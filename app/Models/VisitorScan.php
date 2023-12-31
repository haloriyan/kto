<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id', 'exhibitor_id'
    ];

    public function visitor() {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
    public function exhibitor() {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
