<x-mail::message>
    # Verify Your Email Address

    Thanks for registering as an author. Please verify your email address by clicking the button below.

    <x-mail::button :url="$verificationUrl">
        Verify Email Address
    </x-mail::button>

    If you did not create an account, no further action is required.

    Best regards,
    {{ config('app.name') }}
</x-mail::message>
