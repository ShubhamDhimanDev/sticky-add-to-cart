<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Osiset\ShopifyApp\Contracts\Commands\Shop;
use Osiset\ShopifyApp\Storage\Models\Plan;

class TestController extends Controller
{
    public function test()
    {
        $shop = User::where('name', 'dressylove.myshopify.com')->first();

        $charge = $shop->charges()
        ->where('type', 'RECURRING')
        ->where('status', 'ACTIVE')
        ->latest()
        ->first();


        // $shop->update([
        //     'plan_id' => null
        // ]);
        // dd($charge);

        if ($charge) {
            $api = $shop->api();

            try {
                $response = $api->rest('DELETE', "/admin/api/2023-04/recurring_application_charges/{$charge->charge_id}.json");

                if ($response['status'] === 200) {
                    // ✅ Successfully cancelled on Shopify
                    $charge->status = 'CANCELLED';
                    $charge->save();
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
}
