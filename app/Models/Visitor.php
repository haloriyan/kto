<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'appointment_eligible', 'token'
    ];

    public function visits() {
        return $this->hasMany(VisitorScan::class, 'visitor_id');
    }

    public function claim() {
        return $this->hasOne(Claim::class, 'visitor_id');
    }
    public function exclusiveClaim() {
        return $this->hasOne(ExclusiveClaim::class, 'visitor_id');
    }
}
