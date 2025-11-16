<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">
    <!-- Hero Section -->
<section class="flex flex-col md:flex-row items-center justify-between max-w-6xl mx-auto px-6 py-7 mb-2">
    <!-- Left Content -->
    <div class="md:w-1/2 text-left">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-white">
            Test Your Defenses <span class="text-[#00c3b3]">Before Hackers Do.</span>
        </h1>
        <p class="text-gray-300 mb-8">
            AI-powered attack simulations & vulnerability analysis for businesses and individuals.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('scan') }}" 
                class="bg-[#00c3b3] text-black font-semibold px-6 py-3 rounded-lg hover:bg-[#00a79e] transition">
                    Run a Free Scan
            </a>
            <a href="#" class="border border-[#00c3b3] text-[#00c3b3] font-semibold px-6 py-3 rounded-lg hover:bg-[#00c3b3] hover:text-black transition">
                Request a Demo
            </a>
        </div>

        <div class="mt-8">
            <a href="#" class="bg-[#00c3b3] text-black font-semibold px-8 py-3 rounded-lg hover:bg-[#00a79e] transition">
                Start Free Simulation
            </a>
        </div>
    </div>

    <!-- Right Image -->
    <div class="md:w-1/2 mt-10 md:mt-0 flex justify-center">
        <img src="{{ asset('images/hero-img.png') }}" alt="Cyber Security Illustration" class="w-half max-w-md rounded-lg shadow-sm">
    </div>
