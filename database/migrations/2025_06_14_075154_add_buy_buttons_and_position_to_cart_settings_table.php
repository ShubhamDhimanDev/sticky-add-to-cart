<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_settings', function (Blueprint $table) {
            $table->string('buy_bg_color')->default('#000000')->after('btn_onhover_text_color');
            $table->string('buy_text_color')->default('#ffffff')->after('buy_bg_color');
            $table->string('buy_onhover_bg_color')->default('#ffffff')->after('buy_text_color');
            $table->string('buy_onhover_text_color')->default('#000000')->after('buy_onhover_bg_color');
            $table->integer('cart_position_from_bottom')->default(0)->after('buy_onhover_text_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_settings', function (Blueprint $table) {
             $table->dropColumn([
                'buy_bg_color',
                'buy_text_color',
                'buy_onhover_bg_color',
                'buy_onhover_text_color',
                'cart_position_from_bottom',
            ]);
        });
    }
};
