<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @auth
            <meta name="user-id" content="{{ Auth::id() }}">
        @endauth

        <title>{{ config('app.name', 'Blog') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold"></h2>
                        @yield('content')
                    </div>
                </header>
            @endif

            <!-- Toasts -->
            <x-toast />

            <!-- Page Content -->
            <main class="py-2">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @include('layouts.partials.messages')
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.tiny.cloud/1/lcugueg1lhtpmnv56zjf9shxtcrsvol11yszc1kga1o04cee/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        @stack('scripts')
    </body>
</html>
