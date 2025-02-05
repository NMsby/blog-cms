{{-- resources/views/vendor/notifications/email.blade.php --}}
<x-mail::message>
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        @if ($level === 'error')
            # @lang('Whoops!')
        @else
            # @lang('Hello!')
        @endif
    @endif

    {{-- Content --}}
    {{ $content }}

    {{-- Action Button --}}
    @isset($actionText)
        <x-mail::button :url="$actionUrl">
            {{ $actionText }}
        </x-mail::button>
    @endisset

    {{-- Subcopy --}}
    @isset($actionText)
        <x-slot:subcopy>
            @lang(
                "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
                'into your web browser:',
                [
                    'actionText' => $actionText,
                ]
            ) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        </x-slot:subcopy>
    @endisset
</x-mail::message>
