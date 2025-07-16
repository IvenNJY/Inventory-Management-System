<head>
    <title>Lifespan Tracking</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="../../node_modules/preline/dist/preline.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- Include Chart.js --}}
    <style>
        .sticky-col {
            position: -webkit-sticky;
            position: sticky;
            right: 0;
            background-color: white; /* Or match your table row background */
            z-index: 10;
        }
        .sticky-header th { /* Ensure sticky headers are above sticky columns */
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 20 !important; /* Higher z-index for header */
        }
        .sticky-col {
            z-index: 10 !important; /* z-index for sticky column cells */
        }
        th.sticky-col { /* Sticky header cell for the sticky column */
            right: 0;
            z-index: 30 !important; /* Highest z-index for the corner */
        }
        /* Custom scrollbar for table container */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body class="bg-gray-100 overflow-x-hidden">
    <div class="flex min-h-screen"> {{-- Flex container for navbar and content --}}
        <div class="w-70 flex-shrink-0"> {{-- Fixed width sidebar --}}
            @include('admin.navbar')
        </div>

        <div class="flex-1 bg-gray-100 p-4 sm:p-6 lg:p-8 flex flex-col"> {{-- Main content area --}}
            <div class="mb-6 flex justify-between items-center flex-shrink-0">
                <h1 class="text-2xl font-semibold text-gray-800">Asset Lifespan Tracking</h1>
            </div>

            <div class="flex flex-col lg:flex-row gap-6 flex-grow">
                {{-- Left Column: Status Cards & Chart --}}
                <div class="lg:w-1/4 flex flex-col gap-6">
                    {{-- Lifespan Status Overview Card --}}
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Lifespan Status Overview</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4">
                            {{-- Card 1: Expired --}}
                            <div class="bg-red-50 p-4 rounded-lg flex items-center">
                                <div class="bg-red-100 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-red-700">Expired</p>
                                    <p class="text-2xl font-bold text-red-800" id="status-past-eol">{{ $expiredCount ?? 0 }}</p>
                                </div>
                            </div>
                            {{-- Card 2: Expiring Soon --}}
                            <div class="bg-yellow-50 p-4 rounded-lg flex items-center">
                                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-yellow-700">Expiring Soon (6 months)</p>
                                    <p class="text-2xl font-bold text-yellow-800" id="status-eol-next-6-months">{{ $expiringSoonCount ?? 0 }}</p>
                                </div>
                            </div>
                            {{-- Card 3: Active --}}
                            <div class="bg-green-50 p-4 rounded-lg flex items-center">
                                <div class="bg-green-100 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-green-700">Active</p>
                                    <p class="text-2xl font-bold text-green-800" id="status-nearing-eol">{{ $activeCount ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lifespan Distribution Chart Card --}}
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Asset Lifespan Distribution</h2>
                        <div class="bg-gray-50 p-4 rounded-lg shadow min-h-[300px]">
                            <canvas id="lifespanDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Table --}}
                <div class="lg:w-3/4 flex flex-col w-full">
                    <div class="bg-white p-6 rounded-lg shadow flex-grow flex flex-col h-full max-h-auto max-w-[100%]"> {{-- Modal-like card for table --}}
                        {{-- Table Header --}}
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Assets Approaching End of Lifespan</h2>
                            <button id="filter-toggle-btn-lifespan" type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md bg-white text-sm text-gray-700 hover:bg-gray-50 focus:outline-none">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0013 14.414V19a1 1 0 01-1.447.894l-2-1A1 1 0 009 18v-3.586a1 1 0 00-.293-.707L2.293 6.707A1 1 0 012 6V4z"/></svg>
                                Filter
                            </button>
                        </div>
                        <div class="flex-grow overflow-y-auto w-full"> {{-- Only vertical scroll --}}
                            <div class="w-full"> {{-- Remove min-w to prevent overflow --}}
                                <div class="shadow-md rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200" id="lifespan-table">
                                        <thead class="bg-gray-50 sticky-header">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th> --}}
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Est. Lifespan (Yrs)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Est. EOL Date</th>
                                                <th class="hidden">Months Remaining</th> {{-- Hidden column for JS --}}
                                                <th class="sticky-col px-6 py-3 text-left text-xs font-medium text-gray-500 bg-gray-50 uppercase tracking-wider">Status</th>
                                            </tr>
                                            <tr id="filter-row-lifespan" class="bg-gray-100 hidden">
                                                <td class="px-6 py-2"><input type="text" id="filter-asset-name-lifespan" placeholder="Filter Name" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                                {{-- <td class="px-6 py-2"><input type="text" id="filter-serial-lifespan" placeholder="Filter Serial" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td> --}}
                                                <td class="px-6 py-2"><input type="text" id="filter-purchase-date-lifespan" placeholder="YYYY-MM-DD" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                                <td class="px-6 py-2"><input type="text" id="filter-lifespan-lifespan" placeholder="Filter Lifespan" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                                <td class="px-6 py-2"><input type="text" id="filter-eol-date-lifespan" placeholder="YYYY-MM-DD" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                                <td class="hidden"></td>
                                                <td class="px-6 py-2"><input type="text" id="filter-status-lifespan" placeholder="Filter Status" class="w-full bg-white border border-gray-300 rounded text-xs py-1 px-2" /></td>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @if(isset($assets) && count($assets))
                                                @foreach($assets as $asset)
                                                    @php
                                                        $purchase = $asset->purchase_date;
                                                        $lifespan = $asset->expected_lifespan;
                                                        $today = \Carbon\Carbon::today();
                                                        $eolDate = $lifespan ? \Carbon\Carbon::parse($lifespan) : null;
                                                        $monthsLeft = $eolDate ? $today->diffInMonths($eolDate, false) : null;
                                                        if ($monthsLeft === null) {
                                                            $status = 'Unknown';
                                                            $statusClass = 'bg-gray-100 text-gray-800';
                                                        } elseif ($monthsLeft > 6) {
                                                            $status = 'Active';
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                        } elseif ($monthsLeft > 0) {
                                                            $status = 'Expiring Soon';
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        } else {
                                                            $status = 'Expired';
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $asset->asset_name }}</td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $asset->purchase_date }}</td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $lifespan ? \Carbon\Carbon::parse($asset->purchase_date)->diffInYears($eolDate, false) : 'N/A' }}</td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $lifespan }}</td>
                                                        <td class="hidden">{{ $monthsLeft !== null ? $monthsLeft : 'N/A' }}</td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-sm sticky-col">
                                                            @if($status === 'Active')
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                    {{ $status }}
                                                                </span>
                                                            @elseif($status === 'Expiring Soon')
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                    {{ $status }}
                                                                </span>
                                                            @elseif($status === 'Expired')
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                    {{ $status }}
                                                                </span>
                                                            @else
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                    {{ $status }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="6" class="text-center py-4 text-gray-500">No assets found.</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- Pagination Controls --}}
                        <div class="py-3 px-1.5 flex flex-col sm:flex-row justify-between items-center gap-2 border-t border-gray-200 mt-auto">
                            <div class="flex items-center gap-x-2">
                                <span class="text-sm text-gray-600">Show:</span>
                                <select id="hs-select-rows-lifespan" class="py-1.5 px-2 pe-9 block w-auto border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="all">All</option>
                                </select>
                                <span id="pagination-info-lifespan" class="text-sm text-gray-600">Showing 1 to 10 of X entries</span>
                            </div>
                            <div id="pagination-controls-lifespan" class="flex items-center gap-x-1">
                                {{-- Pagination buttons will be rendered here by JavaScript --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Chart.js Setup ---
    const lifespanChartCtx = document.getElementById('lifespanDistributionChart')?.getContext('2d');
    let lifespanChartInstance;

    function updateChartAndStats(rows) {
        let pastEOLCount = 0;
        let eolNext6MonthsCount = 0;
        let eol6To12MonthsCount = 0;
        let eol12To24MonthsCount = 0;
        let eolOver24MonthsCount = 0;
        let activeNearingEOLCount = 0; // All non-past EOL

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 6) return;
            const monthsRemainingText = cells[4].textContent.trim();
            const monthsRemaining = parseInt(monthsRemainingText);

            if (!isNaN(monthsRemaining)) {
                if (monthsRemaining < 0) {
                    pastEOLCount++;
                } else {
                    activeNearingEOLCount++; // Count all active ones
                    if (monthsRemaining <= 6) {
                        eolNext6MonthsCount++;
                    } else if (monthsRemaining <= 12) {
                        eol6To12MonthsCount++;
                    } else if (monthsRemaining <= 24) {
                        eol12To24MonthsCount++;
                    } else {
                        eolOver24MonthsCount++;
                    }
                }
            }
        });

        // Update Stat Cards
        document.getElementById('status-nearing-eol').textContent = activeNearingEOLCount;
        document.getElementById('status-eol-next-6-months').textContent = eolNext6MonthsCount;
        document.getElementById('status-past-eol').textContent = pastEOLCount;

        // Update Chart Data
        const chartData = {
            labels: ['Past EOL', '<= 6 Months', '7-12 Months', '13-24 Months', '> 24 Months'],
            datasets: [{
                label: 'Asset Lifespan Status',
                data: [pastEOLCount, eolNext6MonthsCount, eol6To12MonthsCount, eol12To24MonthsCount, eolOver24MonthsCount],
                backgroundColor: [
                    'rgba(239, 68, 68, 0.7)',  // Past EOL (Red)
                    'rgba(249, 115, 22, 0.7)', // <= 6 Months (Orange)
                    'rgba(234, 179, 8, 0.7)',  // 7-12 Months (Yellow)
                    'rgba(59, 130, 246, 0.7)', // 13-24 Months (Blue)
                    'rgba(34, 197, 94, 0.7)'  // > 24 Months (Green)
                ],
                borderColor: [
                    'rgb(239, 68, 68)',
                    'rgb(249, 115, 22)',
                    'rgb(234, 179, 8)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)'
                ],
                borderWidth: 1
            }]
        };

        if (lifespanChartInstance) {
            lifespanChartInstance.data = chartData;
            lifespanChartInstance.update();
        } else if (lifespanChartCtx) {
            lifespanChartInstance = new Chart(lifespanChartCtx, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Distribution by Remaining Lifespan'
                        }
                    }
                },
            });
        }
    }


    // --- Table Filtering, Pagination, and Sorting Logic ---
    const filterToggleButtonLifespan = document.getElementById('filter-toggle-btn-lifespan');
    const filterRowLifespan = document.getElementById('filter-row-lifespan');
    const filterInputsLifespan = {
        assetName: document.getElementById('filter-asset-name-lifespan'),
        // serial: document.getElementById('filter-serial-lifespan'),
        purchaseDate: document.getElementById('filter-purchase-date-lifespan'),
        lifespan: document.getElementById('filter-lifespan-lifespan'),
        eolDate: document.getElementById('filter-eol-date-lifespan'),
        status: document.getElementById('filter-status-lifespan'),
        // monthsRemaining: document.getElementById('filter-months-remaining-lifespan'),
    };
    const tableLifespan = document.getElementById('lifespan-table');
    const tableBodyLifespan = tableLifespan?.querySelector('tbody');
    
    if (!tableBodyLifespan) {
        console.error("Lifespan table body not found!");
        return;
    }

    let allTableRowsLifespan = Array.from(tableBodyLifespan.querySelectorAll('tr'));

    const rowsPerPageSelectLifespan = document.getElementById('hs-select-rows-lifespan');
    const paginationControlsLifespan = document.getElementById('pagination-controls-lifespan');
    const paginationInfoLifespan = document.getElementById('pagination-info-lifespan');
    let currentPageLifespan = 1;
    // rowsPerPageLifespan will be set in applyFiltersAndPaginationLifespan

    // Helper function to get a sortable value for "Months Remaining"
    function getSortableMonths(/*monthsTextCell*/) {
        // Always return 0 since months column is removed
        return 0;
    }

    // Default sort (no months column)
    // allTableRowsLifespan.sort((a, b) => {
    //     const monthsA = getSortableMonths(a.cells[5]);
    //     const monthsB = getSortableMonths(b.cells[5]);
    //     return monthsA - monthsB;
    // });

    if (filterToggleButtonLifespan) {
        filterToggleButtonLifespan.addEventListener('click', function() {
            filterRowLifespan.classList.toggle('hidden');
        });
    }

    function getStatusOrder(statusText) {
        // Lower number = higher priority in sorting
        statusText = statusText.toLowerCase();
        if (statusText.includes('past eol')) return 0;
        if (statusText.includes('eol in 6 months')) return 1;
        if (statusText.includes('eol in 8 months')) return 2;
        if (statusText.includes('eol in 12 months')) return 3;
        if (statusText.includes('active')) return 4;
        return 99;
    }

    function applyFiltersAndPaginationLifespan() {
        const filters = {
            assetName: filterInputsLifespan.assetName ? filterInputsLifespan.assetName.value.toLowerCase() : '',
            // serial: filterInputsLifespan.serial ? filterInputsLifespan.serial.value.toLowerCase() : '',
            purchaseDate: filterInputsLifespan.purchaseDate ? filterInputsLifespan.purchaseDate.value : '',
            lifespan: filterInputsLifespan.lifespan ? filterInputsLifespan.lifespan.value.toLowerCase() : '',
            eolDate: filterInputsLifespan.eolDate ? filterInputsLifespan.eolDate.value : '',
            status: filterInputsLifespan.status ? filterInputsLifespan.status.value.toLowerCase() : '',
            // monthsRemaining: filterInputsLifespan.monthsRemaining ? filterInputsLifespan.monthsRemaining.value : '',
        };

        let filteredRows = allTableRowsLifespan.filter(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 6) return false;

            let showRow = true;
            if (filters.assetName && !cells[0].textContent.toLowerCase().includes(filters.assetName)) showRow = false;
            // if (filters.serial && !cells[1].textContent.toLowerCase().includes(filters.serial)) showRow = false;
            if (filters.purchaseDate && !cells[1].textContent.startsWith(filters.purchaseDate)) showRow = false;
            if (filters.lifespan && !cells[2].textContent.toLowerCase().includes(filters.lifespan)) showRow = false;
            if (filters.eolDate && !cells[3].textContent.startsWith(filters.eolDate)) showRow = false;
            if (filters.status && !cells[5].textContent.toLowerCase().includes(filters.status)) showRow = false;
            // if (filters.monthsRemaining && !cells[4].textContent.trim().includes(filters.monthsRemaining)) showRow = false;
            // cells[5] is Status
            return showRow;
        });

        // Sort filteredRows by status order
        filteredRows.sort((a, b) => {
            const statusA = a.querySelectorAll('td')[5]?.textContent || '';
            const statusB = b.querySelectorAll('td')[5]?.textContent || '';
            return getStatusOrder(statusA) - getStatusOrder(statusB);
        });

        updateChartAndStats(filteredRows); // Update chart with filtered data
        renderTableRowsLifespan(filteredRows);
        renderPaginationControlsLifespan(filteredRows.length);
    }

    function renderTableRowsLifespan(rowsToRender) {
        tableBodyLifespan.innerHTML = ''; // Clear existing rows
        const totalFilteredRows = rowsToRender.length;
        let paginatedRows;
        
        let currentRowsPerPageSetting = rowsPerPageSelectLifespan ? rowsPerPageSelectLifespan.value : '10';
        let rowsPerPageLifespan = currentRowsPerPageSetting === 'all' ? totalFilteredRows : parseInt(currentRowsPerPageSetting);
        if (isNaN(rowsPerPageLifespan) || rowsPerPageLifespan <= 0) rowsPerPageLifespan = totalFilteredRows > 0 ? totalFilteredRows : 10;

        if (totalFilteredRows === 0) {
            paginatedRows = [];
            const noResultsRow = tableBodyLifespan.insertRow();
            const cell = noResultsRow.insertCell();
            cell.colSpan = tableLifespan.rows[0].cells.length; // Span all columns
            cell.textContent = 'No matching assets found.';
            cell.className = 'text-center text-gray-500 py-4';
        } else {
             const startIndex = (currentPageLifespan - 1) * rowsPerPageLifespan;
             const endIndex = Math.min(startIndex + rowsPerPageLifespan, totalFilteredRows);
             paginatedRows = rowsToRender.slice(startIndex, endIndex);
        }

        paginatedRows.forEach(row => {
            tableBodyLifespan.appendChild(row.cloneNode(true)); // Clone to avoid issues if rows are re-filtered
        });
    }
    
    function renderPaginationControlsLifespan(totalFilteredRows) {
        if (!paginationControlsLifespan || !paginationInfoLifespan || !rowsPerPageSelectLifespan) return;

        paginationControlsLifespan.innerHTML = ''; 

        const currentRowsPerPageSetting = rowsPerPageSelectLifespan.value;
        let rowsPerPageLifespan = (currentRowsPerPageSetting === 'all' || parseInt(currentRowsPerPageSetting) >= totalFilteredRows && totalFilteredRows > 0) 
                               ? totalFilteredRows 
                               : parseInt(currentRowsPerPageSetting);
        if (isNaN(rowsPerPageLifespan) || rowsPerPageLifespan <= 0) rowsPerPageLifespan = totalFilteredRows > 0 ? totalFilteredRows : 1; // Avoid division by zero if no rows

        
        const totalPages = (rowsPerPageLifespan > 0 && totalFilteredRows > 0) ? Math.ceil(totalFilteredRows / rowsPerPageLifespan) : 1;
        currentPageLifespan = Math.min(currentPageLifespan, totalPages); // Adjust current page if it's out of bounds

        // Update info text
        const startItem = totalFilteredRows === 0 ? 0 : (currentPageLifespan - 1) * rowsPerPageLifespan + 1;
        const endItem = Math.min(startItem + rowsPerPageLifespan - 1, totalFilteredRows);
        paginationInfoLifespan.textContent = `Showing ${startItem} to ${endItem} of ${totalFilteredRows} entries`;

        if (totalPages <= 1 && totalFilteredRows > 0) { // No pagination buttons if only one page, unless no entries
             paginationInfoLifespan.textContent = `Showing ${startItem} to ${endItem} of ${totalFilteredRows} entries`;
             return; // No buttons needed
        }
         if (totalFilteredRows === 0) {
            paginationInfoLifespan.textContent = 'No entries';
            return;
        }


        // Previous Button
        const prevButton = createPaginationButtonLifespan(
            '<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>',
            currentPageLifespan - 1,
            currentPageLifespan === 1,
            false,
            'prev'
        );
        paginationControlsLifespan.appendChild(prevButton);

        // Page Number Buttons (with ellipsis)
        let pagesToShow = [];
        if (totalPages <= 7) { // Show all pages if 7 or less
            for (let i = 1; i <= totalPages; i++) pagesToShow.push(i);
        } else {
            pagesToShow.push(1);
            if (currentPageLifespan > 4) pagesToShow.push('...');
            
            let startPage = Math.max(2, currentPageLifespan - 2);
            let endPage = Math.min(totalPages - 1, currentPageLifespan + 2);

            if (currentPageLifespan <= 4) endPage = Math.min(totalPages -1, 5);
            if (currentPageLifespan >= totalPages - 3) startPage = Math.max(2, totalPages - 4);

            for (let i = startPage; i <= endPage; i++) pagesToShow.push(i);
            
            if (currentPageLifespan < totalPages - 3) pagesToShow.push('...');
            pagesToShow.push(totalPages);
        }

        pagesToShow.forEach(pageNum => {
            if (pageNum === '...') {
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.className = 'px-2.5 py-1.5 text-sm text-gray-500';
                ellipsisSpan.textContent = '...';
                paginationControlsLifespan.appendChild(ellipsisSpan);
            } else {
                paginationControlsLifespan.appendChild(createPaginationButtonLifespan(
                    pageNum.toString(),
                    pageNum,
                    false,
                    pageNum === currentPageLifespan
                ));
            }
        });

        // Next Button
        const nextButton = createPaginationButtonLifespan(
            '<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>',
            currentPageLifespan + 1,
            currentPageLifespan === totalPages,
            false,
            'next'
        );
        paginationControlsLifespan.appendChild(nextButton);
    }

    function createPaginationButtonLifespan(htmlContent, page, isDisabled, isCurrentPage = false, type = 'page') {
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
        else roundedClasses = 'rounded-md'; // Individual page buttons
        
        if (isCurrentPage) {
            bgClasses = 'bg-blue-500 hover:bg-blue-600';
            textClasses = 'text-white';
            borderClasses = 'border-blue-500';
        }
        
        button.className = `${baseClasses} ${borderClasses} ${textClasses} ${bgClasses} ${roundedClasses}`;
        button.disabled = isDisabled;

        if (!isDisabled && type !== 'ellipsis') {
            button.addEventListener('click', () => {
                currentPageLifespan = page;
                applyFiltersAndPaginationLifespan();
            });
        }
        return button;
    }

    if (rowsPerPageSelectLifespan) {
        rowsPerPageSelectLifespan.addEventListener('change', function() {
            currentPageLifespan = 1; // Reset to first page on changing rows per page
            applyFiltersAndPaginationLifespan();
        });
    }

    Object.values(filterInputsLifespan).forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                currentPageLifespan = 1;
                applyFiltersAndPaginationLifespan();
            });
            // For date input, apply on change (already handled by input event)
        }
    });
    
    // Initial render
    if (tableBodyLifespan && allTableRowsLifespan.length > 0) {
        applyFiltersAndPaginationLifespan(); // This will also call updateChartAndStats
    } else if (tableBodyLifespan) { // If table exists but no rows, still call to show "No entries" and update chart/stats
        renderTableRowsLifespan([]);
        renderPaginationControlsLifespan(0);
        updateChartAndStats([]); // Update chart/stats with empty data
    }
});
</script>
</body>
</html>
</html>
