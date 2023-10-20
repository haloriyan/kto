<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KmteUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'eligible', 'referer', 'website'
    ];
}
