<?php

namespace App\Services\Table;

use Illuminate\Http\Request;

class SkillsTable extends Table
{
    const API_URL = 'account.skills';
    const SORT_BY = 'total_score';
    const SORT_DESC = true;
    const PER_PAGE = 10;

    function getData(Request $request, bool $paginate = true, ...$params): array
    {
        $requestData = $request->all();
        $query = auth()->user()->skillsQuery();
        $query = $this->filter($query, $requestData);
        $query = $this->sort($query, $requestData);
        if ($paginate) {
            return $query->paginate($requestData['perPage'] ?? self::PER_PAGE)->toArray();
        } else {
            return $query->get()->toArray();
        }
    }


    public function getColumns(): array
    {
        return [
            ['label' => 'Skill', 'field' => 'name', 'sortable' => true],
            ['label' => 'Score', 'field' => 'total_score', 'sortable' => true],
        ];

    }

    public function getSearchOn(): array
    {
        return ['skills.name'];
    }

    public function getFilters(): array
    {
        return [];
    }
}
