<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    <script src="../../node_modules/preline/dist/preline.js"></script>
</head>
@include('toast-layout')

<div class="flex flex-row min-h-screen justify-center items-center bg-gray-200 ">
<div class="mt-7 bg-white border border-gray-200 rounded-xl shadow max-w-lg w-full fade-in">
  <div class="p-4 sm:p-7">
    <div class="text-center">
      <h1 class="block text-2xl font-bold text-gray-800">Reset Password</h1>
        {{-- Session Messages --}}
        {{-- Removed inline status and error display, now handled by toast-layout --}}
    </div>
    <div class="mt-5 p-10">
      <form method="POST" action="{{ route('password.request') }}">
        @csrf
        <div class="grid gap-y-4">
          <div>
            <label for="email" class="block text-sm mb-2">Email address</label>

            <input type="email" id="email" name="email" 
            class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500" 
            required>

          </div>
          <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700">Send Reset Link</button>
        </div>
        <div class="flex flex-col items-center mt-4 space-y-2">
          <span class="text-sm text-gray-500">Remembered your password?
            <a href="{{ url('/') }}" class="text-blue-600 hover:underline">Sign in here</a>
          </span>
        </div>
      </form>
    </div>
  </div>
  </div>
</div>
<footer>
    @vite('resources/js/app.js')
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
