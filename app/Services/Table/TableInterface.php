<?php

namespace App\Services\Table;

use Illuminate\Http\Request;

interface TableInterface
{
    const PER_PAGE = 10;
    const API_URL = '';

    const SORT_DESC = false;

    public function getMeta(): array;

    public function getData(
        Request $request,
        bool $paginate = true,
                ...$params,
    ): array;
}
