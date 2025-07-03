<?php

namespace App\Http\Controllers;

use App\Models\CartSetting;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Osiset\ShopifyApp\Contracts\Commands\Shop;
use Osiset\ShopifyApp\Storage\Models\Plan;
use Illuminate\Support\Arr;

class TestController extends Controller
{
    public function test()
    {
        dd('done');
    }

    public function delete(Request $request){
        dd('ok');
    }
}
