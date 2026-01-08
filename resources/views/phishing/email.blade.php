<x-app-layout>
<div class="max-w-2xl mx-auto p-6 text-white bg-[#102635] rounded-xl">
    <h2 class="text-xl font-bold mb-4">📧 New Email</h2>

    <p><strong>From:</strong> IT Security Team</p>
    <p><strong>Subject:</strong> Password Reset Required</p>

    <div class="mt-4 bg-[#0b1d2a] p-4 rounded">
        Your account has been flagged for unusual activity.
        Please verify your account immediately.
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('phishing.landing', $simulation->id) }}"
           class="bg-[#00c3b3] px-6 py-2 rounded font-semibold text-black">
            Verify Account
        </a>
    </div>

    <p class="text-xs text-yellow-400 mt-6">
        ⚠ Simulation only. No real email was sent.
    </p>
</div>
</x-app-layout>
