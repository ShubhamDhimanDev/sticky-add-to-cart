<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\TestController;
use App\Webhooks\WebhookDispatcher;
use Illuminate\Support\Facades\Route;
use Osiset\ShopifyApp\Http\Middleware\VerifyShopify;

Route::withoutMiddleware([VerifyShopify::class])->controller(TestController::class)->group(function(){
    Route::get('/test', 'test');
    Route::post('webhooks', WebhookDispatcher::class);
});

Route::middleware(['verify.shopify', 'billable'])->controller(CartController::class)->group(function(){
    Route::get('/', 'home')->name('home');
    Route::match(['get', 'post'], '/customize', 'customize')->name('customize');
});

// Route::middleware(['verify.shopify'])->get('/', function () {
//     return Inertia::render('welcome');
// })->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
