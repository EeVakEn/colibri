<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function contentSkills()
    {
        return $this->hasMany(ContentSkill::class, 'skill_id');
    }
}
