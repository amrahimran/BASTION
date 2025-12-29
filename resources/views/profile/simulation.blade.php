<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Simulations
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-10 text-white">

        <!-- Page Intro -->
        <div class="max-w-3xl mx-auto mb-10 text-center">
            <h1 class="text-3xl font-bold mb-2">
                Attack Simulation Dashboard
            </h1>
            <p class="text-gray-400 text-md">
                Run controlled cybersecurity simulations for awareness, training, and demonstration purposes.
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
        This simulation demonstrates a simulated ARP-based Man-in-the-Middle attack, where an attacker positions themselves between a user and the network.

    It helps non-technical staff understand how unsecured networks (such as public Wi-Fi) can expose sensitive data without the user noticing.


    </p>

    <!-- Disclaimer -->
    <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
        This is a controlled simulation. No real network traffic or credentials are intercepted.
    </div>

    <!-- Status + Run -->
    <div class="flex justify-end items-center mb-4">
        

        <form method="POST" action="{{ route('mitm.run') }}">
            @csrf
            <button
                type="submit"
                class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                Run Simulation
            </button>
        </form>
    </div>


</div>


            <!-- DDoS Simulation -->
<div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">

    <h2 class="text-xl font-semibold text-[#00c3b3] mb-2">
        Distributed Denial-of-Service (DDoS) Simulation
    </h2>

    <p class="text-white text-md mb-4">
        This simulation demonstrates how overwhelming traffic can make systems slow or unavailable.
        It helps staff understand why system downtime happens and why protection mechanisms are critical.
    </p>

    <!-- Disclaimer -->
    <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
        This is a safe simulation. No real traffic is sent and no systems are harmed.
    </div>

    <!-- DDoS Form -->
    
    <form method="POST" action="{{ route('ddos.run') }}"  class="space-y-4">

        @csrf

        <!-- Attack Mode -->
        <div>
            <label class="block text-xs text-white mb-1">
                Attack Strength
            </label>
            <select name="mode"
                class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                <option value="Low">Low (Slow disruption)</option>
                <option value="Medium">Medium (Noticeable slowdown)</option>
                <option value="High">High (Service outage)</option>
            </select>
        </div>

        <!-- Target -->
        <div>
            <label class="block text-xs text-white mb-1">
                Target System
            </label>
            <select name="target"
                class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                <option value="Public Website">Public Website</option>
                <option value="Internal Application">Internal Application</option>
                <option value="Customer Portal">Customer Portal</option>
            </select>
        </div>

        <!-- Safety Note -->
        <p class="text-xs text-gray-400">
            Safety limit enforced: Request rates are capped to prevent overload.
        </p>

        <!-- Run Button -->
        <div class="flex justify-end">
            <button
                type="submit"
                class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                Run DDoS Simulation
            </button>
        </div>
    </form>

</div>


          
            <!-- Phishing Simulation -->
<div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">

    <h2 class="text-xl font-semibold text-[#00c3b3] mb-2">
        Phishing Awareness Simulation
    </h2>

    <p class="text-white text-md mb-4">
        This simulation demonstrates how fake emails trick users into clicking links
        or entering login details. It helps staff recognize warning signs before damage occurs.
    </p>

    <!-- Disclaimer -->
    <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
        This is a safe awareness simulation. No real emails are sent and no real credentials are stored.
    </div>

    <!-- Phishing Form -->
    <form method="POST" action="{{ route('phishing.run') }}" class="space-y-4">
        @csrf

        <!-- Phishing Theme -->
        <div>
            <label class="block text-xs text-white mb-1">
                Phishing Email Theme
            </label>
            <select name="theme"
                class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                <option value="Password Reset">Password Reset</option>
                <option value="Invoice Alert">Invoice / Payment Alert</option>
                <option value="HR Notice">HR Policy Update</option>
            </select>
        </div>

        <!-- Target Group -->
        <div>
            <label class="block text-xs text-white mb-1">
                Target Audience
            </label>
            <select name="target"
                class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                <option value="Employees">Employees</option>
                <option value="Finance Team">Finance Team</option>
                <option value="IT Staff">IT Staff</option>
            </select>
        </div>

        <p class="text-xs text-gray-400">
            Clicks and fake login attempts are simulated for awareness tracking.
        </p>

        <div class="flex justify-end">
            <button
                type="submit"
                class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                Run Phishing Simulation
            </button>
        </div>
    </form>

</div>

</x-app-layout>
