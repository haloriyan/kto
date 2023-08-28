<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id','name', 'description', 'website', 'phone', 'icon', 'cover', 'slug', 'email',
        'password', 'max_appointment'
    ];
}
