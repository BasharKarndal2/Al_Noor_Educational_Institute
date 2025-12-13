<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'student__requast_id',
        'question_id',
        'selected_option',
        'is_correct',
    ];

    public function studentRequast()
    {
        return $this->belongsTo(Student_Requeast::class);
    }
  
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
