<!DOCTYPE html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Lato:wght@100;400;600;700&display=swap" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/5ef88b4d32.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js"></script> 


        <!-- Styles -->
        <link href="{{ mix('/styles/app.css') }}" rel="stylesheet" crossorigin="anonymous">
        <link href="{{ mix('/styles/printable.css') }}" rel="stylesheet" crossorigin="anonymous">

        @livewireStyles
    </head>
    <body class="font-sans antialiased d-flex flex-column h-100 @yield('slug')">
        @include('layouts.navigation')

        <div class="flex-shrink-0">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <footer class="footer mt-auto"><div class="container">
            x
        </div></footer>

        @livewireScripts
        
        <!-- Scripts -->
        <script src="{{ mix('/scripts/app.js') }}" defer></script>
    </body>
</html>
