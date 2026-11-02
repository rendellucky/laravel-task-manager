<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReorderTasks
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function handle(?int $projectId, array $taskIds): void
    {
        DB::transaction(function () use ($projectId, $taskIds) {
            $projectTaskIds = Task::where('project_id', $projectId)->lockForUpdate()->pluck('id');

            $isSameList = count($taskIds) === $projectTaskIds->count() && $projectTaskIds->diff($taskIds)->isEmpty();

            if (!$isSameList) {
                throw ValidationException::withMessages([
                    'tasks' => 'The list of tasks is out of date. Please refresh the page and try again.',
                ]);
            }

            foreach ($taskIds as $index => $taskId) {
                Task::whereKey($taskId)->update(['priority' => $index + 1]);
            }
        });
    }
}
