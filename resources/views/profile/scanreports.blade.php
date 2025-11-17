<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Scan Reports') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-12">

        <div class="max-w-5xl mx-auto mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Previous Scan Reports</h1>
            <a href="{{ route('scan.export.csv') }}"
               class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:bg-[#00a79e] transition">
               Export All CSV
            </a>
        </div>

        @foreach($scans as $scan)
            @php
                $ports = $scan->ports ?? [];
            @endphp

            <div class="p-6 mb-6 border border-gray-700 rounded-xl bg-gradient-to-r from-[#102635] to-[#0d1f2b] shadow-lg">
                <div class="flex justify-between items-center mb-2">
                    <!-- Target Header -->
                    <h3 class="text-[#00c3b3] font-bold text-lg">{{ $scan->target }} ({{ ucfirst($scan->scan_mode) }})</h3>

                    <!-- Export CSV for this scan -->
                    <a href="{{ route('scan.export.single', $scan->id) }}"
                       class="bg-[#00c3b3] text-black px-3 py-1 rounded hover:bg-[#00a79e] text-sm transition">
                       Export CSV
                    </a>
                </div>

                <p class="text-gray-400 text-sm mb-4">Run at: {{ $scan->created_at }}</p>

                <!-- Parsed Results -->
                <h4 class="text-white font-semibold mb-2">Parsed Results:</h4>
                @if(count($ports) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-sm">
                            <thead class="bg-[#0f2a3a]">
                                <tr>
                                    <th class="p-2 text-left text-[#00c3b3]">Port</th>
                                    <th class="p-2 text-left text-[#00c3b3]">Service</th>
                                    <th class="p-2 text-left text-[#00c3b3]">Risk</th>
                                    <th class="p-2 text-left text-[#00c3b3]">Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ports as $p)
                                    @php
                                        $riskColor = match($p['risk'] ?? '') {
                                            'High' => 'bg-red-600 text-white',
                                            'Medium' => 'bg-yellow-400 text-black',
                                            'Low' => 'bg-green-400 text-black',
                                            default => 'bg-gray-500 text-white',
                                        };
                                    @endphp
                                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                                        <td class="p-2 font-semibold text-white">{{ $p['port'] ?? '-' }}</td>
                                        <td class="p-2 text-white">{{ $p['service'] ?? '-' }}</td>
                                        <td class="p-2">
                                            <span class="px-2 py-1 rounded {{ $riskColor }}">
                                                {{ $p['risk'] ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="p-2 text-gray-300 text-sm">{{ $p['reason'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-400 text-sm mb-2">No parsed results available.</p>
                @endif

                <!-- Raw Output -->
                <h4 class="text-white font-semibold mt-4 mb-2">Raw Output:</h4>
                <pre class="text-xs text-[#00ff9d] bg-black/40 p-3 rounded overflow-x-auto">{{ $scan->raw_output ?? 'No raw output available.' }}</pre>
            </div>
        @endforeach

    </div>
</x-app-layout>
