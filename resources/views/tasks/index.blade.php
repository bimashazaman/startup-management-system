<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <a href="{{ route('tasks.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create Task') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="project" class="block text-sm font-medium text-gray-700">Project</label>
                        <select name="project" id="project"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Projects</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ request('project') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assignee" class="block text-sm font-medium text-gray-700">Assignee</label>
                        <select name="assignee" id="assignee"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Assignees</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('assignee') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority" id="priority"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-gray-800 border border-transparent rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kanban Board -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Backlog -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                        <h3 class="text-sm font-medium text-gray-900">Backlog</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                            {{ $tasks->where('status', 'backlog')->count() }}
                        </span>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($tasks->where('status', 'backlog') as $task)
                            @include('tasks.partials.task-card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- Todo -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                        <h3 class="text-sm font-medium text-gray-900">To Do</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                            {{ $tasks->where('status', 'todo')->count() }}
                        </span>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($tasks->where('status', 'todo') as $task)
                            @include('tasks.partials.task-card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- In Progress -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                        <h3 class="text-sm font-medium text-gray-900">In Progress</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                            {{ $tasks->where('status', 'in_progress')->count() }}
                        </span>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($tasks->where('status', 'in_progress') as $task)
                            @include('tasks.partials.task-card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- In Review -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                        <h3 class="text-sm font-medium text-gray-900">In Review</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                            {{ $tasks->where('status', 'in_review')->count() }}
                        </span>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($tasks->where('status', 'in_review') as $task)
                            @include('tasks.partials.task-card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- Done -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                        <h3 class="text-sm font-medium text-gray-900">Done</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                            {{ $tasks->where('status', 'done')->count() }}
                        </span>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($tasks->where('status', 'done') as $task)
                            @include('tasks.partials.task-card', ['task' => $task])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
