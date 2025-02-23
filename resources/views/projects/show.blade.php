<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('projects.edit', $project) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit Project
                </a>
                <a href="{{ route('projects.updates.create', $project) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Add Update
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Project Details</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Description</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->description }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                    <span
                                        class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : ($project->status === 'on-hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Estimated Cost</h4>
                                    <p class="mt-1 text-sm text-gray-900">
                                        ${{ number_format($project->estimated_cost, 2) }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Deadline</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->deadline->format('M d, Y') }}</p>
                                </div>
                                @if ($project->team_members)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Team Members</h4>
                                        <div class="mt-1 space-y-1">
                                            @foreach ($project->team_members as $member)
                                                <p class="text-sm text-gray-900">{{ $member }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Recent Updates</h3>
                            <div class="mt-4 space-y-4">
                                @forelse($updates as $update)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $update->title }}</h4>
                                            <span
                                                class="text-xs text-gray-500">{{ $update->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">{{ Str::limit($update->content, 150) }}
                                        </p>
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $update->update_type === 'milestone' ? 'bg-green-100 text-green-800' : ($update->update_type === 'blocker' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($update->update_type) }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No updates yet.</p>
                                @endforelse

                                @if ($updates->hasPages())
                                    <div class="mt-4">
                                        {{ $updates->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
