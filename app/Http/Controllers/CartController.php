<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartSettingResource;
use App\Models\CartSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function home(Request $request)
    {
        // return view('home');
        $cartSettings = CartSetting::where("user_id", Auth::user()->id)->first();
        return Inertia::render('customize', [
            'cartSettings' =>  new CartSettingResource($cartSettings)
        ]);
    }

    public function customize(Request $request)
    {
        if ($request->isMethod('GET')) {
            $cartSettings = CartSetting::where("user_id", Auth::user()->id)->first();
            return Inertia::render('customize', [
                'cartSettings' =>  new CartSettingResource($cartSettings)
            ]);
        }

        if ($request->isMethod('POST')) {
            $shop = Auth::user();

            foreach ($request->all() as $key => $value) {
                $shop->api()->rest('POST', '/admin/api/2024-01/metafields.json', [
                    'metafield' => [
                        'namespace' => 'customization',
                        'key' => $key,
                        'value' => $value,
                        'type' => 'single_line_text_field',
                    ],
                ]);
            }

            CartSetting::updateOrCreate(
                ['user_id' => $shop->id],
                $request->only([
                    'cart_bg_color',
                    'cart_text_color',
                    'cart_price_text_color',
                    'btn_bg_color',
                    'btn_text_color',
                    'btn_onhover_bg_color',
                    'btn_onhover_text_color',
                ])
            );

            return back()->with('success', 'Colors saved.');
        }
    }
}
