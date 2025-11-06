<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#0b1d2a] text-white font-[Poppins] bg-cover bg-center" 
         style="font-family: 'Orbitron', sans-serif; background-image: url('{{ asset('images/bg-img.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
         
        <div class="bg-[#0e2534]/90 rounded-2xl shadow-xl w-full max-w-md p-8">
            
            <!-- Logo and Title -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/bastionlogo.png') }}" alt="Bastion Logo" class="w-24 h-24 object-contain mb-3">
                <h1 class="text-2xl font-semibold text-[#00c3b3]" style="font-family: 'Orbitron', sans-serif;">Password Reset</h1>
                <p class="text-gray-400 text-sm text-center px-6">
                    Forgot your password? No problem.<br>
                    Enter your email and we’ll send you a reset link.
                </p>
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-500">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" />

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="block mb-4">
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-300" />
                    <x-input id="email"
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white 
                                    focus:border-[#00c3b3] focus:ring-[#00c3b3]"
                             type="email"
                             name="email"
                             :value="old('email')"
                             required
                             autofocus
                             autocomplete="username" />
                </div>

                <div class="flex items-center justify-center mt-6">
                    <button type="submit" 
                            class="w-full bg-[#00c3b3] hover:bg-[#00e6d3] text-white font-semibold py-2 rounded-md transition duration-300">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-[#00c3b3] hover:underline">
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
