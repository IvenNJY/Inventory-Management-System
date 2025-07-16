<head>
    <title>Sign In</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="../../node_modules/preline/dist/preline.js"></script>
</head>

<body class="relative min-h-screen">
    @include('toast-layout')
    <div class="flex flex-row min-h-screen justify-center items-center bg-gray-200 ">
        <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow max-w-lg w-full fade-in">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800">Asset Management System</h1>
                </div>
                <div class="mt-5 p-10">
                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="grid gap-y-4">
                            <!-- Form Group -->
                            <div>
                                <label for="email" class="block text-sm mb-2">Email address</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="email-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="hidden text-xs text-red-600 mt-2" id="email-error">Please include a valid email address so we can get back to you</p>
                            </div>
                            <!-- End Form Group -->
                            <!-- Form Group -->
                            <div>
                                <div class="flex flex-wrap justify-between items-center gap-2">
                                    <label for="password" class="block text-sm mb-2">Password</label>
                                </div>
                                <div class="relative">
                                    <input type="password" id="password" name="password" class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="password-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="hidden text-xs text-red-600 mt-2" id="password-error">8+ characters required</p>
                                <a href="{{ url('/forgot-password') }}" class="text-sm text-blue-600 hover:underline gap-y-1 mt-2">Forgot password?</a>
                            </div>
                            <!-- End Form Group -->
                            <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" id="sign-in-btn">Sign in</button>
                        </div>
                        <!-- Added links below the button -->
                        <div class="flex flex-col items-center mt-4 space-y-2">
                            <span class="text-sm text-gray-500">Don't have an account?
                                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up here</a>
                            </span>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </div>
    </div>
    <footer>
        @vite('resources/js/app.js') {{-- Ensure Preline and other JS are initialized here --}}
        <style>
          .fade-in {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInBox 0.8s ease-in-out forwards;
          }
          @keyframes fadeInBox {
            to {
              opacity: 1;
              transform: none;
            }
          }
        </style>
    </footer>
</body>