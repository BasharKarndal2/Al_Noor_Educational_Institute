<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'status',
        'gender',
        'national_id',
        'image_path',
        'notes',
        'user_id',
        'parent_id'
    ];


    /**
     * Get the user associated with the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function sectionSubjectTeachers()
    // {
    //     return $this->belongsToMany(SectionSubjectTeacher::class, 'student_section_subject_teacher', 'student_id', 'section_subject_teacher_id');
    // }

    // علاقة الطلاب مع جدول الربط (pivot) مع الشعبة، المادة، والمعلم
    public function sectionSubjectTeachers()
    {
        return $this->hasMany(StudentSectionSubjectTeacher::class);
    }

    // أو لو تريد روابط مباشرة مع المواد (subject) مع معلومات المعلم والشعبة في pivot
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_section_subject_teacher')
            ->withPivot('section_id', 'teacher_id')
            ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_section_subject_teacher')
            ->withPivot('section_id', 'subject_id')
            ->withTimestamps();
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'student_section_subject_teacher')
            ->withPivot('teacher_id', 'subject_id')
            ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(EvaluationResult::class);
    }
    public function parent()
    {
        return $this->belongsTo(Pearant::class, 'parent_id');
    }
    public function sectionsSubjectsTeachers()
    {
        return $this->hasMany(StudentSectionSubjectTeacher::class);
    }

    public function submissions()
    {
        return $this->hasMany(Assignment_submissions::class, 'student_id', 'id');
    }

}
