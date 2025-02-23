<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Update') }} - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('projects.updates.store', $project) }}" method="POST" class="space-y-6"
                        enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Update Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Update Content</label>
                            <textarea name="content" id="content" rows="4" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="update_type" class="block text-sm font-medium text-gray-700">Update Type</label>
                            <select name="update_type" id="update_type" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="general" {{ old('update_type') === 'general' ? 'selected' : '' }}>General
                                    Update</option>
                                <option value="milestone" {{ old('update_type') === 'milestone' ? 'selected' : '' }}>
                                    Milestone</option>
                                <option value="blocker" {{ old('update_type') === 'blocker' ? 'selected' : '' }}>Blocker
                                </option>
                            </select>
                            @error('update_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700">Attachments</label>
                            <input type="file" name="attachments[]" id="attachments" multiple
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300">
                            <p class="mt-1 text-xs text-gray-500">You can upload multiple files (images, documents,
                                etc.)</p>
                            @error('attachments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('projects.show', $project) }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Post Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
