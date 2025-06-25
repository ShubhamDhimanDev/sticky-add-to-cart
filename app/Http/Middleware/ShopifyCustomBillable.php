<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Osiset\ShopifyApp\Storage\Models\Plan;
use Symfony\Component\HttpFoundation\Response;

class ShopifyCustomBillable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shop = auth()->user();

        if(!$shop->hasCharges()){
            $plan_id = Plan::where('name', 'Starter')->first()->id;
            if($shop->charges()->onlyTrashed()->get()->count() > 0){
                $plan_id = Plan::where('name', 'Starter (No Trial)')->first()->id;
            }
            return redirect(route('billing', ['plan' => $plan_id, 'shop' => $shop->name, 'host' =>  app('request')->input('host') ]));
        }
        return $next($request);
    }
}
