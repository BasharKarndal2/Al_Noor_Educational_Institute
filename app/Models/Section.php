<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    protected $fillable = ['name', 'note', 'status', 'classroom_id', 'maxvalue'];
    use HasFactory;
    public function classroom(){

        return $this->belongsTo(Classroom::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subject');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_section');
    }

    public function subjectTeachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class,);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_section_subject_teacher')
            ->withPivot('teacher_id', 'subject_id')
            ->withTimestamps();
    }
    public function sectionSubjectTeachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class, 'section_id');
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
