<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Services\Table\ContentTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CreationStudioController extends Controller
{

    public function __construct(
        protected readonly ContentTable $contentTable,
    )
    {}

    public function index(Request $request): JsonResponse|InertiaResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['data' => $this->contentTable->getData($request)]);
        }

        return Inertia::render('Account/CreationStudio/Index', [
            'tableMeta' => $this->contentTable->getMeta(),
        ]);
    }
}
