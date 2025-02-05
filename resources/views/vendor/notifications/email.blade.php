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
    @isset($introLines)
        @foreach ($introLines as $line)
            {{ $line }}

        @endforeach
    @endisset

    {{-- Action Button --}}
    @isset($actionText)
        <x-mail::button :url="$actionUrl">
            {{ $actionText }}
        </x-mail::button>
    @endisset

    {{-- Outro Lines --}}
    @isset($outroLines)
        @foreach ($outroLines as $line)
            {{ $line }}
        @endforeach
    @endisset

    {{-- Salutation --}}
    @if (! empty($salutation))
        {{ $salutation }}
    @else
        @lang('Regards'),<br>
        {{ config('app.name') }}
    @endif
</x-mail::message>
</x-mail::message>
