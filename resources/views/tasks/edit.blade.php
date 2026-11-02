<x-layouts.app title="Edit task">
    <h1 class="mb-6 text-2xl font-semibold">Edit task</h1>

    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4 rounded-lg bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1 block text-sm font-medium">Name</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $task->name) }}"
                class="w-full rounded-md border border-stone-300 px-3 py-2 text-sm"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="project_id" class="mb-1 block text-sm font-medium">Project</label>
            <select id="project_id" name="project_id" class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm">
                <option value="">No project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected($project->id === (int) old('project_id', $task->project_id))>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-stone-500">Moving a task to another project puts it at the bottom of that project's list.</p>
            @error('project_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Save
            </button>
            <a href="{{ route('tasks.index', ['project' => $task->project_id]) }}" class="text-sm text-stone-600 hover:text-stone-900">Cancel</a>
        </div>
    </form>
</x-layouts.app>
