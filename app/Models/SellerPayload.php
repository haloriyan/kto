<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPayload extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'type', 'value'
    ];
}
