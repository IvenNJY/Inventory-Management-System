<head>
    <title>User Management</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    <script src="../../node_modules/preline/dist/preline.js"></script>

</head>

<div class="flex min-h-screen"> {{-- Flex container for navbar and content --}}
    <div class="w-70 flex-shrink-0"> {{-- Fixed width sidebar --}}
        @include('admin.navbar')
    </div>
    <div class="flex-1 bg-gray-100 p-4 sm:p-6 lg:p-8 w-[80vw]"> {{-- Main content area --}}
        <div class="bg-white p-6 rounded-lg shadow"> {{-- Main content wrapper --}}
        
        {{-- Page Title and Action Buttons --}}
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">User Management</h1>
            <div class="flex gap-x-2">
                <button type="button" id="import-users-btn" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-400 bg-white text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-import-users-modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Bulk Add Users
                </button>
                <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-create-user-modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create New User
                </button>
            </div>
        </div>

        {{-- Status Alert --}}
        @if(session('status'))
            @php $status = session('status'); @endphp
            <div class="mb-4">
                <div class="rounded-lg px-4 py-3 text-sm font-medium {{ $status['success'] ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                    {{ $status['message'] }}
                </div>
            </div>
        @endif

        {{-- Import Users Modal --}}
        <div id="hs-import-users-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center py-3 px-4">
                        <h3 class="font-bold text-gray-800">Bulk Add Users</h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 black hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-import-users-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <div class="mb-4 text-center">
                            <p class="mb-4">Please follow the format of of the sample CSV to assure data is uploaded correctly</p>
                            <a href="{{ asset('sample_users.csv') }}" download class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 bg-gray-50 text-gray-800 hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4"/></svg>
                                Download Sample CSV
                            </a>
                        </div>
                        <form id="import-users-form" action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-y-4">
                            @csrf
                            <label for="import_file" class="block w-full text-center cursor-pointer border-2 border-dashed border-gray-300 rounded-lg py-8 px-4 hover:border-blue-400 transition">
                                <span class="block text-gray-600 mb-2">Drop your CSV/XLSX file here or click to choose</span>
                                <span id="import-file-name" class="block text-xs text-gray-500">No files Chosen</span>
                                <input type="file" name="import_file" id="import_file" accept=".csv, .xlsx" class="hidden" required>
                            </label>
                            <button type="submit" class="py-2 px-4 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">Import Users</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="flex flex-col w-auto">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 inline-block align-middle min-w-full">
                    <div class="shadow-md rounded-lg overflow-hidden">
                        <table id="users-table" class="min-w-full divide-y divide-gray-200 relative">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created At
                                    </th>
                                    <th scope="col" class="sticky right-0 bg-gray-50 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider z-10">
                                        <div class="flex items-center justify-end">
                                            Actions
                                            <button id="filter-toggle-btn" type="button" class="ml-2 p-2 text-xs font-semibold rounded bg-white hover:bg-gray-100 text-gray-700 border border-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                                <tr id="filter-row" class="hidden bg-gray-100">
                                    <td class="px-6 py-2">
                                        <input type="text" id="filter-name" placeholder="Filter Name" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" />
                                    </td>
                                    <td class="px-6 py-2">
                                        <input type="text" id="filter-email" placeholder="Filter Email" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" />
                                    </td>
                                    <td class="px-6 py-2">
                                        <select id="filter-role" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2">
                                            <option value="">All Roles</option>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-2">
                                        <input type="text" id="filter-date" placeholder="Filter Date (YYYY-MM-DD)" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" />
                                    </td>
                                    <td class="px-6 py-2 sticky right-0 bg-gray-100 z-10"></td>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $roleClasses = [
                                        'admin' => 'bg-purple-100 text-purple-800',
                                        'user' => 'bg-blue-100 text-blue-800',

                                    ];
                                @endphp

                                @foreach($users as $user)
                                <tr class="user-row" data-user-id="{{ $user->id }}">
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900 bg-white user-name">{{ $user->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500 bg-white user-email">{{ $user->email }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap bg-white user-role">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClasses[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500 bg-white user-created-at">{{ $user->created_at }}</td>
                                    <td class="sticky right-0 bg-white px-6 py-2 whitespace-nowrap text-right text-sm font-medium z-10">
                                        <button 
                                            type="button" 
                                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none edit-user-btn" 
                                            data-hs-overlay="#hs-edit-user-modal"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->role }}"
                                            data-user-edit-url="{{ route('admin.users.update', $user->id) }}"
                                        >Edit</button>
                                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 disabled:opacity-50 disabled:pointer-events-none delete-user-btn" data-hs-overlay="#hs-delete-user-modal" data-user-id="{{ $user->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Users Table --}}

        {{-- Create User Modal --}}
        <div id="hs-create-user-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center py-3 px-4 ">
                        <h3 class="font-bold text-gray-800">
                            Create New User
                        </h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 black hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-create-user-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <form id="create-user-form" method="POST" action="{{ route('admin.users.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select name="role" id="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4">
                        {{-- Cancel and Save Buttons --}}
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-create-user-modal">
                            Cancel
                        </button>
                        <button type="submit" form="create-user-form" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create User Modal --}}

        {{-- Edit User Modal --}}
        <div id="hs-edit-user-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center py-3 px-4">
                        <h3 class="font-bold text-gray-800">
                            Edit User
                        </h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-edit-user-modal">
                            <span class="sr-only">Close</span>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <form id="edit-user-form" method="POST" action="">
                            @csrf
                            <div class="grid gap-y-4">
                                <div>
                                    <label for="edit-user-name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="edit-user-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="edit-user-email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="edit-user-email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="edit-user-role" class="block text-sm font-medium text-gray-700">Role</label>
                                    <select name="role" id="edit-user-role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4">
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-edit-user-modal">
                            Cancel
                        </button>
                        <button type="submit" form="edit-user-form" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Edit User Modal --}}

        {{-- Delete User Modal --}}
        <div id="hs-delete-user-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-500/50 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto ">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100  hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="relative flex flex-col  bg-white shadow-sm rounded-xl">
                    <div class="absolute top-2 end-2">
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-delete-user-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-10 text-center overflow-y-auto">
                        <!-- Icon -->
                        <span class="mb-4 inline-flex justify-center items-center size-[62px] rounded-full border-4 border-red-50 bg-red-100 text-red-500">
                            <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                            </svg>
                        </span>
                        <!-- End Icon -->

                        <h3 class="mb-2 text-2xl font-bold text-gray-800">
                            Delete User
                        </h3>
                        <p class="text-gray-500">
                            Are you sure you want to permanently delete this user? This action cannot be undone.
                        </p>

                        <form id="delete-user-form" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <div class="mt-6 flex justify-center gap-x-4">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-delete-user-modal">
                                    Cancel
                                </button>
                                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-500 text-white hover:bg-red-600 disabled:opacity-50 disabled:pointer-events-none">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Delete User Modal --}}
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterToggleButton = document.getElementById('filter-toggle-btn');
        const filterRow = document.getElementById('filter-row');
        
        const filterNameInput = document.getElementById('filter-name');
        const filterEmailInput = document.getElementById('filter-email');
        const filterRoleSelect = document.getElementById('filter-role');
        const filterDateInput = document.getElementById('filter-date');
        
        const tableRows = document.querySelectorAll('#users-table tbody tr.user-row');

        filterToggleButton.addEventListener('click', function() {
            filterRow.classList.toggle('hidden');
        });

        function applyFilters() {
            const nameFilter = filterNameInput.value.toLowerCase();
            const emailFilter = filterEmailInput.value.toLowerCase();
            const roleFilter = filterRoleSelect.value;
            const dateFilter = filterDateInput.value;

            tableRows.forEach(row => {
                const name = row.querySelector('.user-name').textContent.toLowerCase();
                const email = row.querySelector('.user-email').textContent.toLowerCase();
                const role = row.querySelector('.user-role span').textContent.trim();
                const createdAt = row.querySelector('.user-created-at').textContent;

                let showRow = true;

                if (nameFilter && !name.includes(nameFilter)) {
                    showRow = false;
                }
                if (emailFilter && !email.includes(emailFilter)) {
                    showRow = false;
                }
                if (roleFilter && role !== roleFilter) {
                    showRow = false;
                }
                if (dateFilter && !createdAt.startsWith(dateFilter)) {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        filterNameInput.addEventListener('input', applyFilters);
        filterEmailInput.addEventListener('input', applyFilters);
        filterRoleSelect.addEventListener('change', applyFilters);
        filterDateInput.addEventListener('input', applyFilters);

        // Edit User Modal: Populate fields from row data
        document.querySelectorAll('.edit-user-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const userName = btn.getAttribute('data-user-name') || '';
                const userEmail = btn.getAttribute('data-user-email') || '';
                const userRole = btn.getAttribute('data-user-role') || '';
                const userEditUrl = btn.getAttribute('data-user-edit-url');
                document.getElementById('edit-user-name').value = userName;
                document.getElementById('edit-user-email').value = userEmail;
                document.getElementById('edit-user-role').value = userRole;
                // Set form action using route URL
                document.getElementById('edit-user-form').action = userEditUrl;
            });
        });

        // Delete User Modal: Set form action to correct user
        document.querySelectorAll('.delete-user-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var userId = btn.getAttribute('data-user-id');
                var form = document.getElementById('delete-user-form');
                form.action = "{{ url('admin/users') }}/" + userId;
            });
        });

        // Show selected file name for import users
        const importFileInput = document.getElementById('import_file');
        const importFileNameSpan = document.getElementById('import-file-name');
        if (importFileInput && importFileNameSpan) {
            importFileInput.addEventListener('change', function() {
                importFileNameSpan.textContent = importFileInput.files.length > 0 ? importFileInput.files[0].name : '';
            });
        }
    });
</script>