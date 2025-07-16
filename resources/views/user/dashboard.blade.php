<head>
    <title>User Dashboard</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

@php
    $pendingRequests = \App\Models\RequestAsset::where('user_id', $userId)->where('status', 'Pending')->count();
@endphp
<div class="flex min-h-screen">
    {{-- User Navbar --}}
    @include('user.navbar')
    <div class="flex-1 bg-gray-100 p-6 lg:p-10">
        <div class="flex gap-8 h-full">
            <!-- My Assets (66%) -->
            <div class="w-2/3 h-full flex flex-col">
                <!-- Status Bar -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Pending Requests Card -->
                    <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="p-3 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                    <path d="M12 8v4l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                </svg>
                            </span>
                            <p class="text-md font-semibold text-gray-800 m-0">Pending Requests</p>
                        </div>
                        <div class="text-3xl font-bold ml-4">{{ $pendingRequests ?? 0 }}</div>
                    </div>
                    <!-- Active Assets Card -->
                    <div class="bg-white p-6 rounded-lg shadow flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="p-3 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="5 13 9 17 19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                </svg>
                            </span>
                            <p class="text-md font-semibold text-gray-800 m-0">Active Assets</p>
                        </div>
                        <div class="text-3xl font-bold ml-4">{{ count($userAssets) }}</div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow flex-1 flex flex-col">
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-800">My Assets</h1>
                        <div class="flex gap-2 items-center">
                            <button id="open-request-modal-btn" class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Request New Asset</button>
                        </div>
                    </div>

                    <div class="flex flex-col w-auto flex-1">
                        <div class="-m-1.5 overflow-x-auto h-full flex-1">
                            <div class="p-1.5 inline-block align-top min-w-full h-full flex-1">
                                <div class="shadow-md rounded-lg overflow-hidden h-full flex flex-col">
                                    <table class="w-full table-fixed divide-y divide-gray-200 h-full" id="user-assets-table">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model / Serial No.</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 h-full flex-1">
                                            @php
                                                $statusClasses = [
                                                    'Deployed' => 'bg-green-100 text-green-800',
                                                    'Ended' => 'bg-gray-100 text-gray-800',
                                                    'Available' => 'bg-blue-100 text-blue-800',
                                                ];
                                                $pendingAssetRequests = \App\Models\RequestAsset::where('user_id', $userId)->where('status', 'Pending')->get();
                                            @endphp
                                            @if(count($userAssets) === 0 && count($pendingAssetRequests) === 0)
                                                <tr>
                                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-lg">No assets in use.</td>
                                                </tr>
                                            @else
                                                @foreach($userAssets as $asset)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-300 h-12">
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm font-medium text-gray-900 h-12 text-center">{{ $asset['name'] }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 h-12 text-center">{{ $asset['type'] }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 h-12 text-center">{{ $asset['model_sn'] }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 h-12 text-center">{{ $asset['assigned_to'] }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap h-12 align-middle">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$asset['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                                                {{ $asset['status'] }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-2 whitespace-nowrap h-12 align-middle">
                                                            @if($asset['can_return'])
                                                                <button class="py-1 px-2 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 return-btn" 
                                                                        data-asset="{{ $asset['name'] }}" 
                                                                        data-modelsn="{{ $asset['model_sn'] }}"
                                                                        data-assetid="{{ $asset['asset_id'] }}"
                                                                        data-serialnum="{{ $asset['serial_num'] }}">Return</button>
                                                            @else
                                                                <span class="text-gray-400 text-xs">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                {{-- Show pending asset requests (no return button) --}}
                                                @foreach($pendingAssetRequests as $request)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-300 h-12">
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm font-medium text-gray-900 h-12 text-center">{{ $request->asset_name }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 h-12 text-center">{{ $request->type }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 h-12 text-center">{{ $request->model_serial_num }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 h-12 text-center">{{ $request->assigned_to ?? '-' }}</td>
                                                        <td class="px-2 py-2 whitespace-nowrap h-12 align-middle">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                        </td>
                                                        <td class="px-2 py-2 whitespace-nowrap h-12 align-middle">
                                                            <span class="text-gray-400 text-xs">N/A</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Asset Return Notifications (33%) -->
            <div class="w-1/3 h-full flex-1 flex-col">
                <div class="bg-white w-full p-6 rounded-lg shadow mt-0 flex-1 flex flex-col h-full">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Asset Return Notice</h2>
                    <div class="flex flex-col gap-4" id="return-notifications-list" style="max-height: 70vh; overflow-y: auto;">
                        @php
                            $page = request()->get('return_page', 1);
                            $perPage = 5;
                            $total = count($returnNotifications);
                            $start = ($page - 1) * $perPage;
                            $pagedNotifications = array_slice($returnNotifications, $start, $perPage);
                        @endphp
                        @forelse($pagedNotifications as $notification)
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 max-w-md w-full mx-auto">
                                <div class="font-semibold text-red-700 mb-2">Please Return</div>
                                <div class="mb-1"><span class="font-medium text-gray-700">Asset:</span> {{ $notification['asset'] }}</div>
                                <div class="mb-1"><span class="font-medium text-gray-700">Model:</span> {{ $notification['model_sn'] }}</div>
                                <div class="text-gray-600 text-sm mt-2">As it has already been reassigned.</div>
                            </div>
                        @empty
                            <div class="border border-gray-200 rounded-lg p-6 text-center text-gray-500 max-w-md w-full mx-auto">No recent asset return notifications.</div>
                        @endforelse
                        @if($total > $perPage)
                        <div class="flex justify-center mt-4 gap-2">
                            @php $lastPage = ceil($total / $perPage); @endphp
                            <form method="GET" action="" class="inline">
                                <input type="hidden" name="return_page" value="{{ max(1, $page-1) }}">
                                <button type="submit" class="px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" @if($page <= 1) disabled @endif>Prev</button>
                            </form>
                            <span class="px-2 py-1 text-gray-600">Page {{ $page }} of {{ $lastPage }}</span>
                            <form method="GET" action="" class="inline">
                                <input type="hidden" name="return_page" value="{{ min($lastPage, $page+1) }}">
                                <button type="submit" class="px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" @if($page >= $lastPage) disabled @endif>Next</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        <!-- Request New Asset Modal -->
        <div id="request-asset-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 hidden flex items-center justify-center size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center py-3 px-4">
                        <h3 class="font-bold text-gray-800">Request New Asset</h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#request-asset-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <!-- Error and Success Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger" style="color: red;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" style="color: green;">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form id="request-asset-form" method="POST" action="{{ route('user.request-asset') }}" onsubmit="setAssetNameAndModelSerial()">
                            @csrf
                            <div class="mb-4">
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <select id="type" name="type" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="" selected>All Types</option>
                                    @foreach(array_unique(array_column($availableAssets, 'type')) as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-2">Select Asset</label>
                                <select id="asset_id" name="asset_id" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="" disabled selected>Select an asset</option>
                                    @foreach($availableAssets as $a)
                                        <option value="{{ $a['id'] }}" data-type="{{ $a['type'] }}" data-name="{{ $a['name'] }}" data-model-serial="{{ $a['model'] }} / {{ $a['serial'] }}">{{ $a['name'] }} ({{ $a['type'] }} - {{ $a['model'] }} / {{ $a['serial'] }})</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="asset_name" name="asset_name" value="" required>
                                <input type="hidden" id="model_serial_num" name="model_serial_num" value="" required>
                                <input type="hidden" id="assigned_date" name="assigned_date" value="{{ date('Y-m-d') }}">
                                <input type="hidden" id="status" name="status" value="Pending">
                                <input type="hidden" id="user_id" name="user_id" value="{{ $userId }}">
                            </div>
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                                <textarea id="reason" name="reason" rows="2" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- Return Asset Modal -->
        <div id="return-asset-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 hidden flex items-center justify-center size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center py-3 px-4">
                        <h3 class="font-bold text-gray-800">Confirm Asset Return</h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#return-asset-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <p class="mb-4 text-gray-700">Are you sure you want to return <span id="return-asset-name" class="font-semibold"></span> (<span id="return-asset-modelsn"></span>)?</p>
                        <div class="flex justify-end gap-x-2">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50" data-hs-overlay="#return-asset-modal">Cancel</button>
                            <form id="confirm-return-form" method="POST" action="{{ route('user.return-asset') }}">
                                @csrf
                                <input type="hidden" name="asset_id" id="confirm-return-asset-id">
                                <input type="hidden" name="serial_num" id="confirm-return-serial-num">
                                <button type="submit" class="py-2 px-3 inline-flex items-centers gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700">Confirm Return</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Return Asset Modal -->
    </div>
</div>
<script src="../../node_modules/preline/dist/preline.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Request New Asset Modal logic
    const openRequestModalBtn = document.getElementById('open-request-modal-btn');
    const requestAssetModal = document.getElementById('request-asset-modal');
    openRequestModalBtn.addEventListener('click', function() {
        HSOverlay.open(requestAssetModal);
    });

    // Return button logic (modal)
    const returnModal = document.getElementById('return-asset-modal');
    document.querySelectorAll('.return-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('return-asset-name').textContent = btn.getAttribute('data-asset');
            document.getElementById('return-asset-modelsn').textContent = btn.getAttribute('data-modelsn');
            document.getElementById('confirm-return-asset-id').value = btn.getAttribute('data-assetid');
            document.getElementById('confirm-return-serial-num').value = btn.getAttribute('data-serialnum');
            HSOverlay.open(returnModal);
        });
    });

    // Filter asset select by type (inside modal)
    const typeSelect = document.getElementById('type');
    const assetSelect = document.getElementById('asset_id');
    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        Array.from(assetSelect.options).forEach(option => {
            if (!option.value) return; // skip placeholder
            if (!selectedType || option.getAttribute('data-type') === selectedType) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        assetSelect.value = '';
    });

    // Set asset name and model serial on form submit
    window.setAssetNameAndModelSerial = function() {
        var assetSelect = document.getElementById('asset_id');
        var assetNameInput = document.getElementById('asset_name');
        var modelSerialInput = document.getElementById('model_serial_num');
        var selected = assetSelect.options[assetSelect.selectedIndex];
        assetNameInput.value = selected ? (selected.getAttribute('data-name') || '') : '';
        modelSerialInput.value = selected ? (selected.getAttribute('data-model-serial') || '') : '';
    }
    document.getElementById('asset_id').addEventListener('change', window.setAssetNameAndModelSerial);
    window.setAssetNameAndModelSerial();
});
</script>