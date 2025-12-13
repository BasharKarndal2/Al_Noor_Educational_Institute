<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_Requeast extends Model
{
    use HasFactory;
  protected $fillable  = [
            'name',
            'email',
            'phone',
            'date_of_birth',
            'address',
            'status',
            'gender',
            'national_id',
            'image_path',
            'notes','password',
            'parent_id',
            'classroom_id',

        'request_status'];
       

    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'student_subject',
            'student_id',      // المفتاح الخارجي للنموذج الحالي في جدول الربط
            'subject_id'       // المفتاح الخارجي للنموذج المرتبط في جدول الربط
        )
            ->withPivot('teacher_id', 'price')
            ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,       // موديل المعلم الصحيح
            'student_subject',    // جدول الربط
            'student_id',         // مفتاح الطالب في جدول الربط
            'teacher_id'          // مفتاح المعلم في جدول الربط
        )
            ->withPivot('subject_id', 'price')
            ->withTimestamps();
    }
    public function parent()
    {
        return $this->belongsTo(Pearant::class, 'parent_id');
    }
    public function classroom()
    {
        return $this->belongsTo(classroom::class);
    }
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'student__requast_id');
    }
}
