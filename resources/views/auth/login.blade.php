<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#0b1d2a] text-white font-[Poppins] bg-cover bg-center" 
         style="background-image: url('{{ asset('images/bg-img.jpg') }}');
         font-family: 'Orbitron', sans-serif;">
        <div class="bg-[#0e2534]/90 rounded-2xl shadow-xl w-full max-w-md p-8">
            
            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/bastionlogo.png') }}" alt="Bastion Logo" class="w-44 h-44 object-contain">
                <h1 class="text-2xl  font-semibold text-[#FFFFFF]" style="font-family: 'Orbitron', sans-serif;">Login</h1>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" />

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-500">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-300" />
                    <x-input id="email" 
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white focus:border-[#00c3b3] focus:ring-[#00c3b3]" 
                             type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-300" />
                    <x-input id="password" 
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white focus:border-[#00c3b3] focus:ring-[#00c3b3]" 
                             type="password" name="password" required autocomplete="current-password" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="flex items-center text-sm text-gray-400">
                        <x-checkbox id="remember_me" name="remember" class="text-[#00c3b3]" />
                        <span class="ml-2">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-[#00c3b3] hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <div class="mt-6 flex justify-center">
                    <button type="submit" 
                            class="w-full bg-[#00c3b3] hover:bg-[#00e6d3] text-white font-semibold py-2 rounded-md transition duration-300">
                        {{ __('Log in') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    @if (Route::has('register'))
                        <p class="text-sm text-gray-400">
                            Don’t have an account?
                            <a href="{{ route('register') }}" class="text-[#00c3b3] hover:underline">
                                {{ __('Sign up') }}
                            </a>
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
