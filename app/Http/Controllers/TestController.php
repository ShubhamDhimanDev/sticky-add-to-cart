<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Osiset\ShopifyApp\Contracts\Commands\Shop;
use Osiset\ShopifyApp\Storage\Models\Plan;

class TestController extends Controller
{
    public function test()
    {
        // Plan::create([
        //     'type'         => 'RECURRING',
        //     'name'         => 'Basic Monthly',
        //     'price'        => 2.00,
        //     'interval'     => 'EVERY_30_DAYS',
        //     'trial_days'   => 0,
        //     'capped_amount' => null,
        //     'terms'        => null,
        //     'test'         => true,      // keeps it in “development” mode
        //     'on_install'   => true,      // show immediately after install
        // ]);
    }
}
