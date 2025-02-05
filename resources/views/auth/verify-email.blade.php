<x-guest-layout>
        <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Thanks for signing up as an author! Please verify your email to access all features.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email.') }}
                    </p>
                </div>
            @endif

            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <x-primary-button class="w-full sm:w-auto justify-center">
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </form>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <x-secondary-button type="submit" class="w-full sm:w-auto justify-center">
                        {{ __('Log Out') }}
                    </x-secondary-button>
                </form>
            </div>
        </div>
</x-guest-layout>
