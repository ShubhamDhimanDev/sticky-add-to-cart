<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\ShopifyCustomBillable;
use App\Webhooks\WebhookDispatcher;
use Illuminate\Support\Facades\Route;
use Osiset\ShopifyApp\Http\Middleware\VerifyShopify;

Route::withoutMiddleware([VerifyShopify::class])->group(function(){

    Route::controller(TestController::class)->group(function(){
        Route::get('/test', 'test');
        Route::post('webhooks', WebhookDispatcher::class);
    });

    Route::controller(HomeController::class)->group(function(){
        Route::get('/', 'home');
    });
});

Route::middleware(['verify.shopify', ShopifyCustomBillable::class])->controller(CartController::class)->group(function(){
    // Route::get('/', 'home');
    Route::match(['get', 'post'], '/customize', 'customize')->name('home');
});
