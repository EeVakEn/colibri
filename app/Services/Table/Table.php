<?php

namespace App\Services\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Table implements TableInterface
{
    abstract function getData(
        Request $request,
        bool    $paginate = true,
                ...$params,
    ): array;

    public function getMeta(): array
    {
        return [
            'apiUrl' => static::API_URL,
            'columns' => $this->getColumns(),
            'perPage' => static::PER_PAGE,
            'sortBy' => static::SORT_BY,
            'sortDesc' => static::SORT_DESC,
            'filters' => $this->getFilters(),
            'csrf' => csrf_token(),
        ];
    }

    protected function getApiUrl(): string
    {
        return request()->route();
    }
    protected function getLinks(): array
    {
        return [];
    }

    protected function getColumns(): array
    {
        return [];
    }
    protected function getModel(): string
    {
        return '';
    }
    protected function getFilters(): array
    {
        return [];
    }

    protected function filter(Builder $query, array $requestData): Builder
    {
        foreach ($this->_getFilters($requestData) as $filterName=>$filter) {
            if (isset($filter['callback']) && is_callable($filter['callback'])) {
                $filter['callback']($query, $filter['value']);
            }
        }

        if (!empty($requestData['search'])) {
            $query = $query->where(function ($query) use ($requestData) {
                foreach ($this->getSearchOn() as $column) {
                    $query->orWhere($column, 'like', "%{$requestData['search']}%");
                }
            });
        }

        return $query;
    }

    private function _getFilters(array $requestData): array
    {
        $modelFilters = $this->getFilters();

        $filterConditions = json_decode($requestData['filters'] ?? '{}', true);
        $filters = [];

        foreach ($filterConditions as $filterName => $filterValue) {
            $modelFilter = collect($modelFilters)->first(function ($filter) use ($filterName) {
                return $filter['name'] === $filterName;
            });
            $suitableCondition = [
                'value' => $filterValue,
            ];
            $filters[$filterName] = [
                'value' => $suitableCondition['value'],
            ];
            if (isset($modelFilter['callback']) && is_callable($modelFilter['callback'])) {
                $filters[$filterName]['callback'] = $modelFilter['callback'];
            }
        }

        return $filters;
    }

    protected function getSearchOn(): array
    {
        return [];
    }

    protected function sort(Builder $query, array $requestData): Builder
    {
        if (!empty($requestData['sort_by'])) {
            $direction = !empty($requestData['sort_desc']) ? 'desc' : 'asc';
            $query = $query->orderBy(
                $requestData['sort_by'],
                $direction
            );
        }
        return $query;
    }
}
