<?php

namespace App\Services\Table;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ContentTable extends Table
{
    const API_URL = 'account.studio.index';
    const SORT_BY = 'created_at';
    const PER_PAGE = 10;

    function getData(Request $request, bool $paginate = true, ...$params): array
    {
        $requestData = $request->all();
        $query = auth()->user()->contents()->with('skills')->getQuery()
            ->select(['contents.*', 'channels.name as channel_name', 'channels.id as channel_id']);

        $query = $this->filter($query, $requestData);
        $query = $this->sort($query, $requestData);
        return $query->paginate($requestData['perPage'] ?? self::PER_PAGE)->toArray();
    }

    public function getColumns(): array
    {
        return [
            ['label' => 'Preview', 'field' => 'preview_url'],
            ['label' => 'Type', 'field' => 'type', 'sortable' => true],
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
        return [
//            [
//                'type' => 'select',
//                'name' => 'is_free',
//                'label' => 'Type',
//                'options' => [
//                    ['text' => '---', 'value' => null],
//                    ['text' => 'Paid', 'value' => 'paid'],
//                    ['text' => 'Free', 'value' => 'free'],
//                ],
//                'defaultOption' => null,
//                'callback' => [$this, 'filterIsFree'],
//            ],
        ];
    }
//    public function filterIsFree($query, $value)
//    {
//        if ($value === '') {
//            return $query;
//        }
//        return $query->where('is_free', $value==='free');
//    }
}
