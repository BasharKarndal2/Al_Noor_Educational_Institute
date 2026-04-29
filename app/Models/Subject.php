<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'note', 'status', '', 'number_se'];
    use HasFactory;

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_subject');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject');
    }
    public function sectionAssignments()
    {
        return $this->hasMany(SectionSubjectTeacher::class);
    }
    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subject')
            ->withPivot('teacher_id', 'price')
            ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(Assignments::class);
    }
}
