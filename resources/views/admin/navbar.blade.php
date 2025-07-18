<!-- Sidebar -->
<style>
    .tree-view-menu > li {
        position: relative;
        padding-left: 1.5rem; /* 24px */
    }

    .tree-view-menu > li::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0.5rem; /* 8px */
        width: 2px;
        height: 100%;
        background-color: #e5e7eb;
        z-index: 0;
    }

    .tree-view-menu > li:last-child::before {
        height: 1.125rem; /* 18px, stop at the horizontal line */
    }

    .tree-view-menu > li::after {
        content: '';
        position: absolute;
        top: 1.125rem; /* 18px, vertically center with the link */
        left: 0.5rem; /* 8px */
        width: 0.75rem; /* 12px */
        height: 2px;
        background-color: #e5e7eb;
        display: none;
        z-index: 1;
    }

    .tree-view-menu > li:last-child::after {
        display: block;
    }
</style>
<div class="flex h-screen flex-col justify-between border-e border-gray-200 bg-white w-70 fixed top-0 left-0">
    <div class="px-4 py-6">
        <!-- Logo -->
        <div class="flex justify-center items-center py-4 mb-4">
            <img src="{{ asset('assets/logo.png') }}" alt="INFINECS Logo" class="h-16"> 
            <!-- Replace with your actual logo path and adjust styling as needed -->
            {{-- <span class="text-xl font-bold text-gray-700 ml-2">INFINECS</span> --}}
            {{-- You can use text or an image for the logo name part --}}
        </div>
        
    <hr class="border-t border-gray-100 mx-4"> <!-- Added horizontal rule for visual separation -->

    @php
        $pendingRequestCount = \App\Models\RequestAsset::where('status', 'Pending')->count();
    @endphp
        <ul class="mt-6 space-y-1">
            <li>
                <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
            </li>

            <li>
                <details class="group [&_summary::-webkit-details-marker]:hidden" open>
                    <summary
                        class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    >
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="text-sm font-medium"> Assets </span>
                        </div>
                        <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </summary>

                    <ul class="mt-2 space-y-1 px-4 tree-view-menu">
                        <li>
                            <a
                                href="{{ url('admin/assets') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            >
                                Asset Details
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ url('admin/add-assets') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            >
                                Add New Assets
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ url('admin/warranty') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            >
                                Warranty Tracking
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ url('admin/lifespan') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            >
                                Lifespan Tracking
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            <li>
                <details class="group [&_summary::-webkit-details-marker]:hidden" open>
                    <summary
                        class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    >
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium"> Users </span>
                        </div>
                        <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </summary>

                    <ul class="mt-2 space-y-1 px-4 tree-view-menu">
                        <li>
                            <a
                                href="{{ url('admin/users') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            >
                                Users Details
                            </a>
                        </li>
                                                <li>
                            <a
                                href="{{ url('admin/deployment') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            >
                                Deployment
                            </a>
                        </li>
                        
                        <li>
                            <a
                                href="{{ url('admin/requests') }}"
                                class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 relative"
                            >
                                <span>User Requests</span>
                                @if($pendingRequestCount > 0)
                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500 ">
                                            {{ $pendingRequestCount }}
                                        </span>
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <a href="{{ url('admin/settings') }}" class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 @if(request()->is('admin/settings')) bg-gray-100 @endif">
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
            <button type="button" id="logoutButton" class="flex w-full items-center gap-2 bg-white p-4 text-left hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
