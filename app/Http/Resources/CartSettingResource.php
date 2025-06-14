<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_bg_color' => $this->cart_bg_color ?? '#ffffff',
            'cart_text_color' => $this->cart_text_color ?? '#000000',
            'cart_price_text_color' => $this->cart_price_text_color ?? '#ff0000',
            'cart_position_from_bottom' => $this->cart_position_from_bottom ?? '0',
            'btn_bg_color' => $this->btn_bg_color ?? '#000000',
            'btn_text_color' => $this->btn_text_color ?? '#ffffff',
            'btn_onhover_bg_color' => $this->btn_onhover_bg_color ?? '#ffffff',
            'btn_onhover_text_color' => $this->btn_onhover_text_color ?? '#000000',
            'buy_bg_color' => $this->buy_bg_color ?? '#000000',
            'buy_text_color' => $this->buy_text_color ?? '#ffffff',
            'buy_onhover_bg_color' => $this->buy_onhover_bg_color ?? '#ffffff',
            'buy_onhover_text_color' => $this->buy_onhover_text_color ?? '#000000',
        ];
    }
}
