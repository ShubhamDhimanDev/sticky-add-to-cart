<?php

namespace App\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CartSetting;
use App\Models\User;

class ShopDataErasureHandler
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $domain  = $request->header('X-Shopify-Shop-Domain');
        Log::info("GDPR shop/data_erasure for {$domain}", $payload);

        // delete everything you store for that shop
        if ($shop = User::where('name', $domain)->first()) {
            CartSetting::where('user_id', $shop->id)->delete();
            $shop->delete();
        }

        return response()->noContent();
    }
}
