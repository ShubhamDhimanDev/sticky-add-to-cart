<?php

namespace App\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WebhookDispatcher
{
    public function __invoke(Request $request)
    {
        $hmacHeader = $request->header('X-Shopify-Hmac-Sha256', '');
        $data       = (string) $request->getContent();
        $calculated = base64_encode(
            hash_hmac('sha256', $data, config('shopify-app.api_secret'), true)
        );

        if (! hash_equals($hmacHeader, $calculated)) {
            Log::warning("Webhook HMAC verification failed", [
                'topic'       => $request->header('X-Shopify-Topic'),
                'received'    => $hmacHeader,
                'calculated'  => $calculated,
            ]);
            return response('Invalid signature', 401);
        }

        $topic = $request->header('X-Shopify-Topic');

        $line = now()->toIso8601String() . "  -  {$topic}\n";
        Storage::append('webhook_topics.log', $line);

        switch ($topic) {
            case 'app/uninstalled':
                return app(AppUninstalledHandler::class)($request);

            case 'customers/data_request':
                return app(CustomerDataRequestHandler::class)($request);

            case 'customers/data_erasure':
            case 'customers/redact':
                return app(CustomerDataErasureHandler::class)($request);

            case 'shop/data_erasure':
            case 'shop/redact':
                return app(ShopDataErasureHandler::class)($request);

            default:
                Log::warning("Unhandled Shopify webhook topic: {$topic}");
                return response()->noContent();
        }
    }
}
