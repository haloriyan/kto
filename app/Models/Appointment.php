<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exhibitor_id', 'visitor_id', 'schedule_id', 
        'buyer_id', 'seller_id'
    ];

    public function buyer() {
        return $this->belongsTo(KmtmUser::class, 'buyer_id');
    }
    public function seller() {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function schedule() {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}
