<?php

namespace App\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerDataRequestHandler
{
    public function __invoke(Request $request)
    {
        // Shopify expects a JSON response with “data” array of records.
        // E.g. return customer email, orders, metafields, etc.
        // See https://shopify.dev/apps/webhooks/configuration/webhook-events/gdpr-events#customers-data_request

        $payload = $request->all();
        $shopDomain = $request->header('X-Shopify-Shop-Domain');
        Log::info("GDPR data_request for {$shopDomain}", $payload);

        // TODO: look up customer by $payload['customer']['id'], compile their data.

        return response()->json(['data' => [
            // ['type' => 'customer', 'attributes' => [ ... ] ],
        ]]);
    }
}
