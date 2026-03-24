<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'image',
        'description',
        'shopee_link',
        'is_claimed',
        'is_active',
    ];
}
