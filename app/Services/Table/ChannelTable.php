<?php

namespace App\Services\Table;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ChannelTable extends Table
{
    const API_URL = 'account.channels.index';
    const SORT_BY = 'name';
    const PER_PAGE = 10;

    function getData(Request $request, bool $paginate = true, ...$params): array
    {
        $requestData = $request->all();
        $oneMonthAgo = now()->subMonth();

        // Основной запрос с подсчетом активных подписок
        $query = auth()->user()->channels()
            ->leftJoin('subscriptions', function ($join) use ($oneMonthAgo) {
                $join->on('channels.id', '=', 'subscriptions.channel_id')
                    ->where(function ($q) use ($oneMonthAgo) {
                        $q->where('subscriptions.amount', '>', 0)
                            ->whereDate('subscriptions.created_at', '>=', $oneMonthAgo)
                            ->orWhere('subscriptions.amount', 0);
                    });
            })
            ->addSelect('channels.*')
            ->addSelect(\DB::raw('COUNT(subscriptions.id) as subscriptions_count'))
            ->groupBy('channels.id')->getQuery();

        $query = $this->filter($query, $requestData);
        $query = $this->sort($query, $requestData);
        return $query->paginate($requestData['perPage'] ?? self::PER_PAGE)->toArray();
    }

    public function getColumns(): array
    {
        return [
            ['label' => 'Avatar', 'field' => 'avatar', 'sortable' => true],
            ['label' => 'Subscriptions', 'field' => 'subscriptions_count', 'sortable' => true],
            ['label' => 'Name', 'field' => 'name', 'sortable' => true],
            ['label' => 'Description', 'field' => 'description', 'td-class'=>'w-1/2'],
            ['label' => 'Is Free', 'field' => 'is_free', 'sortable' => true],
            ['label' => 'Price', 'field' => 'subscription_price', 'sortable' => true],
            ['label' => 'Actions', 'field' => 'actions', 'sortable' => false],
        ];

    }

    public function getSearchOn(): array
    {
        return ['name', 'description'];
    }

    public function getFilters(): array
    {
        return [
            [
                'type' => 'select',
                'name' => 'is_free',
                'label' => 'Type',
                'options' => [
                    ['text' => '---', 'value' => null],
                    ['text' => 'Paid', 'value' => 'paid'],
                    ['text' => 'Free', 'value' => 'free'],
                ],
                'defaultOption' => null,
                'callback' => [$this, 'filterIsFree'],
            ],
        ];
    }
    public function filterIsFree($query, $value)
    {
        if ($value === '') {
            return $query;
        }
        return $query->where('is_free', $value==='free');
    }
}
