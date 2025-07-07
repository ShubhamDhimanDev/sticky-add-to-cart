<?php

namespace App\Http\Controllers;

use App\Models\CartSetting;
use App\Models\ShopDetail;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Osiset\ShopifyApp\Contracts\Commands\Shop;
use Osiset\ShopifyApp\Storage\Models\Plan;
use Illuminate\Support\Arr;

class TestController extends Controller
{
    public function test()
    {
        dd('done');

        $shop = User::where('name', 'dressylove.myshopify.com')->first();

        $api = $shop->api();

        try {
            $response = $api->rest('GET', '/admin/api/2025-04/shop.json');
            if ($response['status'] === 200) {
                $shopDetails = $response['body']['shop'] ?? [];
                $phone = $shopDetails['phone'] ?? 'Phone not available';

                // You can use or store this as needed
                Log::info("Shop phone number: " . $phone);
                dd('working');
            } else {
                Log::error("Failed to fetch shop details for shop: {$shop->shopify_domain}");
            }
        } catch (\Exception $e) {
            Log::error("Error fetching shop details: " . $e->getMessage());
        }

    }

    public function delete(Request $request){
        dd('ok');
    }
}
