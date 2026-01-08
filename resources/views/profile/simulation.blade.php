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
                This page allows staff members to safely experience how common cyber attacks work.
                These simulations are designed for awareness and training, not for technical use.
                No real systems, data, or users are affected at any point.
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
                    A Man-in-the-Middle (MITM) attack happens when an attacker secretly places themselves
                    between a user and the network they are connected to. The user believes they are
                    communicating normally, but in reality, someone else may be watching or interfering.
                    <br><br>
                    This simulation helps staff understand how using unsecured or public networks
                    (such as café or airport Wi-Fi) can expose sensitive information without any obvious warning signs.
                </p>

                <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
                    This is a controlled simulation for learning purposes only.
                    No real network traffic, passwords, or personal data are intercepted.
                </div>

                <div class="flex justify-end">
                    <form method="POST" action="{{ route('mitm.run') }}" onsubmit="showProgress()">
                        @csrf
                        <button type="submit"
                            class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                            Run Simulation
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
                    A Distributed Denial-of-Service (DDoS) attack works by overwhelming a system
                    with more requests than it can handle. As a result, the system becomes slow,
                    unresponsive, or completely unavailable to normal users.
                    <br><br>
                    This simulation helps non-technical staff understand why websites or internal systems
                    sometimes crash or go offline, and how attackers exploit system limits to cause disruption.
                </p>

                <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
                    This is a safe demonstration. No real traffic is generated and no systems are harmed.
                </div>

                <form method="POST" action="{{ route('ddos.run') }}" class="space-y-4" onsubmit="showProgress()">
                    @csrf

                    <div>
                        <label class="block text-xs mb-1">
                            Attack Strength
                        </label>
                        <select name="mode"
                            class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                            <option value="Low">
                                Low – Represents a small increase in traffic that may slightly slow the system
                            </option>
                            <option value="Medium">
                                Medium – Represents heavy usage that causes noticeable delays and timeouts
                            </option>
                            <option value="High">
                                High – Represents extreme overload where the system becomes unavailable
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs mb-1">
                            Target System
                        </label>
                        <select name="target"
                            class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                            <option value="Public Website">
                                Public Website – A company website accessed by anyone on the internet
                            </option>
                            <option value="Internal Application">
                                Internal Application – Systems used by employees within the organization
                            </option>
                            <option value="Customer Portal">
                                Customer Portal – Platforms used by customers to log in or place requests
                            </option>
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

            <!-- PHISHING -->
            <div class="bg-[#102635] border border-[#00c3b3]/30 rounded-xl p-6 shadow-lg">

                <h2 class="text-xl font-semibold text-[#00c3b3] mb-2">
                    Phishing Awareness Simulation
                </h2>

                <p class="text-white text-md mb-4">
                    Phishing attacks use fake but convincing messages to trick people into
                    clicking malicious links or sharing confidential information such as passwords.
                    These messages often look like they come from trusted sources.
                    <br><br>
                    This simulation helps staff learn how phishing attempts are structured
                    and why even careful users can sometimes be fooled.
                </p>

                <div class="bg-[#0b1d2a] border border-yellow-400/30 text-yellow-300 text-xs p-3 rounded mb-4">
                    This is an awareness simulation only. No real emails or messages are sent.
                </div>

                <form method="POST" action="{{ route('phishing.run') }}" class="space-y-4" onsubmit="showProgress()">
                    @csrf

                    <div>
                        <label class="block text-xs mb-1">
                            Email Theme
                        </label>
                        <select name="theme"
                            class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                            <option value="Password Reset">
                                Password Reset – Tries to create urgency by claiming your password must be changed
                            </option>
                            <option value="Invoice Alert">
                                Invoice Alert – Pretends to be a payment or billing-related message
                            </option>
                            <option value="HR Notice">
                                HR Notice – Appears to come from the human resources department
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs mb-1">
                            Target Audience
                        </label>
                        <select name="target"
                            class="w-full bg-[#0b1d2a] border border-gray-600 rounded px-3 py-2 text-sm text-white">
                            <option value="Employees">
                                Employees – General staff members across the organization
                            </option>
                            <option value="Finance Team">
                                Finance Team – Staff handling payments and financial data
                            </option>
                            <option value="IT Staff">
                                IT Staff – Technical teams with system access
                            </option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-[#00c3b3] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                            Run Phishing Simulation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PROGRESS OVERLAY -->
    <div id="progressOverlay"
         class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
        <div class="bg-[#102635] p-6 rounded-xl w-full max-w-md text-center border border-[#00c3b3]/40">
            <h3 class="text-[#00c3b3] font-semibold mb-3">
                Simulation Running
            </h3>

            <div class="w-full bg-[#0b1d2a] rounded-full h-3 overflow-hidden">
                <div id="progressBar"
                     class="h-full w-full animate-pulse bg-gradient-to-r from-cyan-400 to-green-400">
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-3">
                Demonstrating the attack behavior in a safe and controlled environment…
            </p>
        </div>
    </div>

    <script>
        function showProgress() {
            document.getElementById('progressOverlay').classList.remove('hidden');
            document.getElementById('progressOverlay').classList.add('flex');
        }
    </script>

</x-app-layout>
