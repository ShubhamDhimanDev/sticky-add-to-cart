<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyProxyController extends Controller
{
    public function handle(Request $request){
        Log::info("This is from proxy");
        return response()->json([
            'success'  => true,
            'message'  => 'Referrer received via Shopify App Proxy',
        ]);
    }
}
