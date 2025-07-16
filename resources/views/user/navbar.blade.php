<div class="flex h-screen flex-col justify-between border-e border-gray-200 bg-white w-64 min-w-64">
    <div class="px-4 py-6">
        <!-- Logo -->
        <div class="flex justify-center items-center py-4 mb-4">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-16">
        </div>
        <ul class="mt-6 space-y-1">
            <li>
                <a href="{{ url('user/dashboard') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 @if(request()->is('user/dashboard')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('user/settings') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 @if(request()->is('user/settings')) bg-gray-100 @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
            </li>
        </ul>
    </div>
    <div class="sticky inset-x-0 bottom-0 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" id="logoutButton" class="flex items-center gap-2 bg-white p-4 w-full hover:bg-gray-50 text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                </svg>
                <span class="text-sm font-medium text-red-700">Logout</span>
            </button>
        </form>
    </div>
</div>

@include('confirmation-layout')

<script>
document.getElementById('logoutButton').addEventListener('click', function(event) {
    event.preventDefault();
    showConfirmationModal(
        'Logout Confirmation',
        'Are you sure you want to logout?',
        function() {
            document.getElementById('logoutForm').submit();
        },
        'warning' // <-- This should be the fourth argument
    );
});
</script>
