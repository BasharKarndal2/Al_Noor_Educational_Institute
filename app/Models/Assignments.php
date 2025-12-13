<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignments extends Model
{
    protected $fillable = ['title', 'description', 'subject_id', 'section_id', 'teacher_id', 'due_date', 'file_path', 'status'];

    use HasFactory;
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // العلاقة مع المادة
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // العلاقة مع المعلم
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissions()
    {
        return $this->hasMany(Assignment_submissions::class, 'assignment_id', 'id');
    }
}
