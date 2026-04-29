<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_schedule_id',
        'attendance_date',
        ''
    ];

    // علاقة مع الحصة اليومية
    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    // علاقة مع تفاصيل حضور الطلاب
    public function details()
    {
        return $this->hasMany(AttendanceDetails::class);
    }
}
