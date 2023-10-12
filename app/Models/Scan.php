<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'visitor_id'
    ];

    public function visitor() {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
    public function seller() {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
