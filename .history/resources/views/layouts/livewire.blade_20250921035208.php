<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Livewire Component' }} - {{ config('app.name', 'Laravel') }}</title>
        
        <!-- CSS Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Theme JS -->
        <script src="{{ asset('dist/js/app.js') }}"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="app">
        <!-- Mobile Menu -->
        @include('partials.mobile')

        <!-- Top Bar -->
        @include('partials.topbar')

        <!-- Main Content -->
        <div class="wrapper">
            <div class="wrapper-box">
                <!-- Sidebar -->
                @include('partials.sidebar')
                
                <!-- Content Area -->
                <div class="content">
                    <!-- Page Header -->
                    <div class="intro-y flex items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            {{ $title ?? 'Livewire Component' }}
                        </h2>
                    </div>
                    
                    <!-- Flash Messages -->
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
                    
                    <!-- Livewire Component Content -->
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- JS Assets -->
        @vite(['resources/js/app.js'])
        @stack('scripts')
        
        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
