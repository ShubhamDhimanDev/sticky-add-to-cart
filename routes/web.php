<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware(['verify.shopify'])->controller(CartController::class)->group(function(){
    Route::get('/', 'home')->name('home');
    Route::get('/test', 'test')->name('test');
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
