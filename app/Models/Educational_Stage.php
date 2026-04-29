<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Educational_Stage extends Model
{  protected $fillable=['name','note','status', 'working_hour_id'];
    use HasFactory;

    public function working_hour()
    {
        return $this->belongsTo(Working_hour::class, 'working_hour_id');
    }
    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }
}
