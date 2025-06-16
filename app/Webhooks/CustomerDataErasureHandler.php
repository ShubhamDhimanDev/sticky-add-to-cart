<?php

namespace App\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerDataErasureHandler
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $shopDomain = $request->header('X-Shopify-Shop-Domain');
        Log::info("GDPR data_erasure for {$shopDomain}", $payload);

        // TODO: anonymize or delete the customer record in your DB.
        // You do NOT return any personal data here.

        return response()->noContent();
    }
}
