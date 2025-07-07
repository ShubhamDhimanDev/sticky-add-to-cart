<?php

namespace App\Observers;

use App\Models\ShopDetail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            $api = $user->api();

            $response = $api->rest('GET', '/admin/api/2025-04/shop.json');

            if ($response['status'] === 200) {
                $shopData = $response['body']['shop'] ?? [];

                ShopDetail::create([
                    'shop_id'         => $user->id,
                    'name'            => $shopData['name'] ?? null,
                    'domain'          => $shopData['domain'] ?? null,
                    'state'           => $shopData['province'] ?? null,
                    'email'           => $shopData['email'] ?? null,
                    'secondary_email' => $shopData['customer_email'] ?? null,
                    'address1'        => $shopData['address1'] ?? null,
                    'address2'        => $shopData['address2'] ?? null,
                    'zip'             => $shopData['zip'] ?? null,
                    'phone'           => $shopData['phone'] ?? null,
                    'country'         => $shopData['country_name'] ?? null,
                    'shop_owner'      => $shopData['shop_owner'] ?? null,
                ]);

                Log::info("Shop details saved for shop: {$user->name}");
            } else {
                Log::error("Failed to fetch shop info for: {$user->name}");
            }
        } catch (\Exception $e) {
            Log::error("Error in UserObserver (shop fetch): " . $e->getMessage());
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        try {
            if (!ShopDetail::where('shop_id', $user->id)->exists()) {
                $api = $user->api();

                $response = $api->rest('GET', '/admin/api/2025-04/shop.json');

                Log::info("Shop details initiated for shop: {$user->name} : $user->id >> ". $response['status']. "");


                if ($response['status'] === 200) {
                    $shopData = $response['body']['shop'] ?? [];

                    ShopDetail::create([
                        'shop_id'         => $user->id,
                        'name'            => $shopData['name'] ?? null,
                        'domain'          => $shopData['domain'] ?? null,
                        'state'           => $shopData['province'] ?? null,
                        'email'           => $shopData['email'] ?? null,
                        'secondary_email' => $shopData['customer_email'] ?? null,
                        'address1'        => $shopData['address1'] ?? null,
                        'address2'        => $shopData['address2'] ?? null,
                        'zip'             => $shopData['zip'] ?? null,
                        'phone'           => $shopData['phone'] ?? null,
                        'country'         => $shopData['country_name'] ?? null,
                        'shop_owner'      => $shopData['shop_owner'] ?? null,
                    ]);

                    Log::info("Shop details saved for shop: {$user->name}");
                }
            } else {
                Log::error("Failed to fetch shop info for: {$user->name}");
            }
        } catch (\Exception $e) {
            Log::error("Error in UserObserver (shop fetch): " . $e->getMessage());
        }
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
