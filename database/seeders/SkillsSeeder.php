<?php

namespace Database\Seeders;

use App\Imports\SkillsImport;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SkillsSeeder extends Seeder
{
    public function run(): void
    {
        Skill::query()->delete();
        \Excel::import(new SkillsImport, Storage::disk('public')->path('imports/skills2.csv'));
    }
}
