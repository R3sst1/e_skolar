<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('Images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sulop Scholarship Management</title>
    <!-- BEGIN: CSS Assets-->
   
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- END: CSS Assets-->
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="/" class="-intro-x flex items-center pt-5">
                <img alt="Logo" class="w-6" src="{{ asset('Images/logo.svg') }}">
                    <span class="text-white text-lg ml-3"> Sulop Scholarship </span> 
                </a>
                <div class="my-auto">
                    <img alt="Illustration" class="-intro-x w-1/2 -mt-16" src="{{ asset('Images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        A few more clicks to <br> sign in to your account.
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Manage all your scholarship accounts in one place</div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        Sign In
                    </h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your scholarship accounts in one place</div>
                    <form method="POST" action="{{ route('login') }}" class="intro-x mt-8">
                        @csrf
                        <input id="login" name="login" type="text" class="intro-x login__input form-control py-3 px-4 block" 
                            placeholder="Username or Email" value="{{ old('login') }}" required autofocus>
                        @error('login')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                        <input id="password" name="password" type="password" class="intro-x login__input form-control py-3 px-4 block mt-4" 
                            placeholder="Password" required autocomplete="current-password">
                            <button type="button" onclick="toggleLoginPassword()">
                            <i id="eyeIcon" class="eye"></i>
                        @error('password')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" name="remember" type="checkbox" class="form-check-input border mr-2" {{ old('remember') ? 'checked' : '' }}>
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            @endif
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Register</a>
                        </div>
                    </form>
                    <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left"> By signing up, you agree to our <a class="text-primary dark:text-slate-200" href="#">Terms and Conditions</a> & <a class="text-primary dark:text-slate-200" href="#">Privacy Policy</a> </div>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>

</body>


    <script>
        function toggleLoginPassword() {
            const password = document.getElementById("loginPassword");
            const eyeIcon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.remove("eye");
                eyeIcon.classList.add("eye-off");
            } else {
                password.type = "password";
                eyeIcon.classList.remove("eye-off");
                eyeIcon.classList.add("eye");
            }
        }
    </script>
</html>
