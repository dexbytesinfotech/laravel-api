<?php

namespace App\Models\Agencies;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'agency_name',
        'city',
        'address',
        'account_status',
        'status',
        'phone_number',
        'country_code',
    ];
}
