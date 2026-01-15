<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Frequently Asked Questions') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-10">

        <div class="max-w-4xl mx-auto" x-data="{ search: '' }">

            <h1 class="text-3xl font-bold text-[#00c3b3] mb-12 text-center">
                Help & Security Guidance
            </h1>

            <!-- SEARCH BAR -->
            <div class="mb-6 relative">
                <!-- Search Icon -->
                <svg
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#00c3b3]"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m1.6-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>

                <input
                    type="text"
                    x-model="search"
                    placeholder="Search by question or category..."
                    class="w-full pl-12 pr-4 py-2 rounded-lg bg-[#102635] border border-gray-700
                        text-white placeholder-gray-400 focus:outline-none
                        focus:ring-2 focus:ring-[#00c3b3]"
                >
            </div>


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

                <!-- CATEGORY -->
                <div
                    x-show="
                        search === '' ||
                        '{{ strtolower($category) }}'.includes(search.toLowerCase()) ||
                        [...$el.querySelectorAll('[data-question]')]
                            .some(q => q.dataset.question.includes(search.toLowerCase()))
                    "
                >
                    <h2 class="text-xl font-semibold text-white mt-8 mb-4 text-center">
                        {{ strtoupper($category) }}
                    </h2>

                    @foreach($items as $faq)
                        <div
                            x-data="{ open: false }"
                            data-question="{{ strtolower($faq->question) }}"
                            x-show="
                                search === '' ||
                                '{{ strtolower($category) }}'.includes(search.toLowerCase()) ||
                                '{{ strtolower($faq->question) }}'.includes(search.toLowerCase())
                            "
                            class="mb-4 bg-gradient-to-r from-[#102635] to-[#0d1f2b]
                                   border border-[#00c3b3] rounded-xl p-5"
                        >

                            <!-- CLICKABLE QUESTION AREA -->
                            <div
                                @click="open = !open"
                                class="flex justify-between items-center cursor-pointer"
                            >
                                <h3 class="text-white font-semibold text-lg">
                                    {{ $faq->question }}
                                </h3>

                                <!-- Arrow -->
                                <svg
                                    class="w-5 h-5 text-[#00c3b3] transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- ANSWER -->
                            <div
                                x-show="open"
                                x-collapse
                                class="mt-4 text-gray-300 leading-relaxed"
                            >
                                {{ $faq->answer }}
                            </div>

                        </div>
                    @endforeach
                </div>

            @empty
                <p class="text-gray-400">
                    No FAQs available at the moment.
                </p>
            @endforelse

        </div>
    </div>
</x-app-layout>
