<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Manage FAQs') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#0b1d2a] px-6 py-12">
        <div class="max-w-5xl mx-auto">

            {{-- Add FAQ Form --}}
            <div class="p-6 mb-6 border border-gray-700 rounded-xl bg-gradient-to-r from-[#102635] to-[#0d1f2b] shadow-lg">
                <h3 class="text-[#00c3b3] font-bold text-lg mb-4">Add New FAQ</h3>

                <form method="POST" action="{{ route('admin.faq.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="text-gray-300">Question</label>
                        <input type="text" name="question" required
                            class="w-full px-3 py-2 rounded bg-gray-800 text-white focus:outline-none">
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-300">Answer</label>
                        <textarea name="answer" required
                            class="w-full px-3 py-2 rounded bg-gray-800 text-white focus:outline-none"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-300">Category (optional)</label>
                        <input type="text" name="category"
                            class="w-full px-3 py-2 rounded bg-gray-800 text-white focus:outline-none">
                    </div>
                    <button type="submit"
                        class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:opacity-90 transition">
                        Add FAQ
                    </button>
                </form>
            </div>

            {{-- Existing FAQs --}}
            <h3 class="text-[#00c3b3] font-bold text-lg mb-4">Existing FAQs</h3>

            @forelse($faqs as $faq)
                <div class="p-4 mb-4 border border-gray-700 rounded-xl bg-gradient-to-r from-[#102635] to-[#0d1f2b] shadow-md">

                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-white font-semibold">Q: {{ $faq->question }}</p>
                            <p class="text-gray-300 mt-1">A: {!! nl2br(e($faq->answer)) !!}</p>
                            @if($faq->category)
                                <span class="text-sm text-[#00c3b3] mt-1 inline-block">Category: {{ $faq->category }}</span>
                            @endif
                            <p class="text-sm text-gray-400 mt-1">Status: 
                                <span class="{{ $faq->is_active ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>

                        <div class="flex space-x-2">
                            {{-- Toggle Active --}}
                            <form method="POST" action="{{ route('admin.faq.toggle', $faq->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-yellow-500 text-black px-2 py-1 rounded hover:opacity-90 text-sm">
                                    {{ $faq->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            {{-- Edit FAQ --}}
                            <button onclick="document.getElementById('edit-form-{{ $faq->id }}').classList.toggle('hidden')"
                                class="bg-blue-500 text-black px-2 py-1 rounded hover:opacity-90 text-sm">
                                Edit
                            </button>

                            {{-- Delete FAQ --}}
                            <form method="POST" action="{{ route('admin.faq.destroy', $faq->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 text-black px-2 py-1 rounded hover:opacity-90 text-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Inline Edit Form --}}
                    <form method="POST" action="{{ route('admin.faq.update', $faq->id) }}"
                        id="edit-form-{{ $faq->id }}" class="mt-4 hidden">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <input type="text" name="question" value="{{ $faq->question }}" required
                                class="w-full px-3 py-2 rounded bg-gray-800 text-white focus:outline-none">
                        </div>
                        <div class="mb-2">
                            <textarea name="answer" required
                                class="w-full px-3 py-2 rounded bg-gray-800 text-white focus:outline-none">{{ $faq->answer }}</textarea>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="category" value="{{ $faq->category }}"
                                class="w-full px-3 py-2 rounded bg-gray-800 text-white focus:outline-none">
                        </div>
                        <button type="submit"
                            class="bg-[#00c3b3] text-black px-4 py-2 rounded hover:opacity-90 transition">
                            Save Changes
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-400 text-center">No FAQs added yet.</p>
            @endforelse

        </div>
    </div>
</x-app-layout>
