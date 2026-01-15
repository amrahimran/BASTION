<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Simulation Reports') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-12">

        <!-- Page Header -->
        <div class="max-w-5xl mx-auto mb-8 flex justify-between items-center gap-4">

            <h1 class="text-3xl font-bold text-white">
                Previous Simulation Reports
            </h1>

            <div class="flex gap-3 items-center">

                <!-- FILTER DROPDOWN -->
                <div x-data="{ open: false }" class="relative">
                    <button
                        @click="open = !open"
                        class="bg-[#102635] border border-[#00c3b3]/40 text-[#00c3b3]
                               px-4 py-2 rounded-lg font-semibold flex items-center gap-2
                               hover:bg-[#0d1f2b] transition"
                    >
                        Filter Simulations
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-56 bg-[#102635]
                               border border-[#00c3b3]/30 rounded-lg shadow-lg z-50"
                    >
                        <a href="{{ route('simulation.reports') }}"
                           class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a] rounded-t-lg">
                            All Simulations
                        </a>

                        <a href="{{ route('simulation.reports', ['type' => 'MITM']) }}"
                           class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a]">
                            MITM Simulations
                        </a>

                        <a href="{{ route('simulation.reports', ['type' => 'DDOS']) }}"
                           class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a]">
                            DDoS Simulations
                        </a>

                        <a href="{{ route('simulation.reports', ['type' => 'PASSIVE_SNIFFING']) }}"
                           class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a] rounded-b-lg">
                            Passive Sniffing
                        </a>
                    </div>
                </div>

                <!-- EXPORT -->
                <a href="{{ route('simulations.export.pdf.all') }}"
                   class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:opacity-90 transition">
                    Export All PDF
                </a>

            </div>
        </div>

        @php
            $filteredSimulations = request('type')
                ? $simulations->where('simulation_type', request('type'))
                : $simulations;
        @endphp

        @forelse($filteredSimulations as $simulation)

            <div class="p-6 mb-6 border border-gray-700 rounded-xl bg-gradient-to-r from-[#102635] to-[#0d1f2b] shadow-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-[#00c3b3] font-bold text-lg">
                        Simulation #{{ $simulation->id }} —
                        {{ strtoupper(str_replace('_', ' ', $simulation->simulation_type)) }}
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

                    @if($simulation->simulation_type === 'MITM')
                        <p>📡 Intercepted Packets:
                            <span class="text-[#00ff9d]">{{ $simulation->intercepted_packets }}</span>
                        </p>
                        <p>🔑 Credentials Exposed:
                            <span class="text-[#00ff9d]">{{ $simulation->exposed_credentials }}</span>
                        </p>

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
                    @elseif($simulation->simulation_type === 'PASSIVE_SNIFFING')
                        <p>🔓 Unencrypted Services Detected:
                            <span class="text-[#00ff9d]">{{ $simulation->unencrypted_services }}</span>
                        </p>

                        <p>👥 Exposed Network Sessions:
                            <span class="text-[#00ff9d]">{{ $simulation->exposed_sessions }}</span>
                        </p>

                        <p>🔑 Credentials Potentially Visible:
                            <span class="text-[#00ff9d]">{{ $simulation->credentials_visible }}</span>
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
                        {!! $simulation->ai_explanation !!}
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
