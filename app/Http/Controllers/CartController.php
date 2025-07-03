<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartSettingResource;
use App\Models\CartSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function customize(Request $request)
    {
        if ($request->isMethod('GET')) {
            $cartSettings = CartSetting::where("user_id", Auth::user()->id)->first();
            $shopDomain = auth()->user()->name;
            $extensionId = config('shopify-app.sticky_cart_ext_id');
            $extensionLink = "https://$shopDomain/admin/themes/current/editor?template=product&addAppBlockId=$extensionId/sticky_cat&target=newAppsSection";

            return Inertia::render('customize', [
                'cartSettings' =>  new CartSettingResource($cartSettings),
                'addExtensionLink' => $extensionLink
            ]);
        }

        if ($request->isMethod('POST')) {
            $shop = Auth::user();
            // Build your array of MetafieldsSetInput objects:
            $raw = $request->only([
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
                'cart_position_from_bottom',
            ]);

            $shopIdQuery = $shop
            ->api()
            ->graph('query { shop { id } }');

            $shopGid = data_get($shopIdQuery, 'body.data.shop.id');

            // 2) build your MetafieldsSetInput array
            $metafields = collect($raw)
                ->map(fn($value, $key) => [
                    'ownerId'   => $shopGid,
                    'namespace' => 'customization',
                    'key'       => $key,
                    'value'     => (string) $value,
                    'type'      => 'single_line_text_field',
                ])
                ->values()
                ->all();

            // 3) the mutation remains the same…
            $mutation = <<<'GQL'
            mutation metafieldsSet($metafields: [MetafieldsSetInput!]!) {
            metafieldsSet(metafields: $metafields) {
                metafields { key value }
                userErrors { field message }
            }
            }
            GQL;

            // 4) now this will succeed
            $response = $shop
                ->api()
                ->graph(
                    $mutation,
                    ['metafields' => $metafields]
                );

            // 5) check for errors & persist as before…
            $errors = data_get($response, 'body.data.metafieldsSet.userErrors', []);
            if (!empty($errors) && count($errors) > 0 ) {
                return back()->withErrors($errors);
            }

            CartSetting::updateOrCreate(
                ['user_id' => $shop->id],
                $raw
            );

            return back()->with('success', 'Settings saved in one go!');
        }
    }
}
