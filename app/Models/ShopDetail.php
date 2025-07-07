<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopDetail extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'domain',
        'state',
        'email',
        'secondary_email',
        'address1',
        'address2',
        'zip',
        'phone',
        'country',
        'shop_owner',
    ];
}
