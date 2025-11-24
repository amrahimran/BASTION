<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Scan Reports') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-12">

        <div class="max-w-5xl mx-auto mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Previous Scan Reports</h1>
            <a href="{{ route('scans.export.pdf.all') }}"
               class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:bg-[#00a79e] transition">
               Export All PDF
            </a>
        </div>

        @forelse($scans as $scan)
            @php
                $results = $scan->parsed_results_detailed;
            @endphp

            <div class="p-6 mb-6 border border-gray-700 rounded-xl bg-gradient-to-r from-[#102635] to-[#0d1f2b] shadow-lg">

                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-[#00c3b3] font-bold text-lg">
                        Scan #{{ $scan->id }} - {{ $scan->target ?? 'Auto-detected' }}
                        ({{ ucfirst($scan->scan_mode) }})
                    </h3>
                    <a href="{{ route('scans.export.pdf', $scan->id) }}"
                       class="bg-[#00c3b3] text-black px-3 py-1 rounded hover:bg-[#00a79e] transition text-sm">
                       Export PDF
                    </a>
                </div>

                <p class="text-gray-400 text-sm mb-2">Run at: {{ $scan->created_at }}</p>
                <p class="text-gray-400 text-sm mb-4">Features: {{ implode(', ', $scan->features ?? []) }}</p>
{{-- 
                <!-- Hosts Table -->
                @if(isset($results['hosts']) && count($results['hosts']) > 0)
                    <h4 class="text-gray-200 font-semibold mt-2">Discovered Hosts:</h4>
                    <table class="w-full border-collapse text-sm mb-4">
                        <thead class="bg-[#0f2a3a]">
                            <tr>
                                <th class="p-2 text-left text-[#00c3b3]">IP</th>
                                <th class="p-2 text-left text-[#00c3b3]">MAC</th>
                                <th class="p-2 text-left text-[#00c3b3]">Vendor</th>
                                <th class="p-2 text-left text-[#00c3b3]">Risk</th>
                                <th class="p-2 text-left text-[#00c3b3]">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['hosts'] as $host)
                                <tr class="border-b border-gray-700 hover:bg-gray-800">
                                    <td class="p-2 text-white">{{ $host['ip'] }}</td>
                                    <td class="p-2 text-white">{{ $host['mac'] }}</td>
                                    <td class="p-2 text-white">{{ $host['vendor'] }}</td>
                                    <td class="p-2 font-bold {{ $host['risk_level']=='High'?'text-red-500':($host['risk_level']=='Medium'?'text-yellow-400':'text-green-400') }}">
                                        {{ $host['risk_level'] }}
                                    </td>
                                    <td class="p-2 text-gray-300">{{ $host['description'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <!-- Ports Table -->
                @if(isset($results['ports']) && count($results['ports']) > 0)
                    <h4 class="text-gray-200 font-semibold mt-2">Open Ports:</h4>
                    <table class="w-full border-collapse text-sm mb-4">
                        <thead class="bg-[#0f2a3a]">
                            <tr>
                                <th class="p-2 text-left text-[#00c3b3]">Port</th>
                                <th class="p-2 text-left text-[#00c3b3]">Service</th>
                                <th class="p-2 text-left text-[#00c3b3]">State</th>
                                <th class="p-2 text-left text-[#00c3b3]">Risk</th>
                                <th class="p-2 text-left text-[#00c3b3]">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['ports'] as $port)
                                <tr class="border-b border-gray-700 hover:bg-gray-800">
                                    <td class="p-2 text-white">{{ $port['port'] }}</td>
                                    <td class="p-2 text-white">{{ $port['service'] }}</td>
                                    <td class="p-2 text-white">{{ $port['state'] }}</td>
                                    <td class="p-2 font-bold {{ $port['risk_level']=='High'?'text-red-500':($port['risk_level']=='Medium'?'text-yellow-400':'text-green-400') }}">
                                        {{ $port['risk_level'] }}
                                    </td>
                                    <td class="p-2 text-gray-300">{{ $port['description'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-400 text-sm">No open ports detected.</p>
                @endif --}}

                <!-- Raw Output -->
                <h4 class="text-gray-200 font-semibold mt-3">Raw Output:</h4>
                <pre class="text-xs text-[#00ff9d] whitespace-pre-wrap bg-black/40 p-3 rounded overflow-x-auto">
{{ $scan->raw_output ?? 'No raw output available.' }}
                </pre>

            </div>
        @empty
            <p class="text-center text-gray-400">No scans available yet.</p>
        @endforelse

    </div>
</x-app-layout>
