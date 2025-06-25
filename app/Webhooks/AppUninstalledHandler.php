<?php

namespace App\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CartSetting;
use App\Models\User;
use Carbon\Carbon;

class AppUninstalledHandler
{
    public function __invoke(Request $request)
    {
        $domain = $request->header('X-Shopify-Shop-Domain');
        $shop   = User::where('name', $domain)->first();

        if (! $shop) {
            Log::warning("AppUninstalled: no shop for {$domain}");
            return response()->noContent();
        }

        // 1) delete your DB data
        CartSetting::where('user_id', $shop->id)->delete();

        // 2) delete your metafields via GraphQL (same client you use to write them)
        $result  = $shop->api()->graph(<<<'GQL'
          query { shop { id } }
        GQL);
        $shopGid = data_get($result, 'body.data.shop.id');

        $keys = [
            'cart_bg_color',
            'cart_bg_color',
            'cart_text_color',
            'cart_price_text_color',
            'btn_bg_color',
            'btn_text_color',
            'btn_onhover_bg_color',
            'btn_onhover_text_color',
            'buy_bg_color',
            'buy_text_color',
            'buy_onhover_bg_color',
            'buy_onhover_text_color',
            'cart_position_from_bottom'
        ];
        $inputs = collect($keys)->map(fn($key) => [
            'ownerId'   => $shopGid,
            'namespace' => 'customization',
            'key'       => $key,
        ])->all();

        $mutation = <<<'GQL'
          mutation metafieldsDelete($metafields: [MetafieldsDeleteInput!]!) {
            metafieldsDelete(input: $metafields) {
              deletedIds
              userErrors { field message }
            }
          }
        GQL;

        $resp   = $shop->api()->graph($mutation, ['metafields' => $inputs]);
        $errors = data_get($resp, 'body.data.metafieldsDelete.userErrors', []);
        if (! empty($errors)) {
            Log::error('metafieldsDelete errors on uninstall', [
                'shop'   => $domain,
                'errors' => $errors,
            ]);
        }

        // 3) Cancel Charges
        $charge = $shop->charges()
                ->where('type', 'RECURRING')
                ->where('status', 'ACTIVE')
                ->latest()
                ->first();



        if ($charge) {
            // $api = $shop->api();

            try {
                $charge->status = 'CANCELLED';
                $charge->cancelled_on = Carbon::now();
                $charge->save();
                $charge->delete();
                // $response = $api->rest('DELETE', "/admin/api/2023-04/recurring_application_charges/{$charge->charge_id}.json");
                // if ($response['status'] === 200) {
                // } else {
                //     Log::error("Charge cancellation failed. For shop : " . $shop->name . " " . $shop->id);
                // }
            } catch (\Exception $e) {
                Log::error("Failed to cancel recurring charge: " . $e->getMessage());
            }
        }

        // 3) optionally drop the shop record
        $shop->delete();

        return response()->noContent();
    }
}
