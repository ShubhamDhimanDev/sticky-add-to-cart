<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartSetting extends Model
{
    protected $fillable = [
        'user_id',
        'cart_bg_color',
        'cart_text_color',
        'cart_price_text_color',
        'btn_bg_color',
        'btn_text_color',
        'btn_onhover_bg_color',
        'btn_onhover_text_color',
        'buy_bg_color',
        'buy_text_color',
        'buy_onhover_bg_color',
        'buy_onhover_text_color',
        'cart_position_from_bottom'
    ];
}
