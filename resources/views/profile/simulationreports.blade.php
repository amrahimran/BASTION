
@php
use App\Models\Simulation;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Simulation Reports') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-4 md:px-6 py-12">

        <!-- Page Header -->
        <div class="max-w-6xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Simulation Reports Dashboard
                    </h1>
                    <p class="text-gray-400">
                        View detailed analytics and visualizations from all cybersecurity simulations
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <!-- FILTER DROPDOWN -->
                    <div x-data="{ open: false }" class="relative">
                        <button
                            @click="open = !open"
                            class="bg-[#102635] border border-[#00c3b3]/40 text-[#00c3b3]
                                   px-4 py-2 rounded-lg font-semibold flex items-center gap-2
                                   hover:bg-[#0d1f2b] transition w-full sm:w-auto"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
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
                                📊 All Simulations ({{ $simulations->total() }})
                            </a>

                            <a href="{{ route('simulation.reports', ['type' => 'MITM']) }}"
                               class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a]">
                                🔄 MITM Simulations
                            </a>

                            <a href="{{ route('simulation.reports', ['type' => 'DDOS']) }}"
                               class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a]">
                                ⚡ DDoS Simulations
                            </a>

                            <a href="{{ route('simulation.reports', ['type' => 'PHISHING']) }}"
                               class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a]">
                                🎣 Phishing Simulations
                            </a>

                            <a href="{{ route('simulation.reports', ['type' => 'PASSIVE_SNIFFING']) }}"
                               class="block px-4 py-2 text-sm text-white hover:bg-[#0b1d2a] rounded-b-lg">
                                👁️ Passive Sniffing
                            </a>
                        </div>
                    </div>

                    <!-- EXPORT -->
                    <a href="{{ route('simulations.export.pdf.all') }}"
                       class="bg-[#00c3b3] text-black px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export All PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        @php
            // Get all simulations for statistics (not paginated)
            $statsQuery = Simulation::query();
            if (request('type')) {
                $statsQuery->where('simulation_type', request('type'));
            }
            $allSimulations = $statsQuery->get();
            
            $totalSimulations = $allSimulations->count();
            $highRiskCount = $allSimulations->where('risk_level', 'High')->count();
            $avgRisk = $allSimulations->avg(function($sim) {
                return $sim->risk_level === 'High' ? 3 : ($sim->risk_level === 'Medium' ? 2 : 1);
            });
            // $pythonCount = $allSimulations->filter(function($sim) {
            //     return isset($sim->metadata['python_simulation']) && $sim->metadata['python_simulation'];
            // })->count();
        @endphp

        @if($totalSimulations > 0)
        <div class="max-w-6xl mx-auto mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-cyan-900/30 to-cyan-800/20 border border-cyan-500/30 rounded-xl p-4">
                    <div class="text-2xl font-bold text-white">{{ $totalSimulations }}</div>
                    <div class="text-sm text-cyan-300">Total Simulations</div>
                </div>
                <div class="bg-gradient-to-r from-red-900/30 to-red-800/20 border border-red-500/30 rounded-xl p-4">
                    <div class="text-2xl font-bold text-white">{{ $highRiskCount }}</div>
                    <div class="text-sm text-red-300">High Risk Events</div>
                </div>
                <div class="bg-gradient-to-r from-blue-900/30 to-blue-800/20 border border-blue-500/30 rounded-xl p-4">
                    <div class="text-2xl font-bold text-white">
                        {{ number_format($avgRisk, 1) }}/3
                    </div>
                    <div class="text-sm text-blue-300">Average Risk Level</div>
                </div>
                {{-- <div class="bg-gradient-to-r from-green-900/30 to-green-800/20 border border-green-500/30 rounded-xl p-4">
                    <div class="text-2xl font-bold text-white">
                        {{ $pythonCount }}
                    </div>
                    <div class="text-sm text-green-300">Python Simulations</div>
                </div> --}}
            </div>
        </div>
        @endif

        <!-- Reports List -->
        <div class="max-w-6xl mx-auto space-y-6">
            @forelse($simulations as $simulation)
                <div class="bg-gradient-to-r from-[#102635] to-[#0d1f2b] border border-gray-700 rounded-xl shadow-lg overflow-hidden">
                    
                    <!-- Report Header -->
                    <div class="p-6 border-b border-gray-700">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $simulation->simulation_type === 'MITM' ? 'bg-cyan-500/20 text-cyan-300' :
                                           ($simulation->simulation_type === 'DDOS' ? 'bg-red-500/20 text-red-300' :
                                           ($simulation->simulation_type === 'PHISHING' ? 'bg-pink-500/20 text-pink-300' :
                                           'bg-blue-500/20 text-blue-300')) }}">
                                        {{ strtoupper(str_replace('_', ' ', $simulation->simulation_type)) }}
                                    </span>
                                    @if($simulation->metadata['python_simulation'] ?? false)
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-300 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9.585 11.692h4.328s2.432.039 2.432-2.35V5.391S16.714 3 11.936 3C7.362 3 7.647 4.983 7.647 4.983l.006 2.055h4.363v.617H5.92s-2.927-.332-2.927 4.282 2.555 4.45 2.555 4.45h1.524v-2.141s-.083-2.554 2.513-2.554zm-.056-5.74a.784.784 0 1 1 0-1.57.784.784 0 1 1 0 1.57z"/>
                                        </svg>
                                        Python Engine
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-white">
                                    Simulation #{{ str_pad($simulation->id, 6, '0', STR_PAD_LEFT) }}
                                </h3>
                                <p class="text-gray-400 text-sm mt-1">
                                    {{ $simulation->created_at->format('F j, Y \a\t g:i A') }} • 
                                    Run by {{ $simulation->user?->name ?? 'System' }}
                                </p>
                            </div>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('simulation.result', $simulation->id) }}"
                                   class="bg-[#102635] border border-[#00c3b3]/40 text-[#00c3b3] px-4 py-2 rounded-lg font-semibold hover:bg-[#00c3b3]/10 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View Details
                                </a>
                                <a href="{{ route('simulations.export.pdf.single', $simulation->id) }}"
                                   class="bg-[#00c3b3] text-black px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Report Body -->
                    <div class="p-6">
                        <!-- Quick Stats -->
                        <div class="mb-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @if($simulation->simulation_type === 'MITM')
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Intercepted Packets</div>
                                    <div class="text-2xl font-bold text-white">{{ $simulation->intercepted_packets }}</div>
                                </div>
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Credentials Exposed</div>
                                    <div class="text-2xl font-bold {{ $simulation->exposed_credentials > 0 ? 'text-red-400' : 'text-green-400' }}">
                                        {{ $simulation->exposed_credentials }}
                                    </div>
                                </div>
                                @elseif($simulation->simulation_type === 'DDOS')
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Request Rate</div>
                                    <div class="text-2xl font-bold text-white">{{ $simulation->request_rate }}/s</div>
                                </div>
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Total Requests</div>
                                    <div class="text-2xl font-bold text-white">{{ $simulation->total_requests }}</div>
                                </div>
                                @elseif($simulation->simulation_type === 'PHISHING')
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Emails Sent</div>
                                    <div class="text-2xl font-bold text-white">{{ $simulation->emails_sent }}</div>
                                </div>
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Success Rate</div>
                                    <div class="text-2xl font-bold text-yellow-400">
                                        {{ $simulation->emails_sent > 0 ? round(($simulation->entered_details / $simulation->emails_sent) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Risk Level Card -->
                                <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Risk Level</div>
                                    <div class="text-xl font-bold
                                        {{ $simulation->risk_level === 'High' ? 'text-red-400' :
                                           ($simulation->risk_level === 'Medium' ? 'text-yellow-400' : 'text-green-400') }}">
                                        {{ $simulation->risk_level }}
                                    </div>
                                </div>
                                
                                <!-- Python Status -->
                                {{-- <div class="bg-black/30 rounded-lg p-3">
                                    <div class="text-sm text-gray-400">Engine</div>
                                    <div class="text-xl font-bold {{ $simulation->metadata['python_simulation'] ?? false ? 'text-green-400' : 'text-blue-400' }}">
                                        {{ $simulation->metadata['python_simulation'] ?? false ? 'Python' : 'PHP' }}
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <!-- Charts Section -->
                        @if($simulation->metadata['chart_data'] ?? false)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-white mb-4">Visual Analytics</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Traffic Chart -->
                                <div class="bg-black/20 rounded-xl p-4">
                                    <h5 class="text-sm font-semibold text-gray-300 mb-3">Traffic Analysis</h5>
                                    <canvas id="trafficChart{{ $simulation->id }}" height="200"></canvas>
                                </div>
                                
                                <!-- Protocol/Distribution Chart -->
                                <div class="bg-black/20 rounded-xl p-4">
                                    <h5 class="text-sm font-semibold text-gray-300 mb-3">Protocol Distribution</h5>
                                    <canvas id="protocolChart{{ $simulation->id }}" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- AI Explanation -->
                        @if(!empty($simulation->ai_explanation))
                        <div>
                            <h4 class="text-lg font-semibold text-white mb-3">Security Analysis</h4>
                            <div class="bg-black/20 rounded-xl p-4 border border-blue-500/20">
                                <div class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                                    {{ Str::limit($simulation->ai_explanation, 300) }}
                                </div>
                                @if(strlen($simulation->ai_explanation) > 300)
                                <a href="{{ route('simulation.result', $simulation->id) }}" 
                                   class="inline-block mt-2 text-blue-400 hover:text-blue-300 text-sm">
                                    Read full analysis →
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="inline-block p-6 bg-[#102635] rounded-2xl border border-gray-700">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-400 mb-2">No Simulation Reports</h3>
                        <p class="text-gray-500 mb-4">Run your first simulation to see reports here</p>
                        <a href="{{ route('simulation.index') }}"
                           class="inline-block bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                            Run Simulation
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($simulations->hasPages())
        <div class="max-w-6xl mx-auto mt-8">
            <div class="bg-[#102635] border border-gray-700 rounded-lg p-4">
                {{ $simulations->links() }}
            </div>
        </div>
        @endif

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts for each simulation
        @foreach($simulations as $simulation)
            @if(isset($simulation->metadata['chart_data']))
                @php
                    $chartData = $simulation->metadata['chart_data'];
                @endphp
                
                // Traffic Chart
                @if(isset($chartData['traffic_over_time']))
                const trafficCtx{{ $simulation->id }} = document.getElementById('trafficChart{{ $simulation->id }}');
                new Chart(trafficCtx{{ $simulation->id }}, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['traffic_over_time']['labels'] ?? ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00']) !!},
                        datasets: [{
                            label: 'Traffic Volume',
                            data: {!! json_encode($chartData['traffic_over_time']['data'] ?? [45, 30, 80, 100, 120, 90]) !!},
                            borderColor: '#00c3b3',
                            backgroundColor: 'rgba(0, 195, 179, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                labels: { color: '#9ca3af' }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#9ca3af' },
                                grid: { color: 'rgba(255,255,255,0.05)' }
                            },
                            x: {
                                ticks: { color: '#9ca3af' },
                                grid: { color: 'rgba(255,255,255,0.05)' }
                            }
                        }
                    }
                });
                @endif
                
                // Protocol Chart
                @if(isset($chartData['protocol_distribution']))
                const protocolCtx{{ $simulation->id }} = document.getElementById('protocolChart{{ $simulation->id }}');
                new Chart(protocolCtx{{ $simulation->id }}, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($chartData['protocol_distribution']['labels'] ?? ['HTTP', 'HTTPS', 'DNS', 'Email', 'FTP']) !!},
                        datasets: [{
                            data: {!! json_encode($chartData['protocol_distribution']['data'] ?? [35, 45, 12, 5, 3]) !!},
                            backgroundColor: {!! json_encode($chartData['protocol_distribution']['colors'] ?? ['#ef4444', '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b']) !!},
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'right',
                                labels: { color: '#9ca3af' }
                            }
                        }
                    }
                });
                @endif
            @endif
        @endforeach
    </script>

</x-app-layout>