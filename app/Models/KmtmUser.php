<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KmtmUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'website', 'join_type', 'reference', 'from_company', 'line_of_business', 'company_established',
        'custom_field', 'eligible', 'has_notified', 'interesting_sellers'
    ];
}
