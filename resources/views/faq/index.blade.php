<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Frequently Asked Questions') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-10">

        <div class="max-w-4xl mx-auto">

            <h1 class="text-3xl font-bold text-[#00c3b3] mb-6">
                Help & Security Guidance
            </h1>

            @auth
                @if(auth()->user()->isAdmin())
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.faq.index') }}"
                        class="bg-[#00c3b3] text-black px-3 py-1 rounded hover:opacity-90 transition">
                            Manage FAQs
                        </a>
                    </div>
                @endif
            @endauth


            @forelse($faqs->groupBy('category') as $category => $items)

                <h2 class="text-xl font-semibold text-white mt-8 mb-4">
                    {{ strtoupper($category) }}
                </h2>

                @foreach($items as $faq)
                    <div class="mb-4 bg-gradient-to-r from-[#102635] to-[#0d1f2b]
                                border border-gray-700 rounded-xl p-5">

                        <h3 class="text-white font-semibold text-lg mb-2">
                            {{ $faq->question }}
                        </h3>

                        <p class="text-gray-300 leading-relaxed">
                            {{ $faq->answer }}
                        </p>

                    </div>
                @endforeach

            @empty
                <p class="text-gray-400">
                    No FAQs available at the moment.
                </p>
            @endforelse

        </div>
    </div>
</x-app-layout>
