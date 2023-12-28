<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') |
        @endif {{ config('app.name', 'Medix') }}
    </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{-- <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet"> --}}
    @livewireStyles
</head>

<body class="h-screen" style="margin-top:70px;">
    <div class="h-full flex flex-col justify-between">  
        <div class="w-full bg-white">
            @include('layouts.components.navbar')
        </div>
        <div class="flex-grow w-full"  style='margin-top:30px;'>
            @yield('content')
        </div>
        <div class="py-5 w-full bg-primary">
            <div class="w-full flex flex-col sm:flex-row justify-between items-center text-primary mt-4">
                <div class="mb-4 sm:mb-0">
                    <p class="ml-4 mr-4 text-xl font-bold text-white">CONTACTO: </p>
                    <p class="ml-4 mr-4 text-lg sm:text-base text-white">EMAIL@EMAIL.COM</p>
                    <p class="ml-4 mr-4 text-lg sm:text-base text-white">TELEFONO: 55 45 67 89 90</p>
                </div>

                <div class="text-white">
                    <img src="{{ asset('img/logo-white.png') }}"
                        style="object-fit: cover; width: 100px;"
                        alt="logo">
                </div>

                <div class="flex flex-col items-end">
                    <p class="ml-4 mr-4 text-xl font-bold text-white">TERMINOS Y CONDICIONES </p>
                    <p class="ml-4 mr-4 text-lg sm:text-base text-white">POLITICA DE PRIVACIDAD </p>
                </div>
            </div>
        </div>
        @role(['buyers-manager', 'buyer'])
            @livewire('soporte-component')
        @endrole
    </div>
    @livewireScripts
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
</body>

</html>
