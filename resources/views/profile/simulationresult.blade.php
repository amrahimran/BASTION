<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Simulation Result
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-4 md:px-6 py-10 text-white max-w-6xl mx-auto">

        <!-- RISK LEVEL BANNER - NEW SECTION AT TOP -->
        <div class="mb-6">
            @php
                $riskColors = [
                    'Critical' => 'bg-red-600 border-red-500 text-white',
                    'High' => 'bg-red-700/80 border-red-500 text-white',
                    'Medium' => 'bg-yellow-600/80 border-yellow-500 text-white',
                    'Low' => 'bg-green-600/80 border-green-500 text-white',
                    'Info' => 'bg-blue-600/80 border-blue-500 text-white'
                ];
                $riskIcon = [
                    'Critical' => '⚠️',
                    'High' => '🔴',
                    'Medium' => '🟡',
                    'Low' => '🟢',
                    'Info' => 'ℹ️'
                ];
                $riskLevel = $simulation->risk_level ?? 'Medium';
                $colorClass = $riskColors[$riskLevel] ?? $riskColors['Medium'];
                $icon = $riskIcon[$riskLevel] ?? $riskIcon['Medium'];
            @endphp
            
            <div class="border-l-4 {{ str_replace('border-', 'border-l-', explode(' ', $colorClass)[1]) }} {{ $colorClass }} rounded-lg p-5 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">{{ $icon }}</div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold">Security Risk Level: {{ $riskLevel }}</h3>
                        <p class="text-sm opacity-90 mt-1">
                            @if($riskLevel === 'Critical' || $riskLevel === 'High')
                                ⚠️ Immediate attention required. This security issue could seriously impact company operations.
                            @elseif($riskLevel === 'Medium')
                                ⚠️ Review recommended. This security issue could affect staff productivity or data safety.
                            @else
                                ℹ️ Awareness recommended. Good opportunity to improve security practices.
                            @endif
                        </p>
                    </div>
                    <div class="bg-black/30 px-4 py-2 rounded-lg border border-white/20">
                        <span class="font-bold text-lg">{{ $riskLevel }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Python Engine Indicator -->
        @if(isset($simulation->metadata['python_simulation']) && $simulation->metadata['python_simulation'])
        <div class="mb-6 bg-gradient-to-r from-cyan-900/30 to-emerald-900/30 border border-cyan-500/40 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-500/20 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9.585 11.692h4.328s2.432.039 2.432-2.35V5.391S16.714 3 11.936 3C7.362 3 7.647 4.983 7.647 4.983l.006 2.055h4.363v.617H5.92s-2.927-.332-2.927 4.282 2.555 4.45 2.555 4.45h1.524v-2.141s-.083-2.554 2.513-2.554zm-.056-5.74a.784.784 0 1 1 0-1.57.784.784 0 1 1 0 1.57z"/>
                        <path d="M18.28 11.692h-4.329s-2.43.039-2.43-2.35V5.391s-.369-2.391 4.409-2.391c4.573 0 4.288 1.983 4.288 1.983l-.006 2.055h-4.363v.617h6.096s2.927-.332 2.927 4.282-2.555 4.45-2.555 4.45h-1.524v-2.141s.083-2.554-2.513-2.554zm.056-5.74a.784.784 0 1 0 0-1.57.784.784 0 1 0 0 1.57z"/>
                        <path d="m15.012 17.291-4.408 1.46.991-3.628-3.406-2.585 4.209-.187 1.614-3.758 1.614 3.758 4.209.187-3.406 2.585.991 3.628z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-cyan-300">Python Security Engine</h4>
                    <p class="text-sm text-cyan-100/70">This simulation was powered by advanced Python security analysis</p>
                </div>
            </div>
        </div>
        @endif

        <!-- QUICK SUMMARY FOR NON-TECHNICAL STAFF - NEW SECTION -->
        <div class="bg-[#102635] border border-blue-500/40 rounded-xl p-6 mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-500/20 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-blue-300">Quick Summary: What This Means For You</h3>
            </div>
            
            <div class="space-y-4">
                @if($simulation->simulation_type === 'MITM')
                <div class="bg-blue-900/20 p-4 rounded-lg border border-blue-500/30">
                    <h4 class="font-semibold text-blue-300 mb-2">Man-in-the-Middle Attack Simulation</h4>
                    <p class="text-gray-200">This shows what happens when someone secretly listens to network traffic, like on public Wi-Fi.</p>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold">{{ $simulation->intercepted_packets }}</div>
                            <div class="text-sm text-gray-400">Data packets intercepted</div>
                        </div>
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold {{ $simulation->exposed_credentials > 0 ? 'text-red-400' : 'text-green-400' }}">
                                {{ $simulation->exposed_credentials }}
                            </div>
                            <div class="text-sm text-gray-400">Login details exposed</div>
                        </div>
                    </div>
                </div>
                @elseif($simulation->simulation_type === 'DDOS')
                <div class="bg-red-900/20 p-4 rounded-lg border border-red-500/30">
                    <h4 class="font-semibold text-red-300 mb-2">DDoS Attack Simulation</h4>
                    <p class="text-gray-200">This shows what happens when too many fake requests overwhelm a system, causing it to slow down or stop working.</p>
                    <div class="mt-3 grid grid-cols-3 gap-3">
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold">{{ $simulation->request_rate }}</div>
                            <div class="text-sm text-gray-400">Requests per second</div>
                        </div>
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold">{{ $simulation->total_requests }}</div>
                            <div class="text-sm text-gray-400">Total requests made</div>
                        </div>
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold">{{ $simulation->duration }}s</div>
                            <div class="text-sm text-gray-400">Attack duration</div>
                        </div>
                    </div>
                </div>
                @elseif($simulation->simulation_type === 'PHISHING')
                <div class="bg-pink-900/20 p-4 rounded-lg border border-pink-500/30">
                    <h4 class="font-semibold text-pink-300 mb-2">Phishing Email Simulation</h4>
                    <p class="text-gray-200">This shows how many staff might click on fake emails and enter sensitive information.</p>
                    <div class="mt-3 grid grid-cols-3 gap-3">
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold">{{ $simulation->emails_sent }}</div>
                            <div class="text-sm text-gray-400">Fake emails sent</div>
                        </div>
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold text-yellow-400">{{ $simulation->clicked_links }}</div>
                            <div class="text-sm text-gray-400">Clicked links</div>
                        </div>
                        <div class="bg-black/30 p-3 rounded">
                            <div class="text-lg font-bold text-red-400">{{ $simulation->entered_details }}</div>
                            <div class="text-sm text-gray-400">Entered details</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="bg-green-900/20 p-4 rounded-lg border border-green-500/30">
                    <h4 class="font-semibold text-green-300 mb-2">Key Message</h4>
                    <p class="text-gray-200">
                        This is a <span class="font-semibold text-white">simulation only</span> - no real attack occurred. 
                        It helps us understand vulnerabilities and improve our security.
                    </p>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-[#00c3b3] mb-2">
                        {{ ucwords(str_replace('_', ' ', $simulation->simulation_type)) }} Simulation Completed

                    </h2>
                    @if($simulation->simulation_type === 'MITM')
                        <p class="text-gray-300 text-sm">
                            Shows how data can be intercepted on unsecured networks.
                        </p>
                    @elseif($simulation->simulation_type === 'DDOS')
                        <p class="text-gray-300 text-sm">
                            Shows how overwhelming traffic can make systems unavailable.
                        </p>
                    @elseif($simulation->simulation_type === 'PHISHING')
                        <p class="text-gray-300 text-sm">
                            Shows how fake emails can trick staff into revealing information.
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">Simulation ID</div>
                    <div class="font-mono text-xs text-gray-300">#{{ str_pad($simulation->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $simulation->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- MITM Specific Charts & Metrics -->
        @if($simulation->simulation_type === 'MITM')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Risk Assessment Card -->
            <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-[#00c3b3] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                    </svg>
                    Risk Assessment
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-300">Attack Severity</span>
                            <span class="text-sm font-semibold {{ $simulation->risk_level === 'High' ? 'text-red-400' : ($simulation->risk_level === 'Medium' ? 'text-yellow-300' : 'text-green-300') }}">
                                {{ $simulation->risk_level }}
                            </span>
                        </div>
                        <div class="w-full bg-[#0b1d2a] rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $simulation->risk_level === 'High' ? 'bg-red-500' : ($simulation->risk_level === 'Medium' ? 'bg-yellow-500' : 'bg-green-500') }}"
                                 style="width: {{ $simulation->risk_level === 'High' ? '85%' : ($simulation->risk_level === 'Medium' ? '60%' : '30%') }}">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $simulation->intercepted_packets }}</div>
                            <div class="text-sm text-gray-400">Intercepted Packets</div>
                        </div>
                        <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                            <div class="text-2xl font-bold {{ $simulation->exposed_credentials > 0 ? 'text-red-400' : 'text-green-400' }}">
                                {{ $simulation->exposed_credentials }}
                            </div>
                            <div class="text-sm text-gray-400">Credentials Exposed</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Traffic Composition Chart -->
            <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-[#00c3b3] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    Traffic Analysis
                </h3>
                <p class="text-sm text-gray-400 mb-4">Shows how much data could potentially be seen by an attacker on an unsecured network.</p>
                <canvas id="mitmTrafficChart"></canvas>
            </div>
        </div>

        <!-- Protocol Breakdown -->
        <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 text-[#00c3b3]">Protocol Distribution</h3>
            <p class="text-sm text-gray-400 mb-4">Different types of network traffic. Red means less secure, green means more secure.</p>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $protocols = [
                        ['name' => 'HTTP', 'color' => 'bg-red-500', 'percentage' => 35, 'risk' => 'High', 'desc' => 'Not secure'],
                        ['name' => 'HTTPS', 'color' => 'bg-green-500', 'percentage' => 45, 'risk' => 'Low', 'desc' => 'Secure'],
                        ['name' => 'DNS', 'color' => 'bg-blue-500', 'percentage' => 12, 'risk' => 'Medium', 'desc' => 'Somewhat secure'],
                        ['name' => 'Email', 'color' => 'bg-purple-500', 'percentage' => 5, 'risk' => 'High', 'desc' => 'Risky if not encrypted'],
                        ['name' => 'File Transfer', 'color' => 'bg-yellow-500', 'percentage' => 3, 'risk' => 'High', 'desc' => 'Often unsecured'],
                    ];
                @endphp
                
                @foreach($protocols as $protocol)
                <div class="text-center bg-[#0b1d2a]/50 p-3 rounded-lg">
                    <div class="relative w-20 h-20 mx-auto mb-2">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="#1a202c"
                                  stroke-width="3"/>
                            <path d="M18 2.0845
                                    a 15.9155 15.9155 0 0 1 0 31.831
                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="currentColor"
                                  class="{{ str_replace('bg-', 'text-', $protocol['color']) }}"
                                  stroke-width="3"
                                  stroke-dasharray="{{ $protocol['percentage'] }}, 100"
                                  stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-white font-bold">{{ $protocol['percentage'] }}%</span>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-white">{{ $protocol['name'] }}</div>
                    <div class="text-xs {{ $protocol['risk'] === 'High' ? 'text-red-400' : ($protocol['risk'] === 'Medium' ? 'text-yellow-400' : 'text-green-400') }} mb-1">
                        {{ $protocol['risk'] }} Risk
                    </div>
                    <div class="text-xs text-gray-400">{{ $protocol['desc'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- DDOS Specific Visualizations -->
        @if($simulation->simulation_type === 'DDOS')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Attack Metrics -->
            <div class="bg-[#102635] border border-red-500/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-red-400">Attack Metrics</h3>
                <p class="text-sm text-gray-400 mb-4">These numbers show how intense the simulated attack was.</p>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-300">Request Rate</span>
                            <span class="text-sm font-semibold text-white">{{ $simulation->request_rate }} req/s</span>
                        </div>
                        <div class="w-full bg-[#0b1d2a] rounded-full h-2.5">
                            <div class="h-2.5 rounded-full bg-gradient-to-r from-red-500 to-orange-500"
                                 style="width: {{ min(100, $simulation->request_rate / 5) }}%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $simulation->total_requests }}</div>
                            <div class="text-sm text-gray-400">Total Requests</div>
                        </div>
                        <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $simulation->duration }}s</div>
                            <div class="text-sm text-gray-400">Duration</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Traffic Over Time -->
            <div class="bg-[#102635] border border-red-500/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-red-400">Traffic Over Time</h3>
                <p class="text-sm text-gray-400 mb-4">This chart shows how the attack started small, peaked, and then stopped. Real attacks look similar.</p>
                <canvas id="ddosTrafficChart"></canvas>
            </div>
        </div>
        @endif

        <!-- PHISHING Charts -->
        @if($simulation->simulation_type === 'PHISHING')
        <div class="bg-[#102635] border border-pink-400/40 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-pink-300 mb-6">Phishing Campaign Analysis</h3>
            <p class="text-sm text-gray-400 mb-4">This shows how many staff might be tricked by fake emails in a real attack.</p>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Metrics Grid -->
                <div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-[#0b1d2a] p-4 rounded-xl">
                            <div class="text-3xl font-bold text-white">{{ $simulation->emails_sent }}</div>
                            <div class="text-sm text-gray-400">Emails Sent</div>
                        </div>
                        <div class="bg-[#0b1d2a] p-4 rounded-xl">
                            <div class="text-3xl font-bold text-yellow-300">{{ $simulation->clicked_links }}</div>
                            <div class="text-sm text-gray-400">Links Clicked</div>
                        </div>
                        <div class="bg-[#0b1d2a] p-4 rounded-xl">
                            <div class="text-3xl font-bold text-red-400">{{ $simulation->entered_details }}</div>
                            <div class="text-sm text-gray-400">Details Entered</div>
                        </div>
                        <div class="bg-[#0b1d2a] p-4 rounded-xl">
                            <div class="text-2xl font-bold {{ $simulation->risk_level === 'High' ? 'text-red-400' : 'text-yellow-300' }}">
                                {{ $simulation->risk_level }}
                            </div>
                            <div class="text-sm text-gray-400">Risk Level</div>
                        </div>
                    </div>
                    
                    <!-- Conversion Funnel -->
                    <div class="bg-[#0b1d2a] p-4 rounded-xl">
                        <h4 class="text-sm font-semibold text-gray-300 mb-3">Conversion Funnel</h4>
                        <p class="text-xs text-gray-400 mb-3">Shows how many people went from receiving email to entering details.</p>
                        <div class="space-y-2">
                            @php
                                $steps = [
                                    ['label' => 'Emails Sent', 'value' => $simulation->emails_sent, 'color' => 'bg-blue-500', 'desc' => 'Fake emails sent to staff'],
                                    ['label' => 'Links Clicked', 'value' => $simulation->clicked_links, 'color' => 'bg-yellow-500', 'desc' => 'Clicked the link in email'],
                                    ['label' => 'Details Entered', 'value' => $simulation->entered_details, 'color' => 'bg-red-500', 'desc' => 'Entered login details'],
                                ];
                            @endphp
                            
                            @foreach($steps as $step)
                            <div>
                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                    <span>{{ $step['label'] }}</span>
                                    <span>{{ $step['value'] }} ({{ round(($step['value'] / $simulation->emails_sent) * 100, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-gray-800 rounded-full h-2">
                                    <div class="{{ $step['color'] }} h-2 rounded-full"
                                         style="width: {{ ($step['value'] / $simulation->emails_sent) * 100 }}%">
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $step['desc'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-[#0b1d2a] p-4 rounded-xl">
                    <canvas id="phishingChart"></canvas>
                    <p class="text-xs text-gray-400 mt-3 text-center">Blue: Emails sent | Yellow: Links clicked | Red: Details entered</p>
                </div>
            </div>
        </div>
        @endif

        <!-- AI Explanation - IMPROVED FOR NON-TECHNICAL USERS -->
        <!-- AI Explanation -->
<div class="bg-[#0b1d2a] border border-blue-400 rounded-xl p-6 mb-6">
    <h3 class="text-lg font-semibold text-blue-300 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
        </svg>
        Security Explanation
    </h3>
    
    <div class="text-gray-100 text-sm leading-relaxed whitespace-pre-line">
        {{ $simulation->ai_explanation ?? 'AI explanation not available.' }}
    </div>
</div>
                
                <!-- Key Takeaways Box -->
                <div class="bg-blue-900/30 p-4 rounded-lg border border-blue-500/30 mt-4">
                    <h4 class="font-semibold text-blue-300 mb-2">💡 What This Means For Our Company:</h4>
                    @if($simulation->simulation_type === 'MITM')
                    <ul class="list-disc ml-5 text-gray-200 space-y-1">
                        <li>Public Wi-Fi can be risky for company data</li>
                        <li>Always look for "HTTPS" in website addresses</li>
                        <li>Use company VPN when working remotely</li>
                        <li>Report suspicious network activity to IT</li>
                    </ul>
                    @elseif($simulation->simulation_type === 'DDOS')
                    <ul class="list-disc ml-5 text-gray-200 space-y-1">
                        <li>Our website could become slow or unavailable during attacks</li>
                        <li>Customers might not be able to reach us</li>
                        <li>IT needs proper protection systems in place</li>
                        <li>Have a backup plan for important online services</li>
                    </ul>
                    @elseif($simulation->simulation_type === 'PHISHING')
                    <ul class="list-disc ml-5 text-gray-200 space-y-1">
                        <li>Some staff might click on fake emails</li>
                        <li>Training can help everyone spot suspicious emails</li>
                        <li>Never enter passwords on unexpected login pages</li>
                        <li>When in doubt, check with IT before clicking</li>
                    </ul>
                    @elseif($simulation->simulation_type === 'PASSIVE_SNIFFING')
                    <ul class="list-disc ml-5 text-gray-200 space-y-1">
                        <li>Unencrypted data can be seen by others on the same network</li>
                        <li>Always use HTTPS websites for sensitive work</li>
                        <li>Company VPN protects data on all networks</li>
                        <li>Report any suspicious network monitoring to IT</li>
                    </ul>
                    @endif
                    @endif
                    
                </div>
            </div>
        </div>

        <!-- Recommended Actions -->
        <div class="bg-[#102635] border border-green-400/30 rounded-xl p-6 mb-6 ml-16 mr-16">
            <h3 class="text-lg font-semibold text-green-400 mb-2">✅ Recommended Next Steps</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @if($simulation->simulation_type === 'MITM')
                <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                    <div class="text-green-400 font-semibold mb-2">For All Staff</div>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Use VPN on public Wi-Fi</li>
                        <li>• Check for "HTTPS" in browser</li>
                        <li>• Avoid sensitive work on coffee shop Wi-Fi</li>
                    </ul>
                </div>
                <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                    <div class="text-green-400 font-semibold mb-2">For IT Team</div>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Enforce VPN for remote work</li>
                        <li>• Monitor for unusual traffic</li>
                        <li>• Update network security policies</li>
                    </ul>
                </div>
                <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                    <div class="text-green-400 font-semibold mb-2">For Management</div>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Schedule security training</li>
                        <li>• Review remote work policies</li>
                        <li>• Invest in secure networking tools</li>
                    </ul>
                </div>
                @elseif($simulation->simulation_type === 'DDOS')
                <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                    <div class="text-green-400 font-semibold mb-2">Immediate Actions</div>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Review current DDoS protection</li>
                        <li>• Test website under load</li>
                        <li>• Create response plan</li>
                    </ul>
                </div>
                <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                    <div class="text-green-400 font-semibold mb-2">Technical Steps</div>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Implement rate limiting</li>
                        <li>• Set up traffic monitoring</li>
                        <li>• Consider cloud DDoS protection</li>
                    </ul>
                </div>
                <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                    <div class="text-green-400 font-semibold mb-2">Communication Plan</div>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• How to inform customers</li>
                        <li>• Social media updates</li>
                        <li>• Internal notification system</li>
                    </ul>
                </div>
                
                @elseif($simulation->simulation_type === 'PASSIVE_SNIFFING')
            <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                <div class="text-green-400 font-semibold mb-2">For All Staff</div>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li>• Use company VPN on all networks</li>
                    <li>• Only use HTTPS websites for work</li>
                    <li>• Avoid sensitive work on public Wi-Fi</li>
                </ul>
            </div>
            <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                <div class="text-green-400 font-semibold mb-2">For IT Team</div>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li>• Enforce HTTPS for all internal services</li>
                    <li>• Implement network segmentation</li>
                    <li>• Monitor for unusual network activity</li>
                </ul>
            </div>
            <div class="bg-[#0b1d2a]/50 p-4 rounded-lg">
                <div class="text-green-400 font-semibold mb-2">For Management</div>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li>• Invest in network encryption tools</li>
                    <li>• Schedule network security audits</li>
                    <li>• Review remote work security policies</li>
                </ul>
            </div>
            @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center mt-8 ml-16 mr-16">
            <a href="{{ route('simulation.index') }}"
               class="inline-block bg-[#00c3b3] text-black px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                Run Another Simulation
            </a>
            
            <div class="flex gap-3">
                <a href="{{ route('simulations.export.pdf.single', $simulation->id) }}"
                   class="inline-block bg-[#102635] border border-[#00c3b3]/40 text-[#00c3b3] px-4 py-2 rounded-lg font-semibold hover:bg-[#00c3b3]/10 transition">
                    Export PDF Report
                </a>
                <a href="{{ route('simulation.reports') }}"
                   class="inline-block bg-[#102635] border border-gray-600 text-gray-300 px-4 py-2 rounded-lg font-semibold hover:bg-gray-800 transition">
                    View All Reports
                </a>
            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // MITM Traffic Chart
        @if($simulation->simulation_type === 'MITM')
        const mitmCtx = document.getElementById('mitmTrafficChart').getContext('2d');
        new Chart(mitmCtx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                datasets: [{
                    label: 'Data that could be seen',
                    data: [
                        {{ rand(20, 50) }},
                        {{ rand(10, 30) }},
                        {{ rand(40, 80) }},
                        {{ rand(60, 100) }},
                        {{ rand(70, 120) }},
                        {{ rand(50, 90) }}
                    ],
                    borderColor: '#00c3b3',
                    backgroundColor: 'rgba(0, 195, 179, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { 
                        labels: { color: '#ffffff' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount of data',
                            color: '#ffffff'
                        },
                        ticks: { color: '#ffffff' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Time of day',
                            color: '#ffffff'
                        },
                        ticks: { color: '#ffffff' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    }
                }
            }
        });
        @endif

        // DDoS Traffic Chart
        @if($simulation->simulation_type === 'DDOS')
        const ddosCtx = document.getElementById('ddosTrafficChart').getContext('2d');
        new Chart(ddosCtx, {
            type: 'bar',
            data: {
                labels: ['Min 1', 'Min 2', 'Min 3', 'Min 4', 'Min 5', 'Min 6', 'Min 7', 'Min 8', 'Min 9', 'Min 10'],
                datasets: [{
                    label: 'Fake requests hitting system',
                    data: [
                        {{ rand(50, 100) }},
                        {{ rand(100, 200) }},
                        {{ rand(150, 300) }},
                        {{ $simulation->request_rate }},
                        {{ rand($simulation->request_rate - 50, $simulation->request_rate + 50) }},
                        {{ rand($simulation->request_rate - 30, $simulation->request_rate + 30) }},
                        {{ rand($simulation->request_rate - 100, $simulation->request_rate - 20) }},
                        {{ rand(100, 200) }},
                        {{ rand(50, 150) }},
                        {{ rand(20, 80) }}
                    ],
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { 
                        labels: { color: '#ffffff' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Requests per second',
                            color: '#ffffff'
                        },
                        ticks: { color: '#ffffff' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Minutes during attack',
                            color: '#ffffff'
                        },
                        ticks: { color: '#ffffff' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    }
                }
            }
        });
        @endif

        // Phishing Chart
        @if($simulation->simulation_type === 'PHISHING')
        const phishingCtx = document.getElementById('phishingChart').getContext('2d');
        new Chart(phishingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Emails Sent', 'Links Clicked', 'Details Entered'],
                datasets: [{
                    data: [
                        {{ $simulation->emails_sent }},
                        {{ $simulation->clicked_links }},
                        {{ $simulation->entered_details }}
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(250, 204, 21, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(250, 204, 21)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { 
                        position: 'right',
                        labels: { color: '#ffffff', font: { size: 12 } }
                    }
                }
            }
        });
        @endif
        // Passive Sniffing Chart
@if($simulation->simulation_type === 'PASSIVE_SNIFFING')
const sniffingCtx = document.createElement('canvas');
document.querySelector('.bg-[#0b1d2a].p-4.rounded-xl').appendChild(sniffingCtx);

const sniffingChart = new Chart(sniffingCtx, {
    type: 'pie',
    data: {
        labels: ['Encrypted', 'Unencrypted'],
        datasets: [{
            data: [
                {{ $simulation->metadata['encryption_rate'] ?? 65 }},
                {{ 100 - ($simulation->metadata['encryption_rate'] ?? 65) }}
            ],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderColor: [
                'rgb(16, 185, 129)',
                'rgb(239, 68, 68)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { 
                position: 'right',
                labels: { color: '#ffffff', font: { size: 12 } }
            }
        }
    }
});

    </script>
</x-app-layout>