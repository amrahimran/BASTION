<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Simulation Reports') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-12">

        <!-- Page Header -->
        <div class="max-w-5xl mx-auto mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Previous Simulation Reports</h1>

            <a href="{{ route('simulations.export.pdf.all') }}"
               class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:opacity-90 transition">
                Export All PDF
            </a>
        </div>

        @forelse($simulations as $simulation)

            <div class="p-6 mb-6 border border-gray-700 rounded-xl bg-gradient-to-r from-[#102635] to-[#0d1f2b] shadow-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-[#00c3b3] font-bold text-lg">
                        Simulation #{{ $simulation->id }} —
                        {{ strtoupper($simulation->simulation_type) }}
                    </h3>

                    <a href="{{ route('simulations.export.pdf.single', $simulation->id) }}"
                       class="bg-[#00c3b3] text-black px-3 py-1 rounded hover:opacity-90 transition text-sm">
                        Export PDF
                    </a>
                </div>

                <p class="text-gray-400 text-sm">
                    Run at: {{ $simulation->created_at->format('Y-m-d H:i:s') }}
                </p>

                <p class="text-gray-400 text-sm mb-4">
                    Run By:
                    {{ $simulation->user?->name ?? 'Unknown User' }}
                    (User ID: {{ $simulation->user_id ?? 'N/A' }})
                </p>

                <!-- Simulation Output -->
                <h4 class="text-gray-200 font-semibold mb-2">Simulation Output</h4>

                <div class="bg-black/40 border border-[#00c3b3]/30 rounded-lg p-4 text-sm text-gray-200 space-y-2">

                    {{-- MITM --}}
                    @if($simulation->simulation_type === 'MITM')
                        <p>📡 Intercepted Packets:
                            <span class="text-[#00ff9d]">{{ $simulation->intercepted_packets }}</span>
                        </p>
                        <p>🔑 Credentials Exposed:
                            <span class="text-[#00ff9d]">{{ $simulation->exposed_credentials }}</span>
                        </p>

                    {{-- DDOS --}}
                    @elseif($simulation->simulation_type === 'DDOS')
                        <p>🎯 Target System:
                            <span class="text-[#00ff9d]">{{ $simulation->target }}</span>
                        </p>
                        <p>⚡ Attack Strength:
                            <span class="text-[#00ff9d]">{{ $simulation->ddos_mode }}</span>
                        </p>
                        <p>📊 Requests / Second:
                            <span class="text-[#00ff9d]">{{ $simulation->request_rate }}</span>
                        </p>
                        <p>📦 Total Requests:
                            <span class="text-[#00ff9d]">{{ $simulation->total_requests }}</span>
                        </p>

                    {{-- PHISHING --}}
                    @elseif($simulation->simulation_type === 'PHISHING')
                        <p>📧 Emails Sent:
                            <span class="text-[#00ff9d]">{{ $simulation->emails_sent }}</span>
                        </p>
                        <p>🔗 Links Clicked:
                            <span class="text-[#00ff9d]">{{ $simulation->clicked_links }}</span>
                        </p>
                        <p>📝 Details Entered:
                            <span class="text-[#00ff9d]">{{ $simulation->entered_details }}</span>
                        </p>
                    @endif

                </div>

                <!-- Risk Level -->
                <div class="mt-4">
                    <span class="inline-block px-4 py-1 rounded-full text-xs font-semibold
                        {{ $simulation->risk_level === 'High' ? 'bg-red-600' :
                           ($simulation->risk_level === 'Medium' ? 'bg-yellow-500' : 'bg-green-600') }}">
                        Risk Level: {{ $simulation->risk_level }}
                    </span>
                </div>

                <!-- AI Explanation -->
                @if(!empty($simulation->ai_explanation))
                    <h4 class="text-gray-200 font-semibold mt-4">AI Explanation</h4>
                    <div class="bg-black/40 border border-[#00c3b3]/30 rounded-lg p-4 text-sm text-gray-300 mt-2">
                        {!! ($simulation->ai_explanation) !!}
                    </div>
                @endif

            </div>

        @empty
            <p class="text-center text-gray-400">
                No simulation reports available yet.
            </p>
        @endforelse

    </div>
</x-app-layout>
