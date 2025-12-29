<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            MITM Simulation Result
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] text-white px-6 py-12">

        <div class="max-w-4xl mx-auto bg-[#102635] border border-[#00c3b3]/30
                    rounded-xl p-6 shadow-lg">

            <h3 class="text-xl font-bold text-[#00c3b3] mb-4">
                Simulation Summary
            </h3>

            <p class="text-gray-300 mb-2">
                <strong>Type:</strong> MITM Attack
            </p>

            <p class="text-gray-300 mb-4">
                <strong>Run At:</strong> {{ $simulation->created_at }}
            </p>

            <h4 class="text-lg font-semibold text-white mb-2">
                Results
            </h4>

            <div class="bg-black/40 border border-gray-700 rounded-lg p-4
                        text-gray-200 whitespace-pre-wrap text-sm">
                {{ json_encode($results, JSON_PRETTY_PRINT) }}
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('simulation.dashboard') }}"
                   class="border border-[#00c3b3] text-[#00c3b3]
                          px-6 py-2 rounded hover:bg-[#00c3b3]
                          hover:text-black transition">
                    Back to Simulations
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
