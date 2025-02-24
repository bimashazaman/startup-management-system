<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('tasks.edit', $task) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit Task') }}
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        onclick="return confirm('Are you sure you want to delete this task?')">
                        {{ __('Delete Task') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Main Task Information -->
                        <div class="md:col-span-2 space-y-6">
                            <!-- Description -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                                <div class="prose max-w-none">
                                    {{ $task->description }}
                                </div>
                            </div>

                            <!-- Time Tracking -->
                            @if ($task->estimated_hours)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Time Tracking</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm text-gray-500">Time Spent</span>
                                            <span class="text-sm font-medium">{{ $task->actual_hours ?: 0 }}h /
                                                {{ $task->estimated_hours }}h</span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            @php
                                                $progress = $task->actual_hours
                                                    ? min(100, ($task->actual_hours / $task->estimated_hours) * 100)
                                                    : 0;
                                                $progressColor = $progress > 100 ? 'bg-red-600' : 'bg-green-600';
                                            @endphp
                                            <div class="{{ $progressColor }} h-2 rounded-full"
                                                style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Comments Section -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Comments</h3>
                                <div class="space-y-4">
                                    <!-- Comment Form -->
                                    <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                                        @csrf
                                        <input type="hidden" name="commentable_type" value="App\Models\Task">
                                        <input type="hidden" name="commentable_id" value="{{ $task->id }}">
                                        <textarea name="content" rows="3"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Add a comment..."></textarea>
                                        <div class="mt-2 flex justify-end">
                                            <x-primary-button>
                                                {{ __('Post Comment') }}
                                            </x-primary-button>
                                        </div>
                                    </form>

                                    <!-- Comments List -->
                                    @foreach ($task->comments->sortByDesc('created_at') as $comment)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}"
                                                        alt="{{ $comment->user->name }}">
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $comment->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $comment->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 text-sm text-gray-700">
                                                        {{ $comment->content }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Status and Priority -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                    <div class="mt-1">
                                        @switch($task->status)
                                            @case('backlog')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Backlog
                                                </span>
                                            @break

                                            @case('todo')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    To Do
                                                </span>
                                            @break

                                            @case('in_progress')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    In Progress
                                                </span>
                                            @break

                                            @case('in_review')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    In Review
                                                </span>
                                            @break

                                            @case('done')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Done
                                                </span>
                                            @break
                                        @endswitch
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Priority</h4>
                                    <div class="mt-1">
                                        @switch($task->priority)
                                            @case('urgent')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Urgent
                                                </span>
                                            @break

                                            @case('high')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    High
                                                </span>
                                            @break

                                            @case('medium')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Medium
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Low
                                                </span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                            <!-- Project -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Project</h4>
                                <div class="mt-1">
                                    <a href="{{ route('projects.show', $task->project) }}"
                                        class="text-sm text-indigo-600 hover:text-indigo-900">
                                        {{ $task->project->name }}
                                    </a>
                                </div>
                            </div>

                            <!-- Assignee -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Assignee</h4>
                                <div class="mt-1 flex items-center">
                                    @if ($task->assignee)
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}"
                                            alt="{{ $task->assignee->name }}">
                                        <span class="ml-2 text-sm text-gray-900">{{ $task->assignee->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">Unassigned</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Due Date -->
                            @if ($task->due_date)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Due Date</h4>
                                    <div class="mt-1">
                                        <time datetime="{{ $task->due_date->format('Y-m-d') }}"
                                            class="text-sm {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $task->due_date->format('M d, Y') }}
                                        </time>
                                    </div>
                                </div>
                            @endif

                            <!-- Attachments -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-medium text-gray-500">Attachments</h4>
                                    <form action="{{ route('tasks.attachments.store', $task) }}" method="POST"
                                        enctype="multipart/form-data" class="flex items-center">
                                        @csrf
                                        <label for="file-upload"
                                            class="cursor-pointer text-sm text-indigo-600 hover:text-indigo-900">
                                            <span>Add file</span>
                                            <input id="file-upload" name="attachment" type="file" class="sr-only"
                                                onchange="this.form.submit()">
                                        </label>
                                    </form>
                                </div>
                                <div class="mt-1 space-y-2">
                                    @forelse($task->attachments as $attachment)
                                        <div
                                            class="flex items-center justify-between py-2 pl-3 pr-4 text-sm bg-gray-50 rounded-lg">
                                            <div class="flex items-center flex-1 w-0">
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="ml-2 flex-1 w-0 truncate">
                                                    <a href="{{ $attachment->getUrl() }}" target="_blank"
                                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                        {{ $attachment->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $attachment->getFormattedSize() }}</p>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <form action="{{ route('attachments.destroy', $attachment) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-sm font-medium text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No attachments yet</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
