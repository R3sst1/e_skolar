<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="{{ asset('Images/logo.svg') }}" rel="shortcut icon">
    <title>Register - Sure Scholarship</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!-- END: Head -->
<body class="login">
<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <!-- BEGIN: Register Info -->
        <div class="hidden xl:flex flex-col min-h-screen">
            <a href="" class="-intro-x flex items-center pt-5">
                <img alt="Logo" class="w-6" src="{{ asset('Images/logo.svg') }}">
                <span class="text-white text-lg ml-3"> Sure Scholarship </span>
            </a>
            <div class="my-auto">
                <img alt="Illustration" class="-intro-x w-1/2 -mt-16" src="{{ asset('Images/illustration.svg') }}">
                <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                    A few more clicks to <br> sign up to your account.
                </div>
                <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">
                    Manage all your scholarship applications in one place
                </div>
            </div>
        </div>
        <!-- END: Register Info -->

        <!-- BEGIN: Register Form -->
        <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
            <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                    Sign Up
                </h2>
                <div class="intro-x mt-2 text-slate-400 dark:text-slate-400 xl:hidden text-center">
                    A few more clicks to sign up. Manage all your scholarship applications in one place.
                </div>

                <!-- Show validation errors -->
                @if ($errors->any())
                    <div class="text-red-600 text-sm mt-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="mb-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('register') }}">
                    @csrf
                    <div class="intro-x mt-8">
                        <input name="first_name" type="text" class="login__input form-control py-3 px-4 block" placeholder="First Name" value="{{ old('first_name') }}" required>
                        <input name="middle_name" type="text" class="login__input form-control py-3 px-4 block mt-4" placeholder="Middle Name" value="{{ old('middle_name') }}">
                        <input name="last_name" type="text" class="login__input form-control py-3 px-4 block mt-4" placeholder="Last Name" value="{{ old('last_name') }}" required>
                        <input name="username" type="text" class="login__input form-control py-3 px-4 block mt-4" placeholder="Username" value="{{ old('username') }}" required>
                        <input name="password" type="password" class="login__input form-control py-3 px-4 block mt-4" placeholder="Password" required>
                        <input name="password_confirmation" type="password" class="login__input form-control py-3 px-4 block mt-4" placeholder="Confirm Password" required>
                    </div>

                    <div class="intro-x flex items-center text-slate-600 dark:text-slate-500 mt-4 text-xs sm:text-sm">
                        <input id="terms" type="checkbox" class="form-check-input border mr-2" required>
                        <label for="terms" class="cursor-pointer select-none">I agree to the</label>
                        <a href="#" class="text-primary dark:text-slate-200 ml-1">Privacy Policy</a>.
                    </div>

                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Register</button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Register Form -->
    </div>
</div>


</body>
</html>
