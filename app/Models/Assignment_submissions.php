<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment_submissions extends Model
{
    use HasFactory;
    protected $fillable=['feedback', 'grade', 'submitted_file', 'submitted_text', 'student_id', 'assignment_id','status'];


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    // علاقة مع الواجب
    public function assignment()
    {
        return $this->belongsTo(Assignments::class, 'assignment_id', 'id');
    }


}
