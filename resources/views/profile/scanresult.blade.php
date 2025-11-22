<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Scan Result
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">

        <!-- Scan Header -->
        <div class="max-w-4xl mx-auto text-center mb-10">
            <h1 class="text-3xl font-bold text-white">
                Scan Results (Scan #{{ $scan->id }})
                @if($scan->auto_detect)
                    <span class="text-[#00c3b3]">(Auto-detected devices)</span>
                @endif
            </h1>
            <p class="text-gray-400 text-sm mt-2">
                Scan mode: <strong>{{ ucfirst($scan->scan_mode) }}</strong>
            </p>
        </div>

        <!-- Parsed Results -->
        {{-- <div class="max-w-5xl mx-auto mb-6">
            <h3 class="text-[#00c3b3] font-bold text-lg mb-2">Parsed Results:</h3>

            @if(isset($scan->parsed_results_detailed['ports']) && count($scan->parsed_results_detailed['ports']) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead class="bg-[#0f2a3a]">
                            <tr>
                                <th class="p-2 text-left text-[#00c3b3]">Port</th>
                                <th class="p-2 text-left text-[#00c3b3]">Service</th>
                                <th class="p-2 text-left text-[#00c3b3]">State</th>
                                <th class="p-2 text-left text-[#00c3b3]">Risk Level</th>
                                <th class="p-2 text-left text-[#00c3b3]">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scan->parsed_results_detailed['ports'] as $p)
                                @php
                                    // Set row color based on risk
                                    $rowColor = match($p['risk']) {
                                        'High' => 'bg-red-700',
                                        'Medium' => 'bg-yellow-700',
                                        'Low' => 'bg-green-700',
                                        default => 'bg-gray-800',
                                    };
                                @endphp
                                <tr class="border-b border-gray-700 hover:bg-gray-900 {{ $rowColor }}">
                                    <td class="p-2 font-semibold text-white">{{ $p['port'] ?? '-' }}</td>
                                    <td class="p-2 text-white">{{ $p['service'] ?? '-' }}</td>
                                    <td class="p-2 text-white">{{ $p['state'] ?? '-' }}</td>
                                    <td class="p-2 font-bold text-white">{{ $p['risk'] }}</td>
                                    <td class="p-2 text-white">{{ $p['description'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400 text-sm">No parsed results available.</p>
            @endif
        </div> --}}

        <!-- Raw Output -->
        <div class="max-w-5xl mx-auto mb-6">
            <h3 class="text-[#00c3b3] font-bold text-lg mb-2">Raw Output:</h3>
            <pre class="text-xs text-[#00ff9d] bg-black/40 p-3 rounded overflow-x-auto whitespace-pre-wrap">
{{ $scan->raw_output ?? 'No raw output available.' }}
            </pre>
        </div>

        <div class="max-w-5xl mx-auto text-right">
            <a href="{{ route('scan.reports') }}"
               class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:bg-[#00a79e] transition">
                Go to All Reports
            </a>
        </div>

        <!-- Buttons -->
        <div class="flex justify-center gap-4 mt-4">
            <a href="{{ route('scan') }}"
                class="bg-[#00c3b3] text-black font-semibold px-8 py-3 rounded-lg hover:bg-[#00a79e] transition">
                Run Another Scan
            </a>

            <a href="/dashboard"
                class="border border-[#00c3b3] text-[#00c3b3] px-8 py-3 rounded-lg hover:bg-[#00c3b3] hover:text-black transition">
                Back to Dashboard
            </a>
        </div>

    </div>
</x-app-layout>
