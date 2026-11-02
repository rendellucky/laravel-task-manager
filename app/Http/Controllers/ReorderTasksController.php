<?php

namespace App\Http\Controllers;

use App\Actions\ReorderTasks;
use App\Http\Requests\ReorderTasksRequest;
use Illuminate\Http\Response;

class ReorderTasksController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReorderTasksRequest $request, ReorderTasks $reorderTasks): Response
    {
        $reorderTasks->handle(
            $request->validated('project_id') ?: null,
            $request->validated('tasks'),
        );

        return response()->noContent();
    }
}
