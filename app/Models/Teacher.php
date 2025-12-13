<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'national_id',
        'birth_date',
        'gender',
        'marital',
        'specialization',
        'experience',
        'phone',
        'email',
        'status',
        'hire_date',
        'address',
        'image_path',
        'user_id',
    ];

    // خلي Laravel يتعامل مع الحقول كتواريخ (Carbon)
    protected $dates = ['birth_date', 'hire_date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function subjects()
    {

        return $this->belongsToMany(Subject::class,'teacher_subject')->withPivot('price');
    
    }

    public function sections()
    {

        return $this->belongsToMany(Section::class, 'teacher_section');
    }
    public function sectionSubjectAssignments()
    {
        return $this->hasMany(SectionSubjectTeacher::class);
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }


    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
    public function assignments()
    {
        return $this->hasMany(Assignments::class);
    }
}