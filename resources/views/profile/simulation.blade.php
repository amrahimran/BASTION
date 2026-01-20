<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Security Training Simulations
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-10 text-white">

        <!-- Page Intro -->
        <div class="max-w-3xl mx-auto mb-10 text-center">
            <h1 class="text-3xl font-bold mb-2">
                Security Awareness Training
            </h1>
            <p class="text-gray-300 text-md">
                Interactive demonstrations showing how common cyber threats operate. 
                These controlled simulations help staff recognize and respond to security risks.
                No actual systems or data are impacted during these exercises.
            </p>
        </div>

        <!-- Simulation List -->
        <div class="max-w-3xl mx-auto space-y-6">

            <!-- MITM Simulation -->
            <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#00c3b3] mb-2">
                    Man-in-the-Middle (MITM) Simulation
                </h2>

                <p class="text-white text-md mb-4">
                    This simulation demonstrates how attackers can intercept communications between a user and a network. 
                    When connected to unsecured or public Wi-Fi networks (like in cafes, airports, or hotels), 
                    sensitive information such as login credentials, emails, and browsing activity can be captured 
                    without the user's knowledge.
                    <br><br>
                    <strong class="text-yellow-300">Business impact:</strong> Employees working remotely or traveling may 
                    inadvertently expose company data when using insecure networks for work-related activities.
                </p>

                <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
                    <strong>Training note:</strong> This is a demonstration only. No actual network traffic is intercepted.
                </div>

                <div class="flex justify-end">
                    <form method="POST" action="{{ route('mitm.run') }}" onsubmit="showProgress()">
                        @csrf
                        <button type="submit"
                            class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                            Run MITM Simulation
                        </button>
                    </form>
                </div>
            </div>

            <!-- DDOS Simulation -->
            <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#00c3b3] mb-2">
                    Distributed Denial-of-Service (DDoS) Simulation
                </h2>

                <p class="text-white text-md mb-4">
                    This simulation illustrates how attackers can overwhelm online services with excessive traffic, 
                    causing them to become slow or completely unavailable. DDoS attacks target the availability of 
                    websites, applications, and internal systems that employees and customers rely on.
                    <br><br>
                    <strong class="text-yellow-300">Business impact:</strong> Service disruptions affect customer access, 
                    employee productivity, and can damage the company's reputation and revenue streams.
                </p>

                <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
                    <strong>Training note:</strong> No actual traffic is generated against real systems.
                </div>

                <form method="POST" action="{{ route('ddos.run') }}" class="space-y-4" onsubmit="showProgress()">
                    @csrf

                    <div>
                        <label class="block text-xs mb-1">Attack Intensity</label>
                        <select name="mode"
                            class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                            <option value="Low">Low – Simulates minor service degradation</option>
                            <option value="Medium">Medium – Simulates significant performance impact</option>
                            <option value="High">High – Simulates complete service unavailability</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs mb-1">Target Environment</label>
                        <select name="target"
                            class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                            <option value="Public Website">Public Website</option>
                            <option value="Internal Application">Internal Application</option>
                            <option value="Customer Portal">Customer Portal</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                            Run DDoS Simulation
                        </button>
                    </div>
                </form>
            </div>


            <!-- PASSIVE SNIFFING -->
            <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#00c3b3] mb-2">
                    Passive Network Sniffing Simulation
                </h2>

                <p class="text-white text-md mb-4">
                    This simulation demonstrates how unencrypted network communications can be monitored by 
                    unauthorized parties. When data travels across networks without proper encryption, 
                    sensitive information including login credentials, emails, and file transfers may be visible 
                    to anyone with access to the network.
                    <br><br>
                    <strong class="text-yellow-300">Business impact:</strong> Internal communications and data transfers 
                    could be exposed, potentially leading to data breaches and compliance violations.
                </p>

                <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
                    <strong>Training note:</strong> This is an awareness demonstration only. No actual network monitoring occurs.
                </div>

                <form method="POST" action="{{ route('sniffing.run') }}" onsubmit="showProgress()">
                    @csrf
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                            Run Sniffing Simulation
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- IMPORTANT NOTE -->
        <div class="max-w-3xl mx-auto mt-10 p-4 bg-green-900/30 border border-green-500/50 rounded-xl">
            <div class="flex items-start gap-3">
                <div class="bg-green-500/20 p-2 rounded-lg mt-1">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-green-300 mb-1">Training Environment Safety</h3>
                    <p class="text-sm text-gray-300">
                        These simulations operate within a completely isolated training environment. 
                        No actual systems are targeted, no real data is accessed, and no production 
                        networks are impacted. The purpose is purely educational to enhance organizational 
                        security awareness.
                    </p>
                </div>
            </div>
        </div>

        <!-- PROGRESS OVERLAY -->
        <div id="progressOverlay"
             class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
            <div class="bg-[#102635] p-6 rounded-xl w-full max-w-md text-center border border-[#00c3b3]/40">
                <h3 class="text-[#00c3b3] font-semibold mb-3">
                    Initializing Simulation
                </h3>

                <div class="w-full bg-[#0b1d2a] rounded-full h-3 overflow-hidden">
                    <div id="progressBar"
                         class="h-full w-full animate-pulse bg-gradient-to-r from-cyan-400 to-green-400">
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Preparing training environment...
                </p>
            </div>
        </div>

        <script>
            function showProgress() {
                document.getElementById('progressOverlay').classList.remove('hidden');
                document.getElementById('progressOverlay').classList.add('flex');
            }
        </script>

    </div>
</x-app-layout>