<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'type',
        'frequency',
        'subject_id',
        'teacher_id',
        'section_id',
        'evaluation_date',
        'description',
    ];




    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function results()
    {
        return $this->hasMany(EvaluationResult::class);
    }
}
