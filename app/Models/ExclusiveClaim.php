<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExclusiveClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id', 'is_accepted'
    ];

    public function visitor() {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
}
