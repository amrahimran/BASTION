<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity History') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-8 text-white">

        <!-- Filter Dropdown -->
        <div class="mb-4 flex items-center justify-between">
            <form method="GET" action="{{ route('activity.history') }}" class="flex gap-2 items-center">
                <label for="filter" class="text-gray-300">Filter:</label>
                <select name="filter" id="filter" class="bg-gray-800 text-white p-2 rounded" onchange="this.form.submit()">
                    <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Actions</option>
                    <option value="user_management" {{ request('filter') == 'user_management' ? 'selected' : '' }}>User Management</option>
                    <option value="scan" {{ request('filter') == 'scan' ? 'selected' : '' }}>Scan History</option>
                    <option value="simulation" {{ request('filter') == 'simulation' ? 'selected' : '' }}>Simulation</option>
                    <!-- Add more categories if needed -->
                </select>
            </form>
        </div>

        <table class="table-auto w-full border border-gray-700 text-left">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-2">User</th>
                    <th class="p-2">Action</th>
                    <th class="p-2">Details</th>
                    <th class="p-2">Date</th>
                </tr>
            </thead>

            <tbody class="text-gray-300">
                @foreach($logs as $log)
                    @php
                        // Highlight admin/user management logs
                        $rowClass = str_contains(strtolower($log->action), 'user') ? 'bg-gray-700' : 'bg-gray-900';
                    @endphp
                    <tr class="border-b border-gray-700 {{ $rowClass }}">
                        <td class="p-2">{{ $log->user->name ?? 'System' }}</td>
                        <td class="p-2 font-semibold">{{ $log->action }}</td>
                        <td class="p-2">{{ $log->details }}</td>
                        <td class="p-2">{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>

</x-app-layout>
