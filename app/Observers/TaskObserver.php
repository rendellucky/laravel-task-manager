<?php

namespace App\Observers;

use App\Models\Task;

/**
 * Keeps task priorities in each project a gap-free sequence: 1, 2, 3, ...
 */
class TaskObserver
{
    /**
     * New tasks go to the bottom of their project's list.
     */
    public function creating(Task $task): void
    {
        $task->priority = $this->nextPriority($task->project_id);
    }

    /**
     * A task moved to another project leaves a gap in the old list
     * and goes to the bottom of the new one.
     */
    public function updating(Task $task): void
    {
        if(!$task->isDirty('project_id')) {
            return;
        }

        $this->moveTasksUp($task->getOriginal('project_id'), $task->getOriginal('priority'));

        $task->priority = $this->nextPriority($task->project_id);
    }

    /**
     * Tasks below a deleted task move one place up.
     */
    public function deleted(Task $task): void
    {
        $this->moveTasksUp($task->project_id, $task->priority);
    }

    private function nextPriority(?int $projectId): int
    {
        return (Task::where('project_id', $projectId)->max('priority') ?? 0) + 1;
    }

    private function moveTasksUp(?int $projectId, int $removedPriority): void
    {
        Task::where('project_id', $projectId)
            ->where('priority', '>', $removedPriority)
            ->decrement('priority');
    }
}
