<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exhibitor_id', 'visitor_id', 'schedule_id'
    ];

    public function visitor() {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
    public function exhibitor() {
        return $this->belongsTo(Exhibitor::class, 'exhibitor_id');
    }
    public function schedule() {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}
