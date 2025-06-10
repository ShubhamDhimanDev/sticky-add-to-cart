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
        Schema::create('cart_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('cart_bg_color')->default('#ffffff');
            $table->string('cart_text_color')->default('#000000');
            $table->string('cart_price_text_color')->default('#ff0000');
            $table->string('btn_bg_color')->default('#000000');
            $table->string('btn_text_color')->default('#ffffff');
            $table->string('btn_onhover_bg_color')->default('#ffffff');
            $table->string('btn_onhover_text_color')->default('#000000');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_settings');
    }
};
