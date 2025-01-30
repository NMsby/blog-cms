<x-mail::message>
    # Welcome to {{ config('app.name') }}!

    Hi {{ $user->name }},

    Your author account has been created successfully. You can now:
    - Create and manage blog posts
    - Comment on other posts
    - Upload media for your content

    <x-mail::button :url="route('login')">
        Login to Dashboard
    </x-mail::button>

    Best regards,
    {{ config('app.name') }}
</x-mail::message>
