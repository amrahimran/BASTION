<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('System Vulnerability Scan') }}
        </h2>
    </x-slot>

     @if(session('error'))
        <div class="bg-red-600 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">

        <!-- Title -->
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h1 class="text-4xl font-extrabold text-white">
                Run a <span class="text-[#00c3b3]">Security Scan</span>
            </h1>
            <p class="text-gray-400 mt-3">
                Auto-detect all devices on your LAN and run security checks.
            </p>
        </div>

        <div class="max-w-4xl mx-auto bg-[#102635] border border-[#00c3b3]/20 rounded-xl shadow-lg p-8">

            <form method="POST" action="{{ route('scan.run') }}">
                @csrf

                <!-- Auto Detect Only -->
                <div class="flex items-center mb-4">
                    <input checked type="checkbox" name="auto_detect" class="mr-2" readonly>
                    <label class="text-gray-300 text-sm">Auto-detect LAN devices (mandatory)</label>
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
                    @foreach($featuresList as $key => $item)
                        <div x-data="{ open: false }"
                            class="bg-[#0b1d2a] border border-gray-600 p-4 rounded-lg">

                            <label class="flex items-start">
                                <input type="checkbox" name="features[]" value="{{ $key }}" class="mr-3 mt-1">

                                <div class="w-full">
                                    <!-- Feature Title -->
                                    <span class="text-[#00c3b3] text-base font-bold">
                                        {{ $item['label'] }}
                                    </span>

                                    <!-- Short One-Line Description -->
                                    <p class="text-white font-semibold text-[13px] mt-1">
                                        {{ $item['short'] }}
                                    </p>

                                    <!-- Toggle Button -->
                                    <button type="button"
                                        @click="open = !open"
                                        class="text-[#00c3b3] text-xs mt-2 flex items-center">
                                        <span x-show="!open">Show more ▼</span>
                                        <span x-show="open">Show less ▲</span>
                                    </button>

                                    <!-- Expanded Full Description -->
                                    <div x-show="open"
                                        x-transition
                                        class="text-white text-medium mt-2 border-t border-gray-700 pt-2">
                                        {{ $item['long'] }}
                                    </div>
                                </div>
                            </label>
                        </div>
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
