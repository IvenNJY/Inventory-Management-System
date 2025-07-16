<head>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    const registerBtn = document.querySelector('button[type="submit"]');

    function checkPasswords() {
        const pass = password.value;
        const confirm = passwordConfirm.value;
        const filled = pass.length > 0 && confirm.length > 0;
        const match = pass === confirm;
        if (!filled || !match) {
            registerBtn.disabled = true;
            registerBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            registerBtn.disabled = false;
            registerBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    password.addEventListener('input', checkPasswords);
    passwordConfirm.addEventListener('input', checkPasswords);
    checkPasswords();
});
</script>
    <title>Register</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    <script src="../../node_modules/preline/dist/preline.js"></script>
</head>

<div class="flex flex-row min-h-screen justify-center items-center bg-gray-200 ">
<div class="mt-7 bg-white border border-gray-200 rounded-xl shadow max-w-lg w-full fade-in">
  <div class="p-4 sm:p-7">
    <div class="text-center">
      <h1 class="block text-2xl font-bold text-gray-800">Register Account</h1>
    </div>
    <div class="mt-5 p-10">
      @include('toast-layout')
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="grid gap-y-4">
          <div>
            <label for="name" class="block text-sm mb-2">Name</label>
            <input type="text" id="name" name="name" class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500" required>
          </div>
          <div>
            <label for="email" class="block text-sm mb-2">Email address</label>
            <input type="email" id="email" name="email" class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500" required>
          </div>
          <div>
            <label for="password" class="block text-sm mb-2">Password</label>
            <input type="password" id="password" name="password" class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500" required minlength="8">
            <p class="text-xs text-gray-500 mt-2">8+ characters required</p>
          </div>
          <div>
            <label for="password_confirmation" class="block text-sm mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="py-2.5 sm:py-3 px-4 block w-full border border-gray-300 rounded-lg sm:text-sm focus:border-gray-200 focus:ring-blue-500" required minlength="8">
          </div>
          <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700">Register</button>
        </div>
        <div class="flex flex-col items-center mt-4 space-y-2">
          <span class="text-sm text-gray-500">Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign in here</a>
          </span>
        </div>
      </form>
    </div>
  </div>
  </div>
  </div>
</div>
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