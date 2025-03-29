<?php

namespace App\Imports;

use App\Models\Skill;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SkillsImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Skill
    {
        return new Skill([
            'name' => $row['skill'],
            'description' => $row['description']??'',
        ]);
    }
}
