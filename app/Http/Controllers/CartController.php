<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function home(Request $request){
        // return view('home');
        return Inertia::render('home');
    }

    public function test(Request $request){
        return Inertia::render("test");
    }

    public function customize(Request $request){
        if($request->isMethod('GET')){
            return Inertia::render('customize');
        }

        if($request->isMethod('POST')){
            $shop = Auth::user();

            foreach($request->all() as $key => $value){
                $shop->api()->rest('POST', '/admin/api/2024-01/metafields.json', [
                    'metafield' => [
                        'namespace' => 'customization',
                        'key' => $key,
                        'value' => $value,
                        'type' => 'single_line_text_field',
                    ],
                ]);
            }

            return back()->with('success', 'Colors saved.');
        }
    }
}
