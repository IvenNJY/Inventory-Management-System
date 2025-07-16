<head>
    <title>Asset Deployment</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="../../node_modules/preline/dist/preline.js"></script>
</head>

<div class="flex min-h-screen">
    <div class="w-70 flex-shrink-0">
        @include('admin.navbar')
    </div>
    <div class="flex-1 bg-gray-100 p-4 sm:p-6 lg:p-8 w-[80vw]">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800">Asset Deployment Management</h1>
                <div class="flex gap-2 items-center">
                    <button id="switch-to-deployed" type="button" class="py-2 px-4 text-sm font-semibold rounded-lg border border-blue-600 bg-blue-50 text-blue-700 hover:bg-blue-100">Deployed Assets</button>
                    <button id="switch-to-history" type="button" class="py-2 px-4 text-sm font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">Deployment History</button>
                </div>
            </div>

            @php
            $tableClasses = "min-w-full divide-y divide-gray-200";
            $thClasses = "px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider";
            $tdClasses = "px-6 py-4 whitespace-nowrap text-sm text-gray-900";
            @endphp

            <div id="deployed-assets-section">
                <div class="flex flex-wrap gap-4 mb-4 items-center justify-end">
                    <button id="filter-toggle-btn-deployed" type="button" class="p-2 text-xs font-semibold rounded bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                        <span class="ml-1">Filter</span>
                    </button>
                </div>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Deployed Assets</h2>
                <div class="flex flex-col w-auto mb-8">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 inline-block align-middle min-w-full">
                            <div class="shadow-md rounded-lg overflow-hidden">
                                <table class="{{ $tableClasses }}" id="deployed-assets-table">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="{{ $thClasses }}">Asset Name</th>
                                            <th class="{{ $thClasses }}">Category</th>
                                            <th class="{{ $thClasses }}">Serial</th>
                                            <th class="{{ $thClasses }}">Assigned To</th>
                                            <th class="{{ $thClasses }}">Status</th>
                                            <th class="{{ $thClasses }} text-right">Actions</th>
                                        </tr>
                                        <tr id="filter-row-deployed" class="hidden bg-gray-100">
                                            <td class="px-6 py-2"><input type="text" id="filter-deployed-name" placeholder="Filter Name" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                            <td class="px-6 py-2"><input type="text" id="filter-deployed-category" placeholder="Filter Category" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                            <td class="px-6 py-2"><input type="text" id="filter-deployed-serial" placeholder="Filter Serial" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                            <td class="px-6 py-2"><input type="text" id="filter-deployed-assigned" placeholder="Filter Assigned" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                            <td class="px-6 py-2"><select id="filter-deployed-status" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2">
                                                <option value="">All Statuses</option>
                                                <option value="In Use">In Use</option>
                                                <option value="Available">Available</option>
                                                <option value="Ended">Ended</option>
                                            </select></td>
                                            <td class="px-6 py-2"></td>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($deployedAssets as $asset)
                                        <tr>
                                            <td class="{{ $tdClasses }}">{{ $asset['name'] }}</td>
                                            <td class="{{ $tdClasses }}">{{ $asset['category'] }}</td>
                                            <td class="{{ $tdClasses }}">{{ $asset['serial'] }}</td>
                                            <td class="{{ $tdClasses }}">
                                                @if($asset['status'] === 'Available')
                                                    <span class="text-gray-400">{{ $asset['assigned_to'] }}</span>
                                                @else
                                                    {{ $asset['assigned_to'] }}
                                                @endif
                                            </td>
                                            <td class="{{ $tdClasses }}">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset['status_class'] }}">{{ $asset['status'] }}</span>
                                            </td>
                                            <td class="{{ $tdClasses }}">
                                                @if($asset['action'] === 'end')
                                                    <button type="button" class="text-red-600 hover:text-red-900 end-deployment-btn" data-serial="{{ $asset['serial'] }}">End Deployment</button>
                                                @elseif($asset['action'] === 'assign')
                                                    <button type="button" class="text-blue-600 hover:text-blue-900 assign-btn" data-asset="{{ $asset['name'] }}" data-serial="{{ $asset['serial'] }}">
                                                        @if($asset['status'] === 'Ended')
                                                            Reassign
                                                        @else
                                                            Assign
                                                        @endif
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="deployment-history-section" class="hidden">
                <div class="flex flex-wrap gap-4 mb-4 items-center justify-end">
                    <button id="filter-toggle-btn-history" type="button" class="p-2 text-xs font-semibold rounded bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                        <span class="ml-1">Filter</span>
                    </button>
                </div>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Deployment History</h2>
                <div class="bg-white rounded-lg shadow">
                    <table class="{{ $tableClasses }}" id="deployment-history-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="{{ $thClasses }}">Asset Name</th>
                                <th class="{{ $thClasses }}">Category</th>
                                <th class="{{ $thClasses }}">Serial</th>
                                <th class="{{ $thClasses }}">Assigned To</th>
                                <th class="{{ $thClasses }}">Status</th>
                                <th class="{{ $thClasses }}">Return Date & Time</th>
                            </tr>
                            <tr id="filter-row-history" class="hidden bg-gray-100">
                                <td class="px-6 py-2"><input type="text" id="filter-history-name" placeholder="Filter Name" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                <td class="px-6 py-2"><input type="text" id="filter-history-category" placeholder="Filter Category" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                <td class="px-6 py-2"><input type="text" id="filter-history-serial" placeholder="Filter Serial" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                <td class="px-6 py-2"><input type="text" id="filter-history-assigned" placeholder="Filter Assigned" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                <td class="px-6 py-2"><select id="filter-history-status" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2">
                                    <option value="">All Statuses</option>
                                    <option value="Ended">Ended</option>
                                </select></td>
                                <td class="px-6 py-2"></td>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($deploymentHistory as $asset)
                            <tr>
                                <td class="{{ $tdClasses }}">{{ $asset['name'] }}</td>
                                <td class="{{ $tdClasses }}">{{ $asset['category'] }}</td>
                                <td class="{{ $tdClasses }}">{{ $asset['serial'] }}</td>
                                <td class="{{ $tdClasses }}">{{ $asset['assigned_to'] }}</td>
                                <td class="{{ $tdClasses }}">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $asset['status_class'] }}">{{ $asset['status'] }}</span>
                                </td>
                                <td class="{{ $tdClasses }}">{{ $asset['updated_at'] ? \Carbon\Carbon::parse($asset['updated_at'])->addHours(8)->format('Y-m-d H:i:s') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="hs-assign-asset-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
                <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="flex justify-between items-center py-3 px-4">
                            <h3 class="font-bold text-gray-800">Assign Asset</h3>
                            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#hs-assign-asset-modal">
                                <span class="sr-only">Close</span>
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-4 overflow-y-auto">
                            <form id="assign-asset-form" action="{{ route('admin.deployment.assign') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="assign-user-select" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                                    <select id="assign-user-select" name="assign_user" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select a user</option>
                                        @foreach($users as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" id="assign-asset-name" name="asset_name">
                                <input type="hidden" id="assign-asset-serial" name="asset_serial">
                            </form>
                        </div>
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50" data-hs-overlay="#hs-assign-asset-modal">Cancel</button>
                            <button type="submit" form="assign-asset-form" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700">Assign Asset</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="hs-end-deployment-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
                <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="flex justify-between items-center py-3 px-4">
                            <h3 class="font-bold text-gray-800">End Deployment</h3>
                            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#hs-end-deployment-modal">
                                <span class="sr-only">Close</span>
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-4 overflow-y-auto text-center">
                            <p class="text-gray-800 mb-4">Are you sure you want to end the deployment for this asset?</p>
                            <input type="hidden" id="end-deployment-asset-serial" name="asset_serial">
                        </div>
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50" data-hs-overlay="#hs-end-deployment-modal">Cancel</button>
                            <button id="confirm-end-deployment" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700">End Deployment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function showNotification(message, type = 'info') {
        const notificationArea = document.createElement('div');
        notificationArea.className = `fixed top-4 right-4 p-4 text-sm rounded-lg ${type === 'error' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'}`;
        notificationArea.textContent = message;
        document.body.appendChild(notificationArea);
        setTimeout(() => notificationArea.remove(), 5000);
    }

    document.querySelectorAll('.assign-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('assign-asset-name').value = btn.getAttribute('data-asset');
            document.getElementById('assign-asset-serial').value = btn.getAttribute('data-serial');
            window.HSOverlay.open(document.getElementById('hs-assign-asset-modal'));
        });
    });

    document.querySelectorAll('.end-deployment-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('end-deployment-asset-serial').value = btn.getAttribute('data-serial');
            window.HSOverlay.open(document.getElementById('hs-end-deployment-modal'));
        });
    });

    document.getElementById('confirm-end-deployment').addEventListener('click', function() {
        const serial = document.getElementById('end-deployment-asset-serial').value;
        fetch('{{ route('admin.deployment.end') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ asset_serial: serial })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Deployment ended successfully!', 'success');
                window.HSOverlay.close(document.getElementById('hs-end-deployment-modal'));
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error ending deployment: ' + error.message, 'error');
        });
    });

    document.getElementById('assign-asset-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Asset assigned successfully!', 'success');
                window.HSOverlay.close(document.getElementById('hs-assign-asset-modal'));
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error assigning asset: ' + error.message, 'error');
        });
    });

    document.getElementById('switch-to-deployed').addEventListener('click', function() {
        document.getElementById('deployed-assets-section').classList.remove('hidden');
        document.getElementById('deployment-history-section').classList.add('hidden');
        this.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
        this.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
        document.getElementById('switch-to-history').classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-700');
        document.getElementById('switch-to-history').classList.add('border-gray-300', 'bg-white', 'text-gray-700');
    });

    document.getElementById('switch-to-history').addEventListener('click', function() {
        document.getElementById('deployed-assets-section').classList.add('hidden');
        document.getElementById('deployment-history-section').classList.remove('hidden');
        this.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
        this.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
        document.getElementById('switch-to-deployed').classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-700');
        document.getElementById('switch-to-deployed').classList.add('border-gray-300', 'bg-white', 'text-gray-700');
    });

    const filterToggleBtnDeployed = document.getElementById('filter-toggle-btn-deployed');
    const filterRowDeployed = document.getElementById('filter-row-deployed');
    filterToggleBtnDeployed.addEventListener('click', function() {
        filterRowDeployed.classList.toggle('hidden');
    });

    const filterToggleBtnHistory = document.getElementById('filter-toggle-btn-history');
    const filterRowHistory = document.getElementById('filter-row-history');
    filterToggleBtnHistory.addEventListener('click', function() {
        filterRowHistory.classList.toggle('hidden');
    });

    function filterDeployedTable() {
        const name = document.getElementById('filter-deployed-name').value.toLowerCase();
        const category = document.getElementById('filter-deployed-category').value.toLowerCase();
        const serial = document.getElementById('filter-deployed-serial').value.toLowerCase();
        const assigned = document.getElementById('filter-deployed-assigned').value.toLowerCase();
        const status = document.getElementById('filter-deployed-status').value;
        const table = document.getElementById('deployed-assets-table');
        Array.from(table.tBodies[0].rows).forEach(row => {
            const cells = row.cells;
            let show = true;
            if (name && !cells[0].textContent.toLowerCase().includes(name)) show = false;
            if (category && !cells[1].textContent.toLowerCase().includes(category)) show = false;
            if (serial && !cells[2].textContent.toLowerCase().includes(serial)) show = false;
            if (assigned && !cells[3].textContent.toLowerCase().includes(assigned)) show = false;
            if (status && !cells[4].textContent.includes(status)) show = false;
            row.style.display = show ? '' : 'none';
        });
    }

    [
        'filter-deployed-name',
        'filter-deployed-category',
        'filter-deployed-serial',
        'filter-deployed-assigned',
        'filter-deployed-status'
    ].forEach(id => {
        document.getElementById(id).addEventListener('input', filterDeployedTable);
        if (id.endsWith('status')) document.getElementById(id).addEventListener('change', filterDeployedTable);
    });

    function filterHistoryTable() {
        const name = document.getElementById('filter-history-name').value.toLowerCase();
        const category = document.getElementById('filter-history-category').value.toLowerCase();
        const serial = document.getElementById('filter-history-serial').value.toLowerCase();
        const assigned = document.getElementById('filter-history-assigned').value.toLowerCase();
        const status = document.getElementById('filter-history-status').value;
        const table = document.getElementById('deployment-history-table');
        Array.from(table.tBodies[0].rows).forEach(row => {
            const cells = row.cells;
            let show = true;
            if (name && !cells[0].textContent.toLowerCase().includes(name)) show = false;
            if (category && !cells[1].textContent.toLowerCase().includes(category)) show = false;
            if (serial && !cells[2].textContent.toLowerCase().includes(serial)) show = false;
            if (assigned && !cells[3].textContent.toLowerCase().includes(assigned)) show = false;
            if (status && !cells[4].textContent.includes(status)) show = false;
            row.style.display = show ? '' : 'none';
        });
    }

    [
        'filter-history-name',
        'filter-history-category',
        'filter-history-serial',
        'filter-history-assigned',
        'filter-history-status'
    ].forEach(id => {
        document.getElementById(id).addEventListener('input', filterHistoryTable);
        if (id.endsWith('status')) document.getElementById(id).addEventListener('change', filterHistoryTable);
    });
});
</script>