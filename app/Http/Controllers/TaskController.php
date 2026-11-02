<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $selectedProject = $request->filled('project')
            ? Project::findOrFail($request->query('project'))
            : null;

        return view('tasks.index', [
            'projects' => Project::orderBy('name')->get(),
            'selectedProject' => $selectedProject,
            'tasks' => Task::where('project_id', $selectedProject?->id)->orderBy('priority')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated());
        
        return $this->redirectToProject($task->project_id)->with('status', 'Task created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', ['task' => $task, 'projects' => Project::orderBy('name')->get(),]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return $this->redirectToProject($task->project_id)->with('status', 'Task updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return $this->redirectToProject($task->project_id)->with('status', 'Task deleted.');
    }

    private function redirectToProject(?int $projectId): RedirectResponse
    {
        return to_route('tasks.index', ['project' => $projectId]);
    }
}
