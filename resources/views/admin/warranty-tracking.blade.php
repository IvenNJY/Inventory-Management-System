<head>
    <title>Warranty Tracking</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    <script src="../../node_modules/preline/dist/preline.js"></script>
    <style>
        .sticky-col {
            position: -webkit-sticky;
            position: sticky;
            right: 0;
            background-color: white; /* Or match your table row background */
            z-index: 10;
        }
        .sticky-col-status {
            right: 0px; /* Adjust based on the width of your actions column */
        }
        .sticky-header {
            z-index: 20; /* Ensure header is above body cells */
            background-color: #f9fafb; /* bg-gray-50 */
        }
        /* Ensure the action column is to the right of status */
        th.sticky-col:last-child,
        td.sticky-col:last-child {
            right: 0;
        }
        th.sticky-col-status {
            right: 100px; /* Approximate width of the 'Actions' column, adjust as needed */
        }
        td.sticky-col-status {
            right: 100px; /* Approximate width of the 'Actions' column, adjust as needed */
        }
    </style>
</head>

<div class="flex h-screen overflow-hidden"> {{-- Flex container for navbar and content --}}
    <div class="w-70 flex-shrink-0"> {{-- Fixed width sidebar --}}
        @include('admin.navbar')
    </div>
    <div class="flex-1 bg-gray-100 p-4 sm:p-6 lg:p-8 flex flex-col w-[70vw] "> {{-- Main content area --}}
        <div class="mb-6 flex justify-between items-center flex-shrink-0"> {{-- Title section --}}
            <h1 class="text-2xl font-semibold text-gray-800">Warranty Tracking</h1>
        </div>

        {{-- Main scrollable content area --}}
        <div class="flex flex-col gap-6 flex-grow  overflow-y-auto">
            
            {{-- Top Row: Cards and Table --}}
            <div class="flex flex-col lg:flex-row gap-6 flex-shrink-0 ">
                {{-- Status Cards Container --}}
                <div class="lg:w-auto bg-white p-6 rounded-lg shadow self-start"> {{-- Changed lg:w-1/3 to lg:w-auto and added self-start --}}
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Warranty Status Overview</h2>
                    @php
                        $expiredCount = 0;
                        $expiringSoonCount = 0;
                        $activeWarrantyCount = 0;
                        $today = \Carbon\Carbon::today();
                        foreach ($assets as $asset) {
                            $expiry = $asset->warranty_end_date;
                            $expiryDate = $expiry ? \Carbon\Carbon::parse($expiry) : null;
                            $daysLeft = $expiryDate ? $today->diffInDays($expiryDate, false) : null;
                            if ($daysLeft === null) {
                                // do nothing
                            } elseif ($daysLeft > 60) {
                                $activeWarrantyCount++;
                            } elseif ($daysLeft > 0) {
                                $expiringSoonCount++;
                            } else {
                                $expiredCount++;
                            }
                        }
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4">
                        {{-- Card 1: Expired --}}
                        <div class="bg-red-50 p-4 rounded-lg flex items-center">
                            <div class="bg-red-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-red-700">Expired</p>
                                <p class="text-2xl font-bold text-red-800" id="expiredCount">{{ $expiredCount }}</p>
                            </div>
                        </div>
                        {{-- Card 2: Expiring Soon --}}
                        <div class="bg-yellow-50 p-4 rounded-lg flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-yellow-700" id="expiringSoonLabel">Expiring Soon (60 days)</p>
                                <p class="text-2xl font-bold text-yellow-800" id="expiringSoonCount">{{ $expiringSoonCount }}</p>
                            </div>
                        </div>
                        {{-- Card 3: Active --}}
                        <div class="bg-green-50 p-4 rounded-lg flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-green-700">Active</p>
                                <p class="text-2xl font-bold text-green-800" id="activeWarrantyCount">{{ $activeWarrantyCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Container (replaces chart) --}}
                <div class="flex-1 bg-white p-6 rounded-lg shadow flex flex-col overflow-auto"> {{-- Changed lg:w-2/3 to flex-1 and added overflow-hidden --}}
                    <div class="mb-4 flex justify-between items-center flex-shrink-0">
                        <h2 class="text-xl font-semibold text-gray-700">Assets Nearing Warranty Expiry</h2>
                        <div class="flex items-center gap-2">
                            <label for="expiring-days-select" class="text-xs text-gray-600">Expiring Soon (days):</label>
                            <select id="expiring-days-select" class="py-1 px-2 border border-gray-300 rounded text-xs bg-white">
                                <option value="30">30</option>
                                <option value="60" selected>60</option>
                                <option value="90">90</option>
                                <option value="120">120</option>
                            </select>
                            <button id="filter-toggle-btn-warranty" type="button" class="p-2 text-xs font-semibold rounded bg-white hover:bg-gray-100 text-gray-700 border border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-auto flex-grow "> {{-- Changed overflow-x-auto to overflow-auto --}}
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg" id="warranty-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warranty End Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Remaining</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky-col sticky-col-status sticky-header">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider sticky-col sticky-header">Actions</th>
                                </tr>
                                {{-- Filter Row --}}
                                <tr id="filter-row-warranty" class="hidden bg-gray-100">
                                    <td class="px-4 py-2"><input type="text" id="filter-asset-name-warranty" placeholder="Filter Name" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                    <td class="px-4 py-2"><input type="text" id="filter-serial-warranty" placeholder="Filter Serial" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                    <td class="px-4 py-2"></td> {{-- Warranty End Date filter - placeholder --}}
                                    <td class="px-4 py-2"><input type="text" id="filter-days-remaining-warranty" placeholder="Filter Days" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                    <td class="px-4 py-2 sticky-col sticky-col-status bg-gray-100">
                                        <select id="filter-status-warranty" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2">
                                            <option value="">All Status</option>
                                            <option value="Expired">Expired</option>
                                            <option value="Expiring Soon">Expiring Soon</option>
                                            <option value="Active">Active</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-2 sticky-col bg-gray-100"></td>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($assets) && count($assets))
                                    @foreach($assets as $asset)
                                        @php
                                            $expiry = $asset->warranty_end_date;
                                            $today = \Carbon\Carbon::today();
                                            $expiryDate = $expiry ? \Carbon\Carbon::parse($expiry) : null;
                                            $daysLeft = $expiryDate ? $today->diffInDays($expiryDate, false) : null;
                                            if ($daysLeft !== null && $daysLeft < 0) {
                                                $daysLeft = 0;
                                            }
                                            if ($daysLeft === null) {
                                                $status = 'Unknown';
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                            } elseif ($daysLeft > 60) {
                                                $status = 'Active';
                                                $statusClass = 'bg-green-100 text-green-800';
                                            } elseif ($daysLeft > 0) {
                                                $status = 'Expiring Soon';
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                            } else {
                                                $status = 'Expired';
                                                $statusClass = 'bg-red-100 text-red-800';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $asset->asset_name }}</td>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $asset->serial_number }}</td>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $asset->warranty_end_date }}</td>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $daysLeft !== null ? $daysLeft : 'N/A' }}</td>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm sticky-col sticky-col-status">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium sticky-col">
                                                <a href="{{ route('admin.edit-asset', $asset->id) }}" class="text-blue-600 hover:underline font-medium">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="7" class="text-center py-4 text-gray-500">No assets found.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination and Row Limiter --}}
                    <div class="py-4 px-1 flex justify-between items-center flex-shrink-0">
                        <div>
                            <select id="hs-select-rows-warranty" class="py-1.5 px-3 pe-9 block w-auto bg-white border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-3">
                            <nav class="flex items-center -space-x-px" id="pagination-controls-warranty">
                                {{-- Pagination buttons will be dynamically inserted here --}}
                            </nav>
                            <span id="pagination-info-warranty" class="text-sm text-gray-700"></span>
                        </div>
                    </div>
                </div> {{-- End of new Table Container --}}
            </div>
            {{-- The original Bottom Section for Table is now removed --}}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Expiring Soon days filter
    const expiringDaysSelect = document.getElementById('expiring-days-select');
    let expiringDaysValue = expiringDaysSelect ? parseInt(expiringDaysSelect.value) : 60;
    // Chart.js setup for Warranty Status - REMOVED

    // Table Filtering, Pagination, and Sorting Logic
    const filterToggleButtonWarranty = document.getElementById('filter-toggle-btn-warranty');
    const filterRowWarranty = document.getElementById('filter-row-warranty');
    const filterInputsWarranty = {
        assetName: document.getElementById('filter-asset-name-warranty'),
        serial: document.getElementById('filter-serial-warranty'),
        daysRemaining: document.getElementById('filter-days-remaining-warranty'),
        status: document.getElementById('filter-status-warranty'),
    };
    const tableWarranty = document.getElementById('warranty-table');
    const tableBodyWarranty = tableWarranty?.querySelector('tbody');
    
    if (!tableBodyWarranty) {
        console.error("Warranty table body not found!");
        return;
    }

    let allTableRowsWarranty = Array.from(tableBodyWarranty.querySelectorAll('tr'));

    const rowsPerPageSelectWarranty = document.getElementById('hs-select-rows-warranty');
    const paginationControlsWarranty = document.getElementById('pagination-controls-warranty');
    const paginationInfoWarranty = document.getElementById('pagination-info-warranty');
    let currentPageWarranty = 1;
    let rowsPerPageWarranty = rowsPerPageSelectWarranty ? parseInt(rowsPerPageSelectWarranty.value) : 10;

    // Helper function to get a sortable value for "Days Remaining"
    function getSortableDays(daysTextCell) {
        if (!daysTextCell || !daysTextCell.textContent) return Infinity; // Treat empty or N/A as last
        const text = daysTextCell.textContent.toLowerCase().trim();
        if (text === 'expired') return -1; // Expired comes first
        if (text === 'n/a' || text === '-') return Infinity;
        // Accept just a number as well as "days"
        const daysMatch = text.match(/(\d+)/);
        return daysMatch ? parseInt(daysMatch[1]) : Infinity; // If parsing fails, put it last
    }

    // Default sort by "Days Remaining" (column index 4)
    allTableRowsWarranty.sort((a, b) => {
        const daysA = getSortableDays(a.cells[4]);
        const daysB = getSortableDays(b.cells[4]);
        return daysA - daysB;
    });

    if (filterToggleButtonWarranty) {
        filterToggleButtonWarranty.addEventListener('click', function() {
            filterRowWarranty.classList.toggle('hidden');
        });
    }

    function applyFiltersAndPaginationWarranty() {
        const filters = {
            assetName: filterInputsWarranty.assetName ? filterInputsWarranty.assetName.value.toLowerCase() : '',
            serial: filterInputsWarranty.serial ? filterInputsWarranty.serial.value.toLowerCase() : '',
            daysRemaining: filterInputsWarranty.daysRemaining ? filterInputsWarranty.daysRemaining.value.toLowerCase() : '',
            status: filterInputsWarranty.status ? filterInputsWarranty.status.value : '',
        };

        let filteredRows = allTableRowsWarranty.filter(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 6) return false; // Ensure row has enough cells for status

            let showRow = true;
            if (filters.assetName && !cells[0].textContent.toLowerCase().includes(filters.assetName)) showRow = false;
            if (filters.serial && !cells[1].textContent.toLowerCase().includes(filters.serial)) showRow = false;
            // cells[2] is User, cells[3] is Warranty End Date
            if (filters.daysRemaining && cells[4] && !cells[4].textContent.toLowerCase().includes(filters.daysRemaining)) showRow = false;
            if (filters.status && cells[5] && cells[5].textContent.trim() !== filters.status) showRow = false;

            // If status filter is 'Expiring Soon', filter by days remaining
            if (filters.status === 'Expiring Soon' && cells[4]) {
                const days = getSortableDays(cells[4]);
                if (!(days > 0 && days <= expiringDaysValue)) showRow = false;
            }
            return showRow;
        });

        renderTableRowsWarranty(filteredRows);
        renderPaginationControlsWarranty(filteredRows.length);
    }

    function renderTableRowsWarranty(rowsToRender) {
        tableBodyWarranty.innerHTML = ''; // Clear existing rows
        const totalFilteredRows = rowsToRender.length;
        let paginatedRows;
        let firstEntryNum = 0;
        let lastEntryNum = 0;

        let currentRowsPerPageSetting = rowsPerPageSelectWarranty ? rowsPerPageSelectWarranty.value : '10';
        let numericRowsPerPage = currentRowsPerPageSetting === 'all' ? totalFilteredRows : parseInt(currentRowsPerPageSetting);
        if (isNaN(numericRowsPerPage) || numericRowsPerPage <= 0) numericRowsPerPage = totalFilteredRows;


        if (totalFilteredRows === 0) {
            paginatedRows = [];
        } else if (numericRowsPerPage >= totalFilteredRows) { // Handles 'all' or large number
            paginatedRows = rowsToRender;
            currentPageWarranty = 1; 
            firstEntryNum = totalFilteredRows > 0 ? 1 : 0;
            lastEntryNum = totalFilteredRows;
        } else {
            const start = (currentPageWarranty - 1) * numericRowsPerPage;
            const end = Math.min(start + numericRowsPerPage, totalFilteredRows); 
            paginatedRows = rowsToRender.slice(start, end);
            firstEntryNum = start + 1;
            lastEntryNum = start + paginatedRows.length;
        }

        paginatedRows.forEach(row => {
            tableBodyWarranty.appendChild(row.cloneNode(true));
        });

        if (paginationInfoWarranty) {
            if (totalFilteredRows === 0) {
                paginationInfoWarranty.textContent = 'No entries';
            } else {
                paginationInfoWarranty.textContent = `Showing ${firstEntryNum} to ${lastEntryNum} of ${totalFilteredRows} entries`;
            }
        }
    }
    
    function renderPaginationControlsWarranty(totalFilteredRows) {
        if (!paginationControlsWarranty || !paginationInfoWarranty || !rowsPerPageSelectWarranty) return;

        paginationControlsWarranty.innerHTML = ''; 

        const currentRowsPerPageSetting = rowsPerPageSelectWarranty.value;
        let numericRowsPerPage = (currentRowsPerPageSetting === 'all' || parseInt(currentRowsPerPageSetting) >= totalFilteredRows) 
                               ? totalFilteredRows 
                               : parseInt(currentRowsPerPageSetting);
        if (isNaN(numericRowsPerPage) || numericRowsPerPage <= 0) numericRowsPerPage = totalFilteredRows;


        if (totalFilteredRows === 0) {
            paginationInfoWarranty.textContent = 'No entries found';
            return; 
        }
        
        const totalPages = (numericRowsPerPage > 0 && totalFilteredRows > 0) ? Math.ceil(totalFilteredRows / numericRowsPerPage) : 1;
        
        // Update info text
        const startItem = (currentPageWarranty - 1) * numericRowsPerPage + 1;
        const endItem = Math.min(startItem + numericRowsPerPage - 1, totalFilteredRows);
        paginationInfoWarranty.textContent = `Showing ${startItem} to ${endItem} of ${totalFilteredRows} entries`;

        if (totalPages <= 1) { // No pagination buttons if only one page
            return;
        }

        // Previous Button
        const prevButton = createPaginationButtonWarranty(
            '<svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
            currentPageWarranty > 1 ? currentPageWarranty - 1 : 1,
            currentPageWarranty === 1,
            false, 
            'prev'
        );
        paginationControlsWarranty.appendChild(prevButton);

        // Page Number Buttons (with ellipsis)
        let pagesToShow = [];
        if (totalPages <= 7) { // Show all pages if 7 or less
            for (let i = 1; i <= totalPages; i++) pagesToShow.push(i);
        } else {
            pagesToShow.push(1); 
            if (currentPageWarranty > 3) pagesToShow.push('...');
            for (let i = Math.max(2, currentPageWarranty - 1); i <= Math.min(totalPages - 1, currentPageWarranty + 1); i++) {
                if (!pagesToShow.includes(i)) pagesToShow.push(i);
            }
            if (currentPageWarranty < totalPages - 2) pagesToShow.push('...');
            if (!pagesToShow.includes(totalPages)) pagesToShow.push(totalPages);
        }

        pagesToShow.forEach(pageNum => {
            if (pageNum === '...') {
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.className = 'min-h-[32px] min-w-[32px] py-1.5 px-2.5 inline-flex items-center justify-center text-sm text-gray-500';
                ellipsisSpan.textContent = '...';
                paginationControlsWarranty.appendChild(ellipsisSpan);
            } else {
                const pageButton = createPaginationButtonWarranty(
                    pageNum.toString(), pageNum, false, pageNum === currentPageWarranty, 'page'
                );
                paginationControlsWarranty.appendChild(pageButton);
            }
        });

        // Next Button
        const nextButton = createPaginationButtonWarranty(
            '<svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
            currentPageWarranty < totalPages ? currentPageWarranty + 1 : totalPages,
            currentPageWarranty === totalPages,
            false, 
            'next'
        );
        paginationControlsWarranty.appendChild(nextButton);
    }

    function createPaginationButtonWarranty(htmlContent, page, isDisabled, isCurrentPage = false, type = 'page') {
        const button = document.createElement('button');
        button.type = 'button';
        button.innerHTML = htmlContent;

        let baseClasses = 'min-h-[32px] min-w-[32px] py-1.5 px-2.5 inline-flex items-center justify-center text-sm focus:outline-none disabled:opacity-50 disabled:pointer-events-none';
        let borderClasses = 'border border-gray-300';
        let textClasses = 'text-gray-700';
        let bgClasses = 'bg-white hover:bg-gray-50';
        let roundedClasses = '';

        if (type === 'prev') roundedClasses = 'rounded-s-md';
        else if (type === 'next') roundedClasses = 'rounded-e-md';
        
        if (isCurrentPage) {
            bgClasses = 'bg-gray-200 text-gray-900 hover:bg-gray-200'; 
        }
        
        button.className = `${baseClasses} ${borderClasses} ${textClasses} ${bgClasses} ${roundedClasses}`;
        button.disabled = isDisabled;

        if (!isDisabled) {
            button.addEventListener('click', () => {
                currentPageWarranty = page;
                applyFiltersAndPaginationWarranty();
            });
        }
        return button;
    }

    if (rowsPerPageSelectWarranty) {
        rowsPerPageSelectWarranty.addEventListener('change', function() {
            const selectedValue = this.value;
            rowsPerPageWarranty = selectedValue === 'all' ? allTableRowsWarranty.length : parseInt(selectedValue);
            currentPageWarranty = 1; 
            applyFiltersAndPaginationWarranty();
        });
    }

    Object.values(filterInputsWarranty).forEach(input => {
        if (input) {
            const eventType = input.tagName === 'SELECT' ? 'change' : 'input';
            input.addEventListener(eventType, () => {
                currentPageWarranty = 1;
                applyFiltersAndPaginationWarranty();
            });
        }
    });

    if (expiringDaysSelect) {
        expiringDaysSelect.addEventListener('change', function() {
            expiringDaysValue = parseInt(this.value);
            // Update Expiring Soon label
            var expSoonLabel = document.getElementById('expiringSoonLabel');
            if (expSoonLabel) {
                expSoonLabel.textContent = `Expiring Soon (${expiringDaysValue} days)`;
            }
            currentPageWarranty = 1;
            applyFiltersAndPaginationWarranty();
        });
        // Initial label update
        var expSoonLabel = document.getElementById('expiringSoonLabel');
        if (expSoonLabel) {
            expSoonLabel.textContent = `Expiring Soon (${expiringDaysValue} days)`;
        }
    }
    
    // Initial render
    if (tableBodyWarranty && allTableRowsWarranty.length > 0) { // Ensure table and rows exist before initial render
        applyFiltersAndPaginationWarranty();
    } else if (tableBodyWarranty) { // If table exists but no rows, still call to show "No entries"
        renderTableRowsWarranty([]);
        renderPaginationControlsWarranty(0);
    }

    function recalculateStatusesAndCounts() {
    let expiredCount = 0;
    let expiringSoonCount = 0;
    let activeWarrantyCount = 0;

    allTableRowsWarranty.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 5) return;

        // Get days remaining
        let daysText = cells[3].textContent.trim();
        let daysLeft = parseInt(daysText);
        if (isNaN(daysLeft)) daysLeft = null;
        if (daysLeft !== null && daysLeft < 0) daysLeft = 0;

        let status = 'Unknown';
        let statusClass = 'bg-gray-100 text-gray-800';

        if (daysLeft === null) {
            // Unknown
        } else if (daysLeft > expiringDaysValue) {
            status = 'Active';
            statusClass = 'bg-green-100 text-green-800';
            activeWarrantyCount++;
        } else if (daysLeft > 0) {
            status = 'Expiring Soon';
            statusClass = 'bg-yellow-100 text-yellow-800';
            expiringSoonCount++;
        } else {
            status = 'Expired';
            statusClass = 'bg-red-100 text-red-800';
            expiredCount++;
        }

        // Update days remaining cell if needed
        if (cells[3] && daysLeft !== null) {
            cells[3].textContent = daysLeft;
        }

        // Update status cell
        const statusCell = cells[4];
        if (statusCell) {
            statusCell.innerHTML = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${status}</span>`;
        }
    });

    // Update status cards
    document.getElementById('expiredCount').textContent = expiredCount;
    document.getElementById('expiringSoonCount').textContent = expiringSoonCount;
    document.getElementById('activeWarrantyCount').textContent = activeWarrantyCount;
}

// Call this function when the page loads and when the dropdown changes
if (expiringDaysSelect) {
    expiringDaysSelect.addEventListener('change', function() {
        expiringDaysValue = parseInt(this.value);
        recalculateStatusesAndCounts();
        currentPageWarranty = 1;
        applyFiltersAndPaginationWarranty();
    });
    // Initial call
    recalculateStatusesAndCounts();
}
});
</script>
