<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div
            x-data="{ menuOpen: false }"
            class="flex min-h-screen custom-scrollbar dark:bg-gray-900"
        >
            <!-- start::Black overlay -->
            <div
                :class="menuOpen ? 'block' : 'hidden'" @click="menuOpen = false"
                class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"
            ></div>
            <!-- end::Black overlay -->

            @include('layouts.adminSidebar')

            {{ $sidebar }}

            <div class="lg:pl-64 w-full flex flex-col">
                @include('layouts.adminNav')

                <!-- start::Topbar -->
                <div class="flex flex-col">
                    {{ $header }}
                </div>

                <!-- start:Page content -->
                <div class="h-full bg-gray-200 p-8">
                    {{ $slot }}
                </div>
                <!-- end:Page content -->
            </div>
        </div>

        @livewireScripts
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts />
    </body>
</html>
