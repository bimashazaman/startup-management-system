<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Time Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('time-entries.update', $timeEntry) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Project -->
                            <div>
                                <x-input-label for="project_id" :value="__('Project')" />
                                <select id="project_id" name="project_id"
                                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                    <option value="">Select a project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id', $timeEntry->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                            </div>

                            <!-- Task -->
                            <div>
                                <x-input-label for="task_id" :value="__('Task (Optional)')" />
                                <select id="task_id" name="task_id"
                                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select a task</option>
                                    @foreach ($tasks as $task)
                                        <option value="{{ $task->id }}"
                                            {{ old('task_id', $timeEntry->task_id) == $task->id ? 'selected' : '' }}>
                                            {{ $task->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('task_id')" class="mt-2" />
                            </div>

                            <!-- Start Time -->
                            <div>
                                <x-input-label for="started_at" :value="__('Start Time')" />
                                <x-text-input id="started_at" type="datetime-local" name="started_at" :value="old('started_at', $timeEntry->started_at->format('Y-m-d\TH:i'))"
                                    class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('started_at')" class="mt-2" />
                            </div>

                            <!-- End Time -->
                            <div>
                                <x-input-label for="ended_at" :value="__('End Time')" />
                                <x-text-input id="ended_at" type="datetime-local" name="ended_at" :value="old(
                                    'ended_at',
                                    $timeEntry->ended_at ? $timeEntry->ended_at->format('Y-m-d\TH:i') : '',
                                )"
                                    class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('ended_at')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3"
                                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('description', $timeEntry->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Billable -->
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_billable" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ old('is_billable', $timeEntry->is_billable) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Billable') }}</span>
                            </label>
                        </div>

                        <!-- Hourly Rate -->
                        <div id="hourlyRateField"
                            class="{{ old('is_billable', $timeEntry->is_billable) ? '' : 'hidden' }}">
                            <x-input-label for="hourly_rate" :value="__('Hourly Rate')" />
                            <x-text-input id="hourly_rate" type="number" name="hourly_rate" :value="old('hourly_rate', $timeEntry->hourly_rate)"
                                step="0.01" min="0" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('hourly_rate')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('time-entries.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Time Entry') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isBillableCheckbox = document.querySelector('input[name="is_billable"]');
                const hourlyRateField = document.getElementById('hourlyRateField');

                isBillableCheckbox.addEventListener('change', function() {
                    hourlyRateField.classList.toggle('hidden', !this.checked);
                });
            });
        </script>
    @endpush
</x-app-layout>