</section>



    <!-- Features Section -->
    <section class="max-w-6xl mx-auto mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
        <div>
            <h3 class="text-lg font-semibold text-[#00c3b3] mb-2">Simulated Cyber Attacks</h3>
            <p class="text-gray-400 text-sm">Phishing, malware & penetration test scenarios.</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-[#00c3b3] mb-2">Vulnerability Scanning</h3>
            <p class="text-gray-400 text-sm">Spot hidden weaknesses in systems & apps.</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-[#00c3b3] mb-2">Real-Time Dashboard</h3>
            <p class="text-gray-400 text-sm">Visualize risks with charts & reports.</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-[#00c3b3] mb-2">Employee Training</h3>
            <p class="text-gray-400 text-sm">Teach teams through safe simulations.</p>
        </div>
    </section>

            <div class="max-w-7xl w-full py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Card 1 -->
                    <a href="/scan" 
                    class="group bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-8 shadow-lg hover:shadow-[#00c3b3]/40 transition transform hover:-translate-y-2 hover:border-[#00c3b3]/50 duration-300 ease-out">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00c3b3] group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6 1A9 9 0 1 1 3 12a9 9 0 0 1 18 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Run System Scan</h3>
                            <p class="text-gray-400 text-sm">Analyze threats and security risks in real-time.</p>
                        </div>
                    </a>

                    <!-- Card 2 -->
                    <a href="/reports" 
                    class="group bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-8 shadow-lg hover:shadow-[#00c3b3]/40 transition transform hover:-translate-y-2 hover:border-[#00c3b3]/50 duration-300 ease-out">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00c3b3] group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2m-6 0h6m-7-8h8m-8 4h8M5 12V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v5" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Reports</h3>
                            <p class="text-gray-400 text-sm">View system logs, threat reports, and analytics.</p>
                        </div>
                    </a>

                    <!-- Card 3 -->
                    <a href="/alerts" 
                    class="group bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-8 shadow-lg hover:shadow-[#00c3b3]/40 transition transform hover:-translate-y-2 hover:border-[#00c3b3]/50 duration-300 ease-out">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00c3b3] group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v4m0 4h.01m-6.938 4h13.856A2.062 2.062 0 0 0 21 18.938V5.062A2.062 2.062 0 0 0 18.938 3H5.062A2.062 2.062 0 0 0 3 5.062v13.876A2.062 2.062 0 0 0 5.062 21z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Security Alerts</h3>
                            <p class="text-gray-400 text-sm">Stay updated on recent vulnerabilities and notifications.</p>
                        </div>
                    </a>

                    <!-- Card 4 -->
                    <a href="/settings" 
                    class="group bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-8 shadow-lg hover:shadow-[#00c3b3]/40 transition transform hover:-translate-y-2 hover:border-[#00c3b3]/50 duration-300 ease-out">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00c3b3] group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317a1 1 0 0 1 1.35-.937l2.262.755a1 1 0 0 1 .63.63l.755 2.262a1 1 0 0 1-.937 1.35 8.001 8.001 0 0 0 0 8.566 1 1 0 0 1 .937 1.35l-.755 2.262a1 1 0 0 1-.63.63l-2.262.755a1 1 0 0 1-1.35-.937 8.001 8.001 0 0 0-8.566 0 1 1 0 0 1-1.35-.937l-.755-2.262a1 1 0 0 1 .63-.63l2.262-.755a1 1 0 0 1 .937-1.35 8.001 8.001 0 0 0 0-8.566z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Settings</h3>
                            <p class="text-gray-400 text-sm">Manage your preferences and configuration.</p>
                        </div>
                    </a>

                    <!-- Card 5 -->
                    <a href="/account" 
                    class="group bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-8 shadow-lg hover:shadow-[#00c3b3]/40 transition transform hover:-translate-y-2 hover:border-[#00c3b3]/50 duration-300 ease-out">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00c3b3] group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A11.955 11.955 0 0 1 12 15c2.477 0 4.774.755 6.879 2.088M15 11a3 3 0 1 0-6 0 3 3 0 0 0 6 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">My Account</h3>
                            <p class="text-gray-400 text-sm">Update your profile, password, and personal details.</p>
                        </div>
                    </a>

                    <!-- Card 6 -->
                    <a href="/support" 
                    class="group bg-[#0b1d2a] border border-[#00c3b3]/20 rounded-xl p-8 shadow-lg hover:shadow-[#00c3b3]/40 transition transform hover:-translate-y-2 hover:border-[#00c3b3]/50 duration-300 ease-out">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#00c3b3] group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8a6 6 0 1 1-9.33 5H8a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2a6 6 0 0 1 8 3z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Support</h3>
                            <p class="text-gray-400 text-sm">Get help, contact the team, or access documentation.</p>
                        </div>
                    </a>
                </div>
            </div>
      
    <!-- Insight Section -->
    <section class="max-w-6xl mx-auto mt-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-2xl font-bold mb-4">Get instant visibility into your security posture</h2>
            <ul class="space-y-3 text-gray-300">
                <li>✅ AI-powered simulations</li>
                <li>✅ Beginner-friendly dashboard</li>
                <li>✅ Safe & ethical testing</li>
                <li>✅ Enterprise-grade reporting</li>
            </ul>
        </div>
        <div class="bg-[#102635] rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm text-gray-400 mb-2">Threats Detected</h3>
            <p class="text-4xl font-extrabold text-[#00c3b3]">32</p>
            <p class="text-sm text-gray-400 mb-4">Previous period</p>
            <div class="space-y-2">
                <div>
                    <p class="text-xs text-gray-400">High Risk</p>
                    <div class="bg-gray-700 h-2 rounded-full">
                        <div class="bg-red-500 h-2 rounded-full w-3/4"></div>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Medium Risk</p>
                    <div class="bg-gray-700 h-2 rounded-full">
                        <div class="bg-yellow-400 h-2 rounded-full w-1/2"></div>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Low Risk</p>
                    <div class="bg-gray-700 h-2 rounded-full">
                        <div class="bg-green-500 h-2 rounded-full w-1/3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="text-center mt-20">
        <h2 class="text-2xl font-bold mb-8">Ready to Protect Your Business?</h2>
        <a href="#" class="bg-[#00c3b3] text-black font-semibold px-8 py-3 rounded-lg hover:bg-[#00a79e] transition">
            Start Free Simulation Now
        </a>
        <p class="text-gray-400 mt-8 text-sm">No credit card required</p>
    </section>
</div>



    </div>
    

```


</x-app-layout>
