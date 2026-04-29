<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pearant extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'relation',
        'address',
        'date_of_birth',
        'status',
        'national_id',
        'image_path',
        'gender',
        'user_id',
        'password',
    ];


    /**
     * Get the students associated with the parent.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id'); 

    }

    public function sudents_requests()
    {
        return $this->hasMany(Student_Requeast::class, 'parent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the requests associated with the parent.
     */
 
}
