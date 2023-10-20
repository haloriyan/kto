<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['date','type'];

    public function appointments() {
        return $this->hasMany(Appointment::class, 'schedule_id');
    }
}
