<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSubjectTeacher extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'subject_id', 'teacher_id'];
    protected $table = 'section_subject_teacher';

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_section_subject_teacher');
    }

}
