<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'note', 'status', 'education_stage_id'];
    use HasFactory;
    public function educationalStage()
    {
        return $this->belongsTo(Educational_Stage::class, 'education_stage_id');
    }
    public function sections(){

        return $this->hasMany(Section::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subject');
    }
    public function student_Requeasts()
    {
        return $this->hasMany(Student_Requeast::class, 'classroom_id');
    }
    

    

}
