<head>
    <title>Asset Management</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    <script src="../../node_modules/preline/dist/preline.js"></script>
</head>

<div class="flex min-h-screen"> {{-- Flex container for navbar and content --}}
    <div class="w-70 flex-shrink-0"> {{-- Fixed width sidebar --}}
        @include('admin.navbar')
    </div>
    <div class="flex-1 bg-gray-100 p-4 sm:p-6 lg:p-8 w-[80vw] "> {{-- Main content area --}}
        <div class="bg-white p-6 rounded-lg shadow"> {{-- Main content wrapper --}}
        
        {{-- Page Title and Create New Asset Button --}}
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">Asset Management</h1>

 
            
            <form id="bulk-delete-form" method="POST" action="{{ route('admin.assets.bulk-delete') }}" class="flex gap-x-2 items-center">
                @csrf
                <button type="button" id="import-assets-btn" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-400 bg-white text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-import-assets-modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Import from Excel
                </button>
                <button type="submit" class="py-3 px-4 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 ml-2" id="bulk-delete-btn" style="display:none;">Delete Selected</button>
                <a href="./add-assets" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    Create New Asset
                </a>
            </form>
        </div>
               {{-- Status Alert --}}
        @if(session('status'))
            @php $status = session('status'); @endphp
            <div class="mb-4">
                <div class="rounded-lg px-4 py-3 text-sm font-medium {{ isset($status['success']) && $status['success'] ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                    {{ $status['message'] ?? $status }}
                </div>
            </div>
        @elseif(session('success'))
            <div class="mb-4">
                <div class="rounded-lg px-4 py-3 text-sm font-medium bg-green-100 text-green-800 border border-green-300">
                    {{ session('success') }}
                </div>
            </div>
        @elseif(session('error'))
            <div class="mb-4">
                <div class="rounded-lg px-4 py-3 text-sm font-medium bg-red-100 text-red-800 border border-red-300">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Assets Table --}}
        <div class="flex flex-col w-auto">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 inline-block align-middle">
                    <div class="shadow rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg" id="devices-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="select-all-assets" class="form-checkbox h-4 w-4 text-blue-600">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Tag</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warranty End Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Lifespan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                    <td></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_name" placeholder="Filter Name" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_type" placeholder="Filter Type" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_serial" placeholder="Filter Serial" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_model" placeholder="Filter Model" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_asset_tag" placeholder="Filter Asset Tag" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_purchase_date" placeholder="YYYY-MM-DD" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_warranty_end_date" placeholder="YYYY-MM-DD" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2"><input type="text" name="filter_lifespan" placeholder="Filter Lifespan" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input" /></td>
                                    <td class="px-6 py-2">
                                        <select name="filter_status" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2 filter-input">
                                            <option value="">All Status</option>
                                            <option value="Active">Active</option>
                                            <option value="In Repair">In Repair</option>
                                            <option value="Retired">Retired</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-2 text-right"></td>
                                </tr>
                            </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $statusClasses = [
                                            'Active' => 'bg-green-100 text-green-800',
                                            'In Repair' => 'bg-yellow-100 text-yellow-800',
                                            'Retired' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    @foreach($assets as $device)
                                    <tr>
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="selected_assets[]" value="{{ $device->id }}" class="form-checkbox h-4 w-4 text-blue-600 asset-checkbox" form="bulk-delete-form">
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $device->asset_name }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->type }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->serial_number }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->model }}</td>
                                        {{-- Asset Tag column: display value only --}}
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->asset_tag }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->purchase_date }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->warranty_end_date }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $device->expected_lifespan }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$device->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $device->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.edit-asset', $device->id) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">Edit</a>
                                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 disabled:opacity-50 disabled:pointer-events-none open-delete-modal" data-asset-id="{{ $device->id }}" data-hs-overlay="#hs-delete-asset-modal">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                                </table>
                            <!-- end table, no form here -->
                    </div>
                </div>
            </div>
        </div>
        {{-- End Assets Table --}}

        {{-- Pagination and Row Limiter --}}
        <div class="flex items-center justify-between py-4 px-1 w-full">
            <div>
                <form method="GET" action="" class="inline-block">
                    <select name="per_page" onchange="this.form.submit()" class="py-1.5 px-3 pe-9 block w-auto bg-white border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="All" {{ request('per_page') == 'All' ? 'selected' : '' }}>All</option>
                    </select>
                </form>
            </div>
            <div class="flex items-center space-x-2">
                @if($assets->lastPage() > 1)
                <div class="pagination flex items-center">
                    {{-- Custom Pagination --}}
                    <nav class="inline-flex -space-x-px" aria-label="Pagination">
                        {{-- Previous Page Link --}}
                        @if ($assets->onFirstPage())
                            <span class="px-3 py-1 rounded-l border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $assets->previousPageUrl() }}" class="px-3 py-1 rounded-l border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">Previous</a>
                        @endif
                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $assets->lastPage(); $i++)
                            @if ($i == $assets->currentPage())
                                <span class="px-3 py-1 border border-gray-300 bg-blue-600 text-white">{{ $i }}</span>
                            @else
                                <a href="{{ $assets->url($i) }}" class="px-3 py-1 border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">{{ $i }}</a>
                            @endif
                        @endfor
                        {{-- Next Page Link --}}
                        @if ($assets->hasMorePages())
                            <a href="{{ $assets->nextPageUrl() }}" class="px-3 py-1 rounded-r border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">Next</a>
                        @else
                            <span class="px-3 py-1 rounded-r border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
                        @endif
                    </nav>
                </div>
                @endif
                <div class="text-sm text-gray-700 ml-2">
                    @if($assets->total() > 0)
                        Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} entries
                    @else
                        No entries found
                    @endif
                </div>
            </div>
        </div>
        {{-- End Pagination and Row Limiter --}}

        {{-- Delete Asset Modal --}}
        <div id="hs-delete-asset-modal" class="hs-overlay hidden size-full hs-overlay-backdrop-open:bg-gray-500/50 fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto flex items-center justify-center">
            <div class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div class="relative flex flex-col justify-center items-center  bg-white shadow-sm rounded-xl">
                    <div class="absolute top-2 end-2">
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-delete-asset-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
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
                            Delete Asset
                        </h3>
                        <p class="text-gray-500">
                            Are you sure you want to delete this asset? This action cannot be undone.
                        </p>
                        <form id="delete-asset-form" method="POST" action="" class="mt-6 flex justify-center gap-x-4">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-delete-asset-modal">
                                Cancel
                            </button>
                            <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-500 text-white hover:bg-red-600 disabled:opacity-50 disabled:pointer-events-none">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Delete Asset Modal --}}

        {{-- Import Assets Modal --}}
        <div id="hs-import-assets-modal" class="hs-overlay hs-overlay-backdrop-open:bg-gray-900/25 flex items-center justify-center hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center py-3 px-4">
                        <h3 class="font-bold text-gray-800">Import Assets from Excel/CSV</h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-import-assets-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <div class="mb-4 text-center">
                            <p class="mb-4">Please follow the format of the sample CSV to assure data is uploaded correctly.</p>
                            <a href="{{ asset('sample_assets.csv') }}" download class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 bg-gray-50 text-gray-800 hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4"/></svg>
                                Download Sample CSV
                            </a>
                        </div>
                        <form id="import-assets-form" action="{{ route('admin.assets.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-y-4">
                            @csrf
                            <label for="import_assets_file" class="block w-full text-center cursor-pointer border-2 border-dashed border-gray-300 rounded-lg py-8 px-4 hover:border-blue-400 transition">
                                <span class="block text-gray-600 mb-2">Drop your CSV/XLSX file here or click to choose</span>
                                <span id="import-assets-file-name" class="block text-xs text-gray-500">No files Chosen</span>
                                <input type="file" name="import_file" id="import_assets_file" accept=".csv, .xlsx" class="hidden" required>
                            </label>
                            <button type="submit" class="py-2 px-4 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">Import Assets</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Import Assets Modal --}}

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attach delete button listeners (no event delegation, original approach)
    document.querySelectorAll('.open-delete-modal').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var assetId = btn.getAttribute('data-asset-id');
            var form = document.getElementById('delete-asset-form');
            form.action = "{{ route('admin.delete-asset', ':id') }}".replace(':id', assetId);
            document.getElementById('hs-delete-asset-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });
    // Modal close logic for X and Cancel buttons
    document.querySelectorAll('[data-hs-overlay="#hs-delete-asset-modal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('hs-delete-asset-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });

    // Filter toggle button logic
    var filterBtn = document.getElementById('filter-toggle-btn');
    var filterRow = document.getElementById('filter-row');
    if (filterBtn && filterRow) {
        filterBtn.addEventListener('click', function() {
            filterRow.classList.toggle('hidden');
        });
    }

    // Select all assets checkbox logic
    var selectAll = document.getElementById('select-all-assets');
    var assetCheckboxes = document.querySelectorAll('.asset-checkbox');
    var bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    function updateBulkDeleteBtn() {
        var checked = Array.from(assetCheckboxes).some(cb => cb.checked);
        bulkDeleteBtn.style.display = checked ? 'inline-block' : 'none';
    }
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            assetCheckboxes.forEach(function(cb) {
                cb.checked = selectAll.checked;
            });
            updateBulkDeleteBtn();
        });
    }
    assetCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', updateBulkDeleteBtn);
    });
    updateBulkDeleteBtn();


    // Show selected file name for import assets
    const importAssetsFileInput = document.getElementById('import_assets_file');
    const importAssetsFileNameSpan = document.getElementById('import-assets-file-name');
    if (importAssetsFileInput && importAssetsFileNameSpan) {
        importAssetsFileInput.addEventListener('change', function() {
            importAssetsFileNameSpan.textContent = importAssetsFileInput.files.length > 0 ? importAssetsFileInput.files[0].name : '';
        });
    }
    // Client-side instant filter logic
    function filterTableRows() {
        var filterValues = {};
        document.querySelectorAll('.filter-input').forEach(function(input) {
            filterValues[input.name] = input.value.trim().toLowerCase();
        });
        var rows = document.querySelectorAll('#devices-table tbody tr');
        rows.forEach(function(row) {
            var show = true;
            var cells = row.querySelectorAll('td');
            // Map: [name, type, serial, model, asset_tag, purchase_date, warranty_end_date, lifespan, status]
            var cellMap = {
                filter_name: cells[1]?.textContent.trim().toLowerCase(),
                filter_type: cells[2]?.textContent.trim().toLowerCase(),
                filter_serial: cells[3]?.textContent.trim().toLowerCase(),
                filter_model: cells[4]?.textContent.trim().toLowerCase(),
                filter_asset_tag: cells[5]?.textContent.trim().toLowerCase(),
                filter_purchase_date: cells[6]?.textContent.trim().toLowerCase(),
                filter_warranty_end_date: cells[7]?.textContent.trim().toLowerCase(),
                filter_lifespan: cells[8]?.textContent.trim().toLowerCase(),
                filter_status: cells[9]?.textContent.trim().toLowerCase(),
            };
            for (var key in filterValues) {
                if (filterValues[key] && cellMap[key] !== undefined) {
                    if (key === 'filter_status') {
                        // For select, match exact
                        if (filterValues[key] && cellMap[key] !== filterValues[key]) {
                            show = false;
                            break;
                        }
                    } else {
                        if (!cellMap[key].includes(filterValues[key])) {
                            show = false;
                            break;
                        }
                    }
                }
            }
            row.style.display = show ? '' : 'none';
        });
    }
    document.querySelectorAll('.filter-input').forEach(function(input) {
        input.addEventListener('input', filterTableRows);
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', filterTableRows);
        }
    });
    // Bulk delete confirmation modal logic
    var bulkDeleteForm = document.getElementById('bulk-delete-form');
    var bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    if (bulkDeleteForm && bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showConfirmationModal(
                'Confirmation',
                'Are you sure you want to proceed?',
                function() {
                    bulkDeleteForm.submit();
                },
                'confirmation'
            );
        });
    }
});
</script>