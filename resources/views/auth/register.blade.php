<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#0b1d2a] text-white font-[Poppins] bg-cover bg-center"
         style="font-family: 'Orbitron', sans-serif; background-image: url('{{ asset('images/bg-img.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <div class="bg-[#0e2534]/90 rounded-2xl shadow-xl w-full max-w-md p-8">

            <!-- Logo + Header -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/bastionlogo.png') }}" alt="Bastion Logo" class="w-24 h-24 object-contain mb-3">
                <h1 class="text-2xl font-semibold text-[#00c3b3]" style="font-family: 'Orbitron', sans-serif;">Create Account</h1>
                <p class="text-gray-400 text-sm text-center">Join Bastion — Where Security Meets Intelligence.</p>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" />

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <x-label for="name" value="{{ __('Name') }}" class="text-white" />
                    <x-input id="name"
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white
                                    focus:border-[#00c3b3] focus:ring-[#00c3b3]"
                             type="text"
                             name="name"
                             :value="old('name')"
                             required
                             autofocus
                             autocomplete="name" />
                </div>

                <div class="mb-4">
                    <x-label for="email" value="{{ __('Email') }}" class="text-white" />
                    <x-input id="email"
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white
                                    focus:border-[#00c3b3] focus:ring-[#00c3b3]"
                             type="email"
                             name="email"
                             :value="old('email')"
                             required
                             autocomplete="username" />
                </div>

                <div class="mb-4">
                    <x-label for="password" value="{{ __('Password') }}" class="text-white" />
                    <x-input id="password"
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white
                                    focus:border-[#00c3b3] focus:ring-[#00c3b3]"
                             type="password"
                             name="password"
                             required
                             autocomplete="new-password" />
                </div>

                <div class="mb-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-white" />
                    <x-input id="password_confirmation"
                             class="block mt-1 w-full bg-[#07141d] border border-[#00c3b3]/40 text-white
                                    focus:border-[#00c3b3] focus:ring-[#00c3b3]"
                             type="password"
                             name="password_confirmation"
                             required
                             autocomplete="new-password" />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4 text-sm text-gray-400">
                        <label for="terms" class="flex items-center">
                            <x-checkbox name="terms" id="terms" required class="text-[#00c3b3]" />
                            <span class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-[#00c3b3] hover:underline">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-[#00c3b3] hover:underline">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </span>
                        </label>
                    </div>
                @endif

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-[#00c3b3] hover:underline">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit"
                            class="bg-[#00c3b3] hover:bg-[#00e6d3] text-white font-semibold py-2 px-6 rounded-md transition duration-300">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
