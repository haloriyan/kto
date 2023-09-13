<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KmtmUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'join_type', 'from_company', 'eligible', 'has_notified'
    ];
}
