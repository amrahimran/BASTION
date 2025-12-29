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

        <!-- AI Summary Section -->
        <div class="max-w-5xl mx-auto mb-8 bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">

            <h2 class="text-xl font-bold text-[#00c3b3] mb-4">
                AI Security Summary
            </h2>

            <!-- Risk Legend -->
            <div class="flex gap-3 mb-4">
                <span class="px-3 py-1 rounded bg-red-600 text-white text-xs font-bold">HIGH RISK</span>
                <span class="px-3 py-1 rounded bg-yellow-500 text-black text-xs font-bold">MEDIUM RISK</span>
                <span class="px-3 py-1 rounded bg-green-600 text-white text-xs font-bold">LOW RISK</span>
            </div>

            <div class="bg-black/30 border border-gray-700 rounded-lg p-4 text-gray-200 whitespace-pre-line leading-relaxed">
                {{-- {{ $aiSummary }} --}}
                {{-- {!! $aiSummary !!} --}}
                {!! $scan->ai_summary ?? $aiSummary !!}

            </div>
        </div>

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
