<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @auth
            <meta name="user-id" content="{{ Auth::id() }}">
        @endauth


        <title>{{ config('app.name') }} Admin - @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        <script src="https://cdn.tiny.cloud/1/lcugueg1lhtpmnv56zjf9shxtcrsvol11yszc1kga1o04cee/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        @stack('scripts')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('admin.partials.header')

            <div class="flex">
                @include('admin.partials.sidebar')
                <!-- Page Content -->
                <main class="flex-1 p-8">
                    <div class="max-w-7xl mx-auto">
                        @include('admin.partials.messages')
                        @yield('content')
                    </div>
                </main>
            </div>

            @include('admin.partials.footer')
        </div>
    </body>
</html>
