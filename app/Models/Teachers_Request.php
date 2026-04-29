<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers_Request extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'identity_number',
        'birth_date',
        'gender',
        'marital_status',
        'education',
        'specialization',
        'experience_years',
        'previous_work',
        'salary_syp',
        'salary_usd',
        'email',
        'password',
        'phone',
        'address',
        'photo_path',
        'status',
        'request_status'
    ];
}
