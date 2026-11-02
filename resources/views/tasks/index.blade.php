<x-layouts.app title="Tasks">
    <header class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <h1 class="text-2xl font-semibold">Tasks</h1>

        <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-2">
            <label for="project" class="text-sm font-medium">Project</label>
            <select id="project" name="project" data-auto-submit class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm">
                <option value="">No project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected($project->id === $selectedProject?->id)>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            <noscript>
                <button type="submit" class="rounded-md border border-stone-300 px-3 py-2 text-sm">Show</button>
            </noscript>
        </form>
    </header>

    <form method="POST" action="{{ route('tasks.store') }}" class="mb-6 flex gap-2">
        @csrf

        <input type="hidden" name="project_id" value="{{ $selectedProject?->id }}">

        <input
            type="text"
            name="name"
            value="{{ $errors->project->any() ? '' : old('name') }}"
            placeholder="New task in {{ $selectedProject?->name ?? 'No project' }}"
            class="flex-1 rounded-md border border-stone-300 bg-white px-3 py-2 text-sm"
        >
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Add task
        </button>
    </form>

    @error('name')
        <p class="-mt-4 mb-6 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if ($tasks->isEmpty())
        <p class="rounded-lg border border-dashed border-stone-300 p-8 text-center text-sm text-stone-500">
            No tasks yet.
        </p>
    @else
        <ol
            id="task-list"
            data-reorder-url="{{ route('tasks.reorder') }}"
            data-project-id="{{ $selectedProject?->id }}"
            class="space-y-2"
        >
            @foreach ($tasks as $task)
                <li data-task-id="{{ $task->id }}" class="flex items-center gap-3 rounded-lg bg-white px-4 py-3 shadow-sm">
                    <span class="drag-handle cursor-grab select-none text-stone-400" title="Drag to reorder">⋮⋮</span>

                    <span class="w-10 text-sm font-semibold text-indigo-600">
                        #<span data-priority>{{ $task->priority }}</span>
                    </span>

                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ $task->name }}</p>
                        <p class="text-xs text-stone-400">
                            Created {{ $task->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <a href="{{ route('tasks.edit', $task) }}" class="text-sm text-stone-600 hover:text-indigo-600">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-stone-600 hover:text-red-600">Delete</button>
                    </form>
                </li>
            @endforeach
        </ol>
    @endif
    
    <section class="mt-10 border-t border-stone-200 pt-6">
        <h2 class="mb-2 text-sm font-medium">New project</h2>

        <form method="POST" action="{{ route('projects.store') }}" class="flex gap-2">
            @csrf
            <input
                type="text"
                name="name"
                value="{{ $errors->project->any() ? old('name') : '' }}"
                placeholder="Project name"
                class="flex-1 rounded-md border border-stone-300 bg-white px-3 py-2 text-sm"
            >
            <button type="submit" class="rounded-md border border-stone-300 bg-white px-4 py-2 text-sm font-medium hover:bg-stone-50">
                Create project
            </button>
        </form>

        @error('name', 'project')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </section>
</x-layouts.app>
