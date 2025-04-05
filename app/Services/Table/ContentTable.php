<?php

namespace App\Services\Table;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ContentTable extends Table
{
    const API_URL = 'account.studio.index';
    const SORT_BY = 'created_at';

    const SORT_DESC = true;
    const PER_PAGE = 10;

    function getData(Request $request, bool $paginate = true, ...$params): array
    {
        $requestData = $request->all();
        $query = auth()->user()->contents()
            ->with('skills')
            ->leftJoin('views', 'contents.id', '=', 'views.content_id')
            ->leftJoin('channels as c1', 'contents.channel_id', '=', 'c1.id')
            ->select([
                'contents.*',
                'c1.name as channel_name',
                \DB::raw('COUNT(views.id) as views_count')
            ])
            ->groupBy('contents.id', 'c1.name')->getQuery();

        $query = $this->filter($query, $requestData);
        $query = $this->sort($query, $requestData);
        return $query->paginate($requestData['perPage'] ?? self::PER_PAGE)->toArray();
    }

    public function getColumns(): array
    {
        return [
            ['label' => 'Preview', 'field' => 'preview_url'],
            ['label' => 'Type', 'field' => 'type', 'sortable' => true],
            ['label' => 'Views', 'field' => 'views_count', 'sortable' => true],
            ['label' => 'Title', 'field' => 'title', 'sortable' => true],
            ['label' => 'Channel', 'field' => 'channel_name', 'sortable' => true],
            ['label' => 'Skills', 'field' => 'processed_at', 'sortable' => true],
            ['label' => 'Created At', 'field' => 'created_at', 'sortable' => true],
            ['label' => 'Updated At', 'field' => 'updated_at', 'sortable' => true],
            ['label' => 'Actions', 'field' => 'actions', 'sortable' => false],
        ];

    }

    public function getSearchOn(): array
    {
        return ['title'];
    }

    public function getFilters(): array
    {
        return [];
    }
}
