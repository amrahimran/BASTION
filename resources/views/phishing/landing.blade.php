<x-app-layout>
<div class="max-w-md mx-auto p-6 bg-[#102635] text-white rounded-xl">

    <h2 class="text-xl font-bold mb-4">Company Login</h2>

    <form method="POST" action="{{ route('phishing.submit', $simulation->id) }}">
        @csrf

        <input type="text" placeholder="Username"
               class="w-full mb-3 p-2 bg-[#0b1d2a] rounded" required>

        <input type="password" placeholder="Password"
               class="w-full mb-4 p-2 bg-[#0b1d2a] rounded" required>

        <button class="bg-[#00c3b3] w-full py-2 rounded text-black font-semibold">
            Login
        </button>
    </form>

    <p class="text-xs text-yellow-400 mt-4">
        ⚠ This is a cybersecurity awareness simulation. No credentials are stored.
    </p>
</div>
</x-app-layout>
