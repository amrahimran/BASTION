<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('System Vulnerability Scan') }}
        </h2>
    </x-slot>

<div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">

    <!-- Title -->
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-extrabold text-white">
            Run a <span class="text-[#00c3b3]">Security Scan</span>
        </h1>
        <p class="text-gray-400 mt-3">
            Select security checks and scan your system or network.
        </p>
    </div>

    <div class="max-w-4xl mx-auto bg-[#102635] border border-[#00c3b3]/20 rounded-xl shadow-lg p-8">

        <form method="POST" action="{{ route('scan.run') }}">
            @csrf

            <!-- Target -->
            <label class="block text-sm font-semibold text-gray-300 mb-2">Target IP or Domain</label>
            <input type="text" name="target"
                placeholder="e.g. 192.168.8.1"
                class="w-full px-4 py-3 rounded-lg bg-[#0b1d2a] border border-gray-600 text-gray-200">

            <!-- Auto Detect -->
            <div class="flex items-center mt-4">
                <input checked type="checkbox" name="auto_detect" class="mr-2">
                <label class="text-gray-300 text-sm">Auto-detect LAN devices</label>
            </div>

            <!-- Scan Mode -->
            <div class="mt-4">
                <label class="text-gray-300 text-sm font-semibold mb-2 block">Scan Mode</label>
                <select name="scan_mode"
                    class="w-full px-4 py-3 rounded-lg bg-[#0b1d2a] border border-gray-600 text-gray-200">
                    <option value="fast" selected>Fast Scan (Top 100 ports)</option>
                    <option value="deep">Deep Scan (Full + Version + OS)</option>
                </select>
            </div>

            <!-- Feature List -->
            <h3 class="text-xl mt-8 mb-4 font-bold text-[#00c3b3]">Scan Features</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                @foreach([
                    'nmap_host_discovery' => [
                        'label' => 'Nmap Host Discovery',
                        'desc'  => 'Detects which devices are alive on the network.'
                    ],
                    'nmap_basic_port_scan' => [
                        'label' => 'Basic Port Scan',
                        'desc'  => 'Scans common open ports that attackers target.'
                    ],
                    'os_fingerprinting' => [
                        'label' => 'OS Fingerprinting',
                        'desc'  => 'Tries to identify the operating system version.'
                    ],
                    'banner_grabbing' => [
                        'label' => 'Banner Grabbing',
                        'desc'  => 'Collects service banners that may reveal versions.'
                    ],
                    'ssh_weak_config' => [
                        'label' => 'Weak SSH Configuration Check',
                        'desc'  => 'Detects weak algorithms or outdated SSH settings.'
                    ],
                    'ftp_anonymous' => [
                        'label' => 'FTP Anonymous Login',
                        'desc'  => 'Checks if FTP allows login without credentials.'
                    ],
                    'smb_share_scan' => [
                        'label' => 'SMB Share Scan',
                        'desc'  => 'Finds open Windows shares that expose files.'
                    ],
                    'http_headers' => [
                        'label' => 'HTTP Security Headers',
                        'desc'  => 'Checks if a website is missing security headers.'
                    ],
                    'snmp_scan' => [
                        'label' => 'SNMP v1/v2 Public Scan',
                        'desc'  => 'Searches for devices with default public SNMP.'
                    ],
                    'nmap_nse' => [
                        'label' => 'Nmap NSE Scripts',
                        'desc'  => 'Runs vulnerability scripts for deeper checks.'
                    ],
                    'nikto' => [
                        'label' => 'Nikto Web Scan (Simulated)',
                        'desc'  => 'Detects common web server misconfigurations.'
                    ],
                    'ssl_tls' => [
                        'label' => 'SSL/TLS Scan',
                        'desc'  => 'Checks SSL versions, ciphers, and weaknesses.'
                    ],
                    'os_patch' => [
                        'label' => 'OS Patch Check',
                        'desc'  => 'Simulates outdated OS or missing patch issues.'
                    ],
                    'docker' => [
                        'label' => 'Docker Misconfigurations',
                        'desc'  => 'Checks for exposed Docker sockets/weak setups.'
                    ],
                    'firewall' => [
                        'label' => 'Firewall Status',
                        'desc'  => 'Tests whether firewall rules are active.'
                    ],
                    'passive_sniff' => [
                        'label' => 'Passive Network Sniffing',
                        'desc'  => 'Detects unencrypted or exposed network traffic.'
                    ],
                    'dns_misconfig' => [
                        'label' => 'DNS Misconfiguration Check',
                        'desc'  => 'Finds common DNS issues like open resolvers.'
                    ],
                ] as $key => $item)

                <label class="flex items-start bg-[#0b1d2a] border border-gray-600 p-3 rounded-lg">
                    <input checked type="checkbox" name="features[]" value="{{ $key }}" class="mr-3 mt-1">
                    <span class="text-gray-300 text-sm">
                        <span class="font-semibold">{{ $item['label'] }}</span>
                        <br>
                        <span class="text-gray-500 text-xs">{{ $item['desc'] }}</span>
                    </span>
                </label>

                @endforeach
            </div>

            <!-- Start Scan -->
            <button type="submit"
                class="w-full mt-6 bg-[#00c3b3] text-black font-semibold px-6 py-3 rounded-lg hover:bg-[#00a79e]">
                Start Scan
            </button>

        </form>
    </div>

</div>

</x-app-layout>
