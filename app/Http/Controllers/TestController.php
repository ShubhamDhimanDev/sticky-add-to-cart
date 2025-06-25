<?php

namespace App\Http\Controllers;

use App\Models\CartSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Osiset\ShopifyApp\Contracts\Commands\Shop;
use Osiset\ShopifyApp\Storage\Models\Plan;

class TestController extends Controller
{
    public function test()
    {
        dd('ok');

        $shop = User::where('name', 'dressylove.myshopify.com')->first();
        $charge = $shop->charges()
                        ->where('type', 'RECURRING')
                        ->where('status', 'ACTIVE')
                        ->latest()
                        ->first();

        if ($charge) {
            $api = $shop->api();
            try {
                $response = $api->rest('DELETE', "/admin/api/2023-04/recurring_application_charges/{$charge->charge_id}.json");
                dd( $response);

                dd($response['status']);
                if ($response['status'] === 200) {
                    // ✅ Successfully cancelled on Shopify
                    $charge->status = 'CANCELLED';
                    $charge->cancelled_on = Carbon::now();
                    $charge->save();
                    $charge->delete();
                    dd('cacnceleted');
                } else {
                    // ❌ Not successful
                    dd("Charge cancellation failed. Status: " . $response->getStatus());
                    dd("Response body: " . json_encode($response->getDecodedBody()));
                }
            } catch (\Exception $e) {
                dd("Failed to cancel recurring charge: " . $e->getMessage());
            }
        }
        dd('DONE');
        dd(!$shop->plan, !$shop->isFreemium(), !$shop->isGrandfathered());
        // Plan::create([
        //     'type'         => 'RECURRING',
        //     'name'         => 'Basic Monthly',
        //     'price'        => 2.00,
        //     'interval'     => 'EVERY_30_DAYS',
        //     'trial_days'   => 0,
        //     'capped_amount' => null,
        //     'terms'        => null,
        //     'test'         => true,      // keeps it in “development” mode
        //     'on_install'   => true,      // show immediately after install
        // ]);
    }

    public function delete(Request $request){
        // $domain = $request->header('X-Shopify-Shop-Domain');
        $domain = 'dressylove.myshopify.com';
        $shop   = User::where('name', $domain)->first();



        // 3) Cancel Charges
        $charge = $shop->charges()
                ->where('type', 'RECURRING')
                ->where('status', 'ACTIVE')
                ->latest()
                ->first();



        if ($charge) {
            $api = $shop->api();

            try {
                $response = $api->rest('DELETE', "/admin/api/2023-04/recurring_application_charges/{$charge->charge_id}.json");
dd($response);
                if ($response['status'] === 200) {
                    $charge->status = 'CANCELLED';
                    $charge->cancelled_on = Carbon::now();
                    $charge->save();
                    $charge->delete();
                } else {
                    Log::error("Charge cancellation failed. For shop : " . $shop->name . " " . $shop->id);
                }
            } catch (\Exception $e) {
                Log::error("Failed to cancel recurring charge: " . $e->getMessage());
            }
        }

        dd('end');
        // 3) optionally drop the shop record
        $shop->delete();

        return response()->noContent();
    }
}
