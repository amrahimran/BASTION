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
                    <option value="my_history" {{ request('filter') == 'my_history' ? 'selected' : '' }}>My History</option>
                    <option value="user_management" {{ request('filter') == 'user_management' ? 'selected' : '' }}>User Management</option>
                    <option value="scan" {{ request('filter') == 'scan' ? 'selected' : '' }}>Scan History</option>
                    <option value="simulation" {{ request('filter') == 'simulation' ? 'selected' : '' }}>Simulation</option>
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
                @if($logs->count() > 0)
                    @foreach($logs as $log)
                        @php
                            $rowClass = str_contains(strtolower($log->action), 'user')
                                ? 'bg-gray-700'
                                : 'bg-gray-900';

                            // Try JSON decode first (for simulations)
                            $jsonDetails = json_decode($log->details, true);

                            // Detect scan-style text
                            $isScanText = str_contains($log->details, 'Scans executed:');

                            // Detect user management actions
                            $isUserManagement = str_contains($log->action, 'User');
                        @endphp

                        <tr class="border-b border-gray-700 {{ $rowClass }}">
                            <td class="p-2">
                                @if(request('filter') == 'my_history' && $log->user_id == auth()->id())
                                    <!-- In "My History" filter, show "You" for current user -->
                                    <span class="text-green-400 font-semibold">You</span>
                                @else
                                    <!-- In all other filters, show actual username -->
                                    {{ $log->user->name ?? 'System' }}
                                @endif
                            </td>
                            <td class="p-2 font-semibold">{{ $log->action }}</td>

                            <!-- IMPROVED DETAILS COLUMN -->
                            <td class="p-2 text-sm">

                                {{-- JSON (Simulations) --}}
                                @if(is_array($jsonDetails))
                                    <ul class="space-y-1">
                                        @foreach($jsonDetails as $key => $value)
                                            <li>
                                                <span class="text-gray-400">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                                </span>
                                                <span class="font-semibold text-white">
                                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>

                                {{-- Scan text --}}
                                @elseif($isScanText)
                                    @php
                                        $parts = explode(',', $log->details);
                                    @endphp
                                    <ul class="space-y-1">
                                        @foreach($parts as $part)
                                            @php
                                                [$label, $value] = array_pad(explode(':', $part, 2), 2, null);
                                            @endphp
                                            <li>
                                                <span class="text-gray-400">
                                                    {{ trim($label) }}:
                                                </span>
                                                <span class="font-semibold text-white">
                                                    {{ ucwords(str_replace('_', ' ', trim($value))) }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>

                                {{-- User Management --}}
                                @elseif($isUserManagement)
                                    @php
                                        $text = $log->details;

                                        // Extract names
                                        preg_match_all('/user:\s([^()]+)/i', $text, $names);

                                        // Extract emails
                                        preg_match_all('/\(([^)]+@[^)]+)\)/', $text, $emails);

                                        // Extract roles
                                        preg_match_all('/Role:\s([^\]]+)/i', $text, $roles);

                                        $oldName  = trim($names[1][0] ?? null);
                                        $newName  = trim($names[1][1] ?? null);

                                        $oldEmail = $emails[1][0] ?? null;
                                        $newEmail = $emails[1][1] ?? null;

                                        $oldRole  = $roles[1][0] ?? null;
                                        $newRole  = $roles[1][1] ?? null;
                                    @endphp

                                    <ul class="space-y-1">

                                        {{-- Name --}}
                                        @if($oldName)
                                            <li>
                                                <span class="text-gray-400">Name:</span>
                                                <span class="font-semibold text-white">
                                                    {{ $oldName }}
                                                    @if($newName && $newName !== $oldName)
                                                        → {{ $newName }}
                                                    @endif
                                                </span>
                                            </li>
                                        @endif

                                        {{-- Email --}}
                                        @if($oldEmail)
                                            <li>
                                                <span class="text-gray-400">Email:</span>
                                                <span class="font-semibold text-white">
                                                    {{ $oldEmail }}
                                                    @if($newEmail && $newEmail !== $oldEmail)
                                                        → {{ $newEmail }}
                                                    @endif
                                                </span>
                                            </li>
                                        @endif

                                        {{-- Role --}}
                                        @if($oldRole)
                                            <li>
                                                <span class="text-gray-400">Role:</span>
                                                <span class="font-semibold text-white">
                                                    {{ $oldRole }}
                                                    @if($newRole && $newRole !== $oldRole)
                                                        → {{ $newRole }}
                                                    @endif
                                                </span>
                                            </li>
                                        @endif

                                        {{-- Deleted --}}
                                        @if(str_contains(strtolower($log->action), 'deleted'))
                                            <li class="text-red-400 font-semibold">
                                                User account deleted
                                            </li>
                                        @endif

                                    </ul>


                                {{-- Fallback --}}
                                @else
                                    {{ $log->details }}
                                @endif

                            </td>

                            <td class="p-2">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg">No activity history found</p>
                                <p class="text-sm">
                                    @if(request('filter') == 'my_history')
                                        You haven't performed any actions yet.
                                    @else
                                        No activity logs available.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if($logs->count() > 0)
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

</x-app-layout>