<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Working_hour extends Model
{
    protected $fillable = ['name', 'status', 'note'];
    use HasFactory;

    public function educationalStages()
    {
        return $this->hasMany(Educational_Stage::class);
    }
}
