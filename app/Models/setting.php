<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    use HasFactory;
    protected $fillable = [
      
        'location',
        
        'phone2',
        'whatsapp',
        'telegram',
        'email',
        'facebook',
        'instagram',
        'working_hours',
    ];
}
