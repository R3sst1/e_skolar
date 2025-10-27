<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Account Management' }} - {{ config('app.name', 'Laravel') }}</title>
        <!-- BEGIN: CSS Assets-->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- END: CSS Assets-->
        <!-- BEGIN: jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- END: jQuery -->
        <!-- BEGIN: Theme JS -->
        <script src="{{ asset('dist/js/app.js') }}"></script>
        <!-- END: Theme JS -->
        <!-- BEGIN: Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- END: Alpine.js -->
        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="app">
        <!-- BEGIN: Mobile Menu -->
        @include('partials.mobile')
        <!-- END: Mobile Menu -->

        <!-- BEGIN: Top Bar -->
        @include('partials.topbar')
        <!-- END: Top Bar -->

        <!-- BEGIN: Content -->
        <div class="wrapper">
            <div class="wrapper-box">
                <!-- BEGIN: Side Menu -->
                @include('partials.sidebar')
                <!-- END: Side Menu -->
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            {{ $title ?? 'Account Management' }}
                        </h2>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success show mb-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger show mb-2" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    {{ $slot }}
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <!-- END: Content -->

        <!-- BEGIN: JS Assets-->
        @vite(['resources/js/app.js'])
        <!-- END: JS Assets-->
        @stack('scripts')
        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
