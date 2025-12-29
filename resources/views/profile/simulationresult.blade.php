<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Simulation Result
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-10 text-white max-w-3xl mx-auto">

        <!-- Summary -->
        <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-[#00c3b3] mb-2">
                Simulation Completed
            </h2>

            @if($simulation->simulation_type === 'MITM')
                <p class="text-gray-300 text-sm">
                    This result demonstrates how a Man-in-the-Middle attack can impact users on unsecured networks.
                </p>
            @elseif($simulation->simulation_type === 'DDOS')
                <p class="text-gray-300 text-sm">
                    This result demonstrates a DDoS attack simulation, showing how overwhelming traffic affects system availability.
                </p>
            @endif
        </div>

        <!-- Metrics -->
        <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 mb-6">

            @if($simulation->simulation_type === 'MITM')
                <h3 class="text-lg font-semibold mb-4 text-[#00c3b3]">
                    Observed Indicators
                </h3>

                <ul class="space-y-2 text-gray-300 text-sm">
                    <li>📡 Intercepted Data Messages: <strong>{{ $simulation->intercepted_packets }}</strong></li>
                    <li>🔑 Credentials Potentially Exposed: <strong>{{ $simulation->exposed_credentials }}</strong></li>
                </ul>
            @elseif($simulation->simulation_type === 'DDOS')
                <h3 class="text-lg font-semibold mb-4 text-[#00c3b3]">
                    Traffic Indicators
                </h3>

                <ul class="space-y-2 text-gray-300 text-sm">
                    <li>🎯 Target System: <strong>{{ $simulation->target }}</strong></li>
                    <li>⚡ Attack Strength: <strong>{{ $simulation->ddos_mode }}</strong></li>
                    <li>📊 Requests / Second: <strong>{{ $simulation->request_rate }}</strong></li>
                    <li>📦 Total Requests: <strong>{{ $simulation->total_requests }}</strong></li>
                </ul>
            @endif

            <!-- Risk Badge -->
            <div class="mt-4">
                <span class="inline-block px-4 py-1 rounded-full text-xs font-semibold
                    {{ $simulation->risk_level === 'High'
                        ? 'bg-red-500/20 text-red-400'
                        : ($simulation->risk_level === 'Medium' ? 'bg-yellow-500/20 text-yellow-300' : 'bg-green-500/20 text-green-300') }}">
                    Risk Level: {{ $simulation->risk_level }}
                </span>
            </div>
        </div>

        <!-- PHISHING Section -->
        @if($simulation->simulation_type === 'PHISHING')
            <div class="bg-[#102635] border border-pink-400/40 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-pink-300 mb-4">
                    Phishing Simulation Summary
                </h3>

                <div class="grid grid-cols-2 gap-4 text-sm text-gray-300 mb-6">
                    <div>
                        Emails Sent
                        <div class="text-xl font-bold text-white">
                            {{ $simulation->emails_sent }}
                        </div>
                    </div>

                    <div>
                        Links Clicked
                        <div class="text-xl font-bold text-yellow-300">
                            {{ $simulation->clicked_links }}
                        </div>
                    </div>

                    <div>
                        Details Entered
                        <div class="text-xl font-bold text-red-400">
                            {{ $simulation->entered_details }}
                        </div>
                    </div>

                    <div>
                        Risk Level
                        <div class="text-xl font-bold
                            {{ $simulation->risk_level === 'High' ? 'text-red-400' : 'text-yellow-300' }}">
                            {{ $simulation->risk_level }}
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-[#0b1d2a] p-4 rounded-xl">
                    <canvas id="phishingChart"></canvas>
                </div>
            </div>
        @endif

        <!-- AI Explanation -->
        <div class="bg-[#0b1d2a] border border-blue-400 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-300 mb-4">
                 Security Explanation
            </h3>

            <div class="text-gray-100 text-sm leading-relaxed space-y-4
                {{ $simulation->simulation_type === 'PHISHING' ? 'whitespace-pre-line' : '' }}">
                {!! $simulation->ai_explanation ?? '<span class="text-gray-400">AI explanation not available.</span>' !!}
            </div>
        </div>

        <!-- Takeaway -->
        @if($simulation->simulation_type === 'MITM')
            <div class="bg-[#102635] border border-green-400/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-green-400 mb-2">
                    What Staff Should Learn
                </h3>

                <ul class="list-disc ml-5 text-sm text-gray-300 space-y-1">
                    <li>Public Wi-Fi can expose sensitive data</li>
                    <li>Users may not notice when interception happens</li>
                    <li>HTTPS, VPNs, and trusted networks reduce this risk</li>
                </ul>
            </div>
        @elseif($simulation->simulation_type === 'DDOS')
            <div class="bg-[#102635] border border-green-400/30 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-green-400 mb-2">
                    Awareness Takeaways
                </h3>

                <ul class="list-disc ml-5 text-sm text-gray-300 space-y-1">
                    <li>High traffic can make services slow or unavailable</li>
                    <li>Understanding traffic patterns helps prevent downtime</li>
                    <li>Protection mechanisms (firewalls, rate limiting) mitigate risk</li>
                </ul>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('simulation.index') }}"
               class="inline-block bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold">
                Back to Simulations
            </a>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if($simulation->simulation_type === 'PHISHING')
        const ctx = document.getElementById('phishingChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Emails Sent', 'Links Clicked', 'Details Entered'],
                datasets: [{
                    label: 'Phishing Metrics',
                    data: [
                        {{ $simulation->emails_sent }},
                        {{ $simulation->clicked_links }},
                        {{ $simulation->entered_details }}
                    ],
                    backgroundColor: [
                        '#ffffff',
                        '#facc15',
                        '#f87171'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#ffffff' }
                    },
                    x: {
                        ticks: { color: '#ffffff' }
                    }
                }
            }
        });
        @endif
    </script>

</x-app-layout>
