<head>
    @include('confirmation-layout')
    <title>User Settings</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="../../node_modules/preline/dist/preline.js"></script>
</head>

<div class="flex min-h-screen">
    {{-- User Navbar --}}
    @include('user.navbar')
    <div class="flex-1 bg-gray-100 p-6 lg:p-10 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow max-w-xl w-full">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Account Settings</h1>
            <form id="settingsForm" method="POST" action="{{ route('user.settings.update') }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-100 cursor-not-allowed" required readonly>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 pr-10" placeholder="Leave blank to keep current password" minlength="8">
                        <button type="button" id="toggle-password" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-gray-700 focus:outline-none px-2">
                            <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-off-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.61A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 pr-10" minlength="8">
                        <button type="button" id="toggle-password-confirm" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center text-gray-400 hover:text-gray-700 focus:outline-none px-2">
                            <svg id="eye-password-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-off-password-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.25-2.61A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="password-requirements" class="mt-2 text-xs text-gray-600 space-y-1">
                    <div class="flex items-center gap-1">
                        <span id="pw-length-icon" class="inline-flex items-center justify-center w-4 h-4 rounded-full border border-gray-300 bg-white text-gray-400 mr-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        </span>
                        <span>Password is at least 8 characters</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span id="pw-match-icon" class="inline-flex items-center justify-center w-4 h-4 rounded-full border border-gray-300 bg-white text-gray-400 mr-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                        </span>
                        <span>Passwords match</span>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="saveChangesBtn" class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveChangesBtn');

    // Show/hide password toggle for password
    const togglePassword = document.getElementById('toggle-password');
    const eyePassword = document.getElementById('eye-password');
    const eyeOffPassword = document.getElementById('eye-off-password');
    togglePassword.addEventListener('click', function () {
        if (password.type === 'password') {
            password.type = 'text';
            eyePassword.classList.add('hidden');
            eyeOffPassword.classList.remove('hidden');
        } else {
            password.type = 'password';
            eyePassword.classList.remove('hidden');
            eyeOffPassword.classList.add('hidden');
        }
    });

    // Show/hide password toggle for confirmation
    const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
    const eyePasswordConfirm = document.getElementById('eye-password-confirm');
    const eyeOffPasswordConfirm = document.getElementById('eye-off-password-confirm');
    togglePasswordConfirm.addEventListener('click', function () {
        if (confirm.type === 'password') {
            confirm.type = 'text';
            eyePasswordConfirm.classList.add('hidden');
            eyeOffPasswordConfirm.classList.remove('hidden');
        } else {
            confirm.type = 'password';
            eyePasswordConfirm.classList.remove('hidden');
            eyeOffPasswordConfirm.classList.add('hidden');
        }
    });

    // Password requirements icons
    const pwLengthIcon = document.getElementById('pw-length-icon');
    const pwMatchIcon = document.getElementById('pw-match-icon');

    function updatePasswordRequirements() {
        // Requirement 1: At least 8 characters
        if (password.value.length >= 8) {
            pwLengthIcon.classList.remove('border-gray-300', 'text-gray-400', 'bg-white');
            pwLengthIcon.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
        } else {
            pwLengthIcon.classList.add('border-gray-300', 'text-gray-400', 'bg-white');
            pwLengthIcon.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
        }
        // Requirement 2: Passwords match (only if confirmation is not empty)
        if (password.value.length > 0 && confirm.value.length > 0 && password.value === confirm.value) {
            pwMatchIcon.classList.remove('border-gray-300', 'text-gray-400', 'bg-white');
            pwMatchIcon.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
        } else {
            pwMatchIcon.classList.add('border-gray-300', 'text-gray-400', 'bg-white');
            pwMatchIcon.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
        }
    }

    function validatePasswords() {
        if (
            (password.value.length > 0 || confirm.value.length > 0) &&
            (password.value.length < 8 || confirm.value.length < 8)
        ) {
            confirm.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            confirm.classList.add('border-red-600', 'focus:border-red-600', 'focus:ring-red-600');
            saveBtn.disabled = true;
            saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else if (confirm.value.length > 0 && password.value !== confirm.value) {
            confirm.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            confirm.classList.add('border-red-600', 'focus:border-red-600', 'focus:ring-red-600');
            saveBtn.disabled = true;
            saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            confirm.classList.remove('border-red-600', 'focus:border-red-600', 'focus:ring-red-600');
            confirm.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    password.addEventListener('input', function() {
        validatePasswords();
        updatePasswordRequirements();
    });
    confirm.addEventListener('input', function() {
        validatePasswords();
        updatePasswordRequirements();
    });

    // Confirmation modal for saving changes
    saveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!saveBtn.disabled) {
            showConfirmationModal(
                'Save Changes',
                'Are you sure you want to save these changes to your account settings?',
                function() {
                    form.submit();
                },
                'confirmation'
            );
        }
    });
});
</script>

