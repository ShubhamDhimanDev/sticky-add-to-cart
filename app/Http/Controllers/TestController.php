<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Osiset\ShopifyApp\Contracts\Commands\Shop;

class TestController extends Controller
{
    public function test(){
        dd('check');
        $shop   = User::where('name', 'dressylove.myshopify.com')->first();
        dd($shop);
    }
}
