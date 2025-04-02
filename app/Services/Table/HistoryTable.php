<?php

namespace App\Services\Table;

use Illuminate\Http\Request;

class HistoryTable extends Table
{
    const API_URL = 'account.history';
    const SORT_BY = 'views.created_at';
    const PER_PAGE = 10;

    function getData(Request $request, bool $paginate = true, ...$params): array
    {
        $requestData = $request->all();
        $query = auth()->user()->viewsWithContent()->getQuery();
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
            ['label' => 'Content', 'field' => 'content'],
            ['label' => 'Skills', 'field' => 'skills'],
            ['label' => 'Date', 'field' => 'created_at', 'sortable' => true],
        ];

    }

    public function getSearchOn(): array
    {
        return ['content.title'];
    }

    public function getFilters(): array
    {
        return [];
    }
}
