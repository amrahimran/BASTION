<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('System Vulnerability Scan') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">

        <!-- Title Section -->
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h1 class="text-4xl font-extrabold text-white">
                Run a <span class="text-[#00c3b3]">Security Scan</span>
            </h1>
            <p class="text-gray-400 mt-3">
                Enter an IP address or domain to analyze open ports, services, and potential vulnerabilities.
            </p>
        </div>

        <!-- Scan Form Card -->
        <div class="max-w-3xl mx-auto bg-[#102635] border border-[#00c3b3]/20 rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('scan.run') }}">
                @csrf

                <!-- Target Input -->
                <label class="block text-sm font-semibold text-gray-300 mb-2">
                    Target IP or Domain
                </label>
                <input 
                    type="text" 
                    name="target" 
                    placeholder="e.g. 192.168.8.1 or example.com"
                    class="w-full px-4 py-3 rounded-lg bg-[#0b1d2a] border border-gray-600 text-gray-200 placeholder-gray-500 focus:outline-none focus:border-[#00c3b3]"
                >

                <!-- Auto-detect -->
                <div class="flex items-center mt-4">
                    <input type="checkbox" name="auto_detect" id="auto_detect" class="mr-2">
                    <label for="auto_detect" class="text-gray-300 text-sm">Auto-detect LAN (scan all devices in network)</label>
                </div>

                <!-- Scan Mode -->
                <div class="mt-4">
                    <label class="text-gray-300 text-sm font-semibold mb-2 block">Scan Mode</label>
                    <select name="scan_mode" class="w-full px-4 py-3 rounded-lg bg-[#0b1d2a] border border-gray-600 text-gray-200 focus:outline-none focus:border-[#00c3b3]">
                        <option value="fast" selected>Fast Scan (Top 100 ports)</option>
                        <option value="deep">Deep Scan (All ports + service detection)</option>
                    </select>
                </div>

                <!-- Start Button -->
                <button 
                    type="submit"
                    class="w-full mt-6 bg-[#00c3b3] text-black font-semibold px-6 py-3 rounded-lg hover:bg-[#00a79e] transition transform hover:-translate-y-1"
                >
                    Start Scan
                </button>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-16">
            <div class="bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-6 text-center shadow-lg hover:border-[#00c3b3]/40 transition">
                <h3 class="text-lg font-semibold text-[#00c3b3] mb-3">Port Analysis</h3>
                <p class="text-gray-400 text-sm">Detect open ports & exposed entry points attackers may use.</p>
            </div>
            <div class="bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-6 text-center shadow-lg hover:border-[#00c3b3]/40 transition">
                <h3 class="text-lg font-semibold text-[#00c3b3] mb-3">Service Detection</h3>
                <p class="text-gray-400 text-sm">Identify running services & outdated versions.</p>
            </div>
            <div class="bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-6 text-center shadow-lg hover:border-[#00c3b3]/40 transition">
                <h3 class="text-lg font-semibold text-[#00c3b3] mb-3">Vulnerability Check</h3>
                <p class="text-gray-400 text-sm">Run Nmap scripts to detect known weaknesses.</p>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="text-center mt-20">
            <p class="text-gray-400 mb-4 text-sm">Ethical, safe, and private security evaluations</p>
            <a href="/dashboard"
                class="border border-[#00c3b3] text-[#00c3b3] px-8 py-3 rounded-lg hover:bg-[#00c3b3] hover:text-black transition">
                Back to Dashboard
            </a>
        </div>

    </div>
</x-app-layout>
