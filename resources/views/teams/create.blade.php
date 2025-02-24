<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('teams.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Team Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3"
                                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Department -->
                        <div>
                            <x-input-label for="department" :value="__('Department')" />
                            <select id="department" name="department"
                                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a department</option>
                                <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>
                                    Engineering</option>
                                <option value="Design" {{ old('department') == 'Design' ? 'selected' : '' }}>Design
                                </option>
                                <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>
                                    Marketing</option>
                                <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales
                                </option>
                                <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance
                                </option>
                                <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>
                                    Operations</option>
                            </select>
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>

                        <!-- Team Lead -->
                        <div>
                            <x-input-label for="team_lead_id" :value="__('Team Lead')" />
                            <select id="team_lead_id" name="team_lead_id"
                                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a team lead</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('team_lead_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('team_lead_id')" class="mt-2" />
                        </div>

                        <!-- Team Members -->
                        <div>
                            <x-input-label for="members" :value="__('Team Members')" />
                            <select id="members" name="members[]" multiple
                                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                size="5">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ in_array($user->id, old('members', [])) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-gray-500">Hold Ctrl (Windows) or Command (Mac) to select
                                multiple members</p>
                            <x-input-error :messages="$errors->get('members')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('teams.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Team') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
