<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
        {{ __('Scan Results') }}
    </h2>
</x-slot>

<div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">

    <!-- Title -->
    <div class="max-w-4xl mx-auto text-center mb-10">
        <h1 class="text-3xl font-bold text-white">
            Scan Results
            @if($autoDetect)
                <span class="text-[#00c3b3]">(Auto-detected devices)</span>
            @endif
        </h1>
        <p class="text-gray-400 text-sm mt-2">
            Scan mode: <strong>{{ ucfirst($scanMode) }}</strong>
        </p>
    </div>

    @foreach($targets as $target)
        @php
            $targetPorts = collect($ports)->where('target', $target);
            $lowCount = $targetPorts->where('risk', 'Low')->count();
            $mediumCount = $targetPorts->where('risk', 'Medium')->count();
            $highCount = $targetPorts->where('risk', 'High')->count();
        @endphp

        <div class="max-w-4xl mx-auto mb-12">

            <h2 class="text-xl font-semibold text-[#00c3b3] mb-4">Target: {{ $target }}</h2>

            <!-- Risk Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-green-800/30 rounded-lg text-center">
                    <div class="text-3xl font-bold text-green-400">{{ $lowCount }}</div>
                    <div>Low Risk</div>
                </div>
                <div class="p-4 bg-yellow-800/30 rounded-lg text-center">
                    <div class="text-3xl font-bold text-yellow-400">{{ $mediumCount }}</div>
                    <div>Medium Risk</div>
                </div>
                <div class="p-4 bg-red-800/30 rounded-lg text-center">
                    <div class="text-3xl font-bold text-red-400">{{ $highCount }}</div>
                    <div>High Risk</div>
                </div>
            </div>

            <!-- Detailed Findings -->
            <div class="bg-black/40 border border-[#00c3b3]/30 rounded-xl shadow-lg p-6 mb-6 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="text-left border-b border-gray-500">
                        <tr>
                            <th class="p-3">Port</th>
                            <th class="p-3">Service</th>
                            <th class="p-3">Risk Level</th>
                            <th class="p-3">Explanation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($targetPorts as $p)
                            @php
                                $colorClass = $p['risk'] == 'High' ? 'bg-red-600' : ($p['risk']=='Medium' ? 'bg-yellow-400' : 'bg-green-400');
                            @endphp
                            <tr class="border-b border-gray-700">
                                <td class="p-3 font-semibold">{{ $p['port'] }}</td>
                                <td class="p-3">{{ $p['service'] }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 {{ $colorClass }} text-black rounded">{{ $p['risk'] }}</span>
                                </td>
                                <td class="p-3 text-gray-300 text-sm">{{ $p['reason'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    @endforeach

    <!-- Raw Output -->
    <div class="max-w-4xl mx-auto bg-black/40 border border-[#00c3b3]/30 rounded-xl shadow-lg p-6 mb-10">
        <h2 class="text-lg font-semibold text-[#00c3b3] mb-2">Raw Nmap Output</h2>
        <pre class="font-mono text-[#00ff9d] text-sm whitespace-pre-line overflow-auto">{{ $rawOutput }}</pre>
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
