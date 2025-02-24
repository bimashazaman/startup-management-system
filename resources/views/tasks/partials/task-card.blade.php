<div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
    <div class="p-4">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <a href="{{ route('tasks.show', $task) }}" class="block focus:outline-none">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                </a>
                <p class="mt-1 text-xs text-gray-500 truncate">{{ $task->project->name }}</p>
            </div>
            <div class="flex-shrink-0 ml-4">
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

        @if ($task->description)
            <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $task->description }}</p>
        @endif

        <div class="mt-4">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    @if ($task->assignee)
                        <img class="h-6 w-6 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}"
                            alt="{{ $task->assignee->name }}">
                        <span class="ml-2 text-gray-500">{{ $task->assignee->name }}</span>
                    @else
                        <span class="text-gray-400">Unassigned</span>
                    @endif
                </div>
                @if ($task->due_date)
                    <div class="flex items-center {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-500' }}">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs">{{ $task->due_date->format('M d') }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($task->estimated_hours)
            <div class="mt-3 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                @php
                    $progress = $task->actual_hours
                        ? min(100, ($task->actual_hours / $task->estimated_hours) * 100)
                        : 0;
                    $progressColor = $progress > 100 ? 'bg-red-600' : 'bg-green-600';
                @endphp
                <div class="{{ $progressColor }}" style="width: {{ $progress }}%"></div>
            </div>
            <div class="mt-1 flex justify-between text-xs text-gray-500">
                <span>{{ $task->actual_hours ?: 0 }}h spent</span>
                <span>{{ $task->estimated_hours }}h estimated</span>
            </div>
        @endif

        @if ($task->comments_count > 0 || $task->attachments_count > 0)
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                @if ($task->comments_count > 0)
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <span>{{ $task->comments_count }}</span>
                    </div>
                @endif

                @if ($task->attachments_count > 0)
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span>{{ $task->attachments_count }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
