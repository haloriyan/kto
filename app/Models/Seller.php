<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo', 'website', 'name'
    ];

    public function payloads() {
        return $this->hasMany(SellerPayload::class, 'seller_id');
    }
}
