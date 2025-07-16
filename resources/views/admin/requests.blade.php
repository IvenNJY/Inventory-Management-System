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
                <h1 class="text-2xl font-semibold text-gray-800">Asset Requests</h1>
            </div>
            <div class="flex flex-col w-auto">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 inline-block align-middle min-w-full">
                        <div class="shadow-md rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200" id="requests-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model / Serial No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                    <tr id="filter-row-requests" class="hidden bg-gray-100">
                                        <td class="px-6 py-2"><input type="text" id="filter-requestor" placeholder="Filter Requestor" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                        <td class="px-6 py-2"><input type="text" id="filter-asset-type" placeholder="Filter Type" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                        <td class="px-6 py-2"><input type="text" id="filter-model-sn" placeholder="Filter Model/SN" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                        <td class="px-6 py-2"><input type="text" id="filter-reason" placeholder="Filter Reason" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                        <td class="px-6 py-2"><input type="text" id="filter-date" placeholder="YYYY-MM-DD" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                        <td class="px-6 py-2"><select id="filter-status" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2">
                                            <option value="">All Statuses</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select></td>
                                        <td class="px-6 py-2"></td>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                    $statusClasses = [
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Approved' => 'bg-green-100 text-green-800',
                                        'Rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    @endphp
                                    @foreach($requests as $req)
                                    <tr data-id="{{ $req->id }}">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $req->user->name }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $req->type }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $req->model_serial_num }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                            @if(strlen($req->reason) > 30)
                                                {{ substr($req->reason, 0, 30) }}...
                                                <button type="button" class="text-blue-600 underline read-more-btn" data-reason="{{ $req->reason }}">Read More</button>
                                            @else
                                                {{ $req->reason }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $req->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$req->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $req->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            @if($req->status === 'Pending')
                                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-600 hover:text-green-800 approve-btn" data-id="{{ $req->id }}" data-requestor="{{ $req->user->name }}" data-modelsn="{{ $req->model_serial_num }}">Approve</button>
                                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 reject-btn" data-id="{{ $req->id }}" data-requestor="{{ $req->user->name }}" data-modelsn="{{ $req->model_serial_num }}">Reject</button>
                                            @else
                                            <span class="text-gray-400">-</span>
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
    </div>
</div>
{{-- Approve Modal --}}
<div id="approve-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex justify-between items-center py-3 px-4">
                <h3 class="font-bold text-gray-800">Approve Request</h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#approve-modal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gray-800 mb-4">Are you sure you want to approve this request for <span id="approve-requestor" class="font-semibold"></span> (<span id="approve-modelsn"></span>)?</p>
                <p class="text-sm text-gray-600 mb-2">If there is anyone already assigned with this asset, their deployment will be ended automatically.</p>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 ">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50" data-hs-overlay="#approve-modal">Cancel</button>
                <button id="confirm-approve" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700">Approve</button>
            </div>
        </div>
    </div>
</div>
{{-- Reject Modal --}}
<div id="reject-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex justify-between items-center py-3 px-4 ">
                <h3 class="font-bold text-gray-800">Reject Request</h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#reject-modal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gray-800 mb-4">Are you sure you want to reject this request for <span id="reject-requestor" class="font-semibold"></span> (<span id="reject-modelsn"></span>)?</p>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 ">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50" data-hs-overlay="#reject-modal">Cancel</button>
                <button id="confirm-reject" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700">Reject</button>
            </div>
        </div>
    </div>
</div>

{{-- Reason Modal --}}
<div id="reason-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex justify-between items-center py-3 px-4">
                <h3 class="font-bold text-gray-800">Full Reason</h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#reason-modal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p id="full-reason-text" class="text-gray-800"></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggleButton = document.createElement('button');
    filterToggleButton.id = 'filter-toggle-btn-requests';
    filterToggleButton.type = 'button';
    filterToggleButton.className = 'p-2 text-xs font-semibold rounded bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 flex items-center mb-4';
    filterToggleButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg><span class="ml-1">Filter</span>`;
    const table = document.getElementById('requests-table');
    table.parentNode.parentNode.insertBefore(filterToggleButton, table.parentNode);
    const filterRow = document.getElementById('filter-row-requests');
    filterToggleButton.addEventListener('click', function() {
        filterRow.classList.toggle('hidden');
    });
    function filterRequestsTable() {
        const requestor = document.getElementById('filter-requestor').value.toLowerCase();
        const type = document.getElementById('filter-asset-type').value.toLowerCase();
        const model_sn = document.getElementById('filter-model-sn').value.toLowerCase();
        const reason = document.getElementById('filter-reason').value.toLowerCase();
        const date = document.getElementById('filter-date').value;
        const status = document.getElementById('filter-status').value;
        Array.from(table.tBodies[0].rows).forEach(row => {
            const cells = row.cells;
            let show = true;
            if (requestor && !cells[0].textContent.toLowerCase().includes(requestor)) show = false;
            if (type && !cells[1].textContent.toLowerCase().includes(type)) show = false;
            if (model_sn && !cells[2].textContent.toLowerCase().includes(model_sn)) show = false;
            if (reason && !cells[3].textContent.toLowerCase().includes(reason)) show = false;
            if (date && !cells[4].textContent.startsWith(date)) show = false;
            if (status && cells[5].textContent.trim() !== status) show = false;
            row.style.display = show ? '' : 'none';
        });
    }
    ['filter-requestor', 'filter-asset-type', 'filter-model-sn', 'filter-reason', 'filter-date', 'filter-status'].forEach(id => {
        document.getElementById(id).addEventListener('input', filterRequestsTable);
        if (id.endsWith('status')) document.getElementById(id).addEventListener('change', filterRequestsTable);
    });
    // Log table data for debugging
    console.log('Table Rows:', Array.from(document.querySelectorAll('#requests-table tbody tr')).map(row => row.getAttribute('data-id')));
    // Approve/Reject modal logic
    document.querySelectorAll('.approve-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = btn.getAttribute('data-id');
            console.log('Approve button clicked for ID:', id);
            document.getElementById('approve-requestor').textContent = btn.getAttribute('data-requestor');
            document.getElementById('approve-modelsn').textContent = btn.getAttribute('data-modelsn');
            document.getElementById('confirm-approve').setAttribute('data-id', id);
            window.HSOverlay.open(document.getElementById('approve-modal'));
        });
    });
    document.querySelectorAll('.reject-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = btn.getAttribute('data-id');
            console.log('Reject button clicked for ID:', id);
            document.getElementById('reject-requestor').textContent = btn.getAttribute('data-requestor');
            document.getElementById('reject-modelsn').textContent = btn.getAttribute('data-modelsn');
            document.getElementById('confirm-reject').setAttribute('data-id', id);
            window.HSOverlay.open(document.getElementById('reject-modal'));
        });
    });
    document.querySelectorAll('.read-more-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('full-reason-text').textContent = btn.getAttribute('data-reason');
            window.HSOverlay.open(document.getElementById('reason-modal'));
        });
    });
    document.getElementById('confirm-approve').addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        console.log('Sending approve request for ID:', id);
        fetch('{{ url("/admin/asset-requests/approve") }}/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => {
            console.log('Response Status:', response.status);
            console.log('Response Headers:', response.headers.get('content-type'));
            return response.text();
        })
        .then(text => {
            console.log('Raw Response:', text);
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    row.querySelector('td:nth-child(6) span').textContent = 'Approved';
                    row.querySelector('td:nth-child(6) span').className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                    row.querySelector('td:last-child').innerHTML = '<span class="text-gray-400">-</span>';
                    window.HSOverlay.close(document.getElementById('approve-modal'));
                } else {
                    alert('Error approving request: ' + data.message);
                    console.error('Error data:', data);
                }
            } catch (e) {
                alert('Error parsing response: ' + e.message);
                console.error('Parse Error:', e, 'Raw Response:', text);
            }
        })
        .catch(error => {
            alert('Error approving request: ' + error);
            console.error('Fetch error:', error);
        });
    });
    document.getElementById('confirm-reject').addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        console.log('Sending reject request for ID:', id);
        fetch('{{ url("/admin/asset-requests/reject") }}/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => {
            console.log('Response Status:', response.status);
            console.log('Response Headers:', response.headers.get('content-type'));
            return response.text();
        })
        .then(text => {
            console.log('Raw Response:', text);
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    row.querySelector('td:nth-child(6) span').textContent = 'Rejected';
                    row.querySelector('td:nth-child(6) span').className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                    row.querySelector('td:last-child').innerHTML = '<span class="text-gray-400">-</span>';
                    window.HSOverlay.close(document.getElementById('reject-modal'));
                } else {
                    alert('Error rejecting request: ' + data.message);
                    console.error('Error data:', data);
                }
            } catch (e) {
                alert('Error parsing response: ' + e.message);
                console.error('Parse Error:', e, 'Raw Response:', text);
            }
        })
        .catch(error => {
            alert('Error rejecting request: ' + error);
            console.error('Fetch error:', error);
        });
    });
});
</script>