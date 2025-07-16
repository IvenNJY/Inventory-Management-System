<head>
    <title>Sign In</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    @vite('resources/css/app.css')
</head>

@php
    $userCount = \App\Models\User::count();
@endphp

<div class="flex min-h-screen"> {{-- Flex container for navbar and content --}}
    <div class="w-70 flex-shrink-0"> {{-- Fixed width sidebar --}}
        @include('admin.navbar')
    </div>
    <div class="flex-1 bg-gray-100 p-4 sm:p-6 lg:p-8"> {{-- Main content area --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Stat Cards Column -->
            <div class="md:col-span-1 space-y-6">
                <!-- Total Assets Card -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Assets</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-semibold text-gray-800">{{ number_format($assets->count()) }}</p>
                    </div>
                </div>

                <!-- Total Employees Card -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Employees</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-semibold text-gray-800">{{ number_format($userCount) }}</p>
                    </div>
                </div>
            </div>

            <!-- Under Warranty & Expiring Assets Column -->
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Under Warranty Card -->
                <div class="bg-white rounded-lg shadow relative">
                    <!-- Red Ping -->
                    <span class="absolute -top-2 -right-2 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                    </span>
                    @php
                        $underWarrantyItems = $assets->filter(function ($asset) {
                            $expiryDate = $asset->warranty_end_date ? \Carbon\Carbon::parse($asset->warranty_end_date) : null;
                            $daysLeft = $expiryDate ? \Carbon\Carbon::today()->diffInDays($expiryDate, false) : null;
                            return $daysLeft !== null && $daysLeft > 0;
                        })->map(function ($asset) {
                            $expiryDate = \Carbon\Carbon::parse($asset->warranty_end_date);
                            $daysLeft = \Carbon\Carbon::today()->diffInDays($expiryDate, false);
                            return [
                                'name' => $asset->asset_name,
                                'category' => $asset->type,
                                'expiry' => $asset->warranty_end_date,
                                'status_class' => $daysLeft > 60 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800',
                                'status_text' => $daysLeft . ' days left',
                                'days_left' => $daysLeft,
                            ];
                        })->sortBy('days_left')->values()->take(4);

                        $underWarrantyCount = $assets->filter(function ($asset) {
                            $expiryDate = $asset->warranty_end_date ? \Carbon\Carbon::parse($asset->warranty_end_date) : null;
                            $daysLeft = $expiryDate ? \Carbon\Carbon::today()->diffInDays($expiryDate, false) : null;
                            return $daysLeft !== null && $daysLeft > 0;
                        })->count();
                    @endphp

                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Under Warranty - {{ $underWarrantyCount }}</h3>
                        <a href="{{ url('/admin/warranty') }}" class="text-blue-600 text-sm hover:underline font-medium">View More</a>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($underWarrantyItems as $item)
                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <span class="text-sm text-gray-700 font-medium block">{{ $item['name'] }}</span>
                                <span class="text-xs text-gray-400 block">{{ $item['category'] }}</span>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['status_class'] }}">
                                {{ $item['status_text'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Expiring Assets Card -->
                <div class="bg-white rounded-lg shadow relative">
                    <!-- Red Ping -->
                    <span class="absolute -top-2 -right-2 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                    </span>
                    @php
                        $expiringAssetsItems = $assets->filter(function ($asset) {
                            $expiryDate = $asset->warranty_end_date ? \Carbon\Carbon::parse($asset->warranty_end_date) : null;
                            $daysLeft = $expiryDate ? \Carbon\Carbon::today()->diffInDays($expiryDate, false) : null;
                            return $daysLeft !== null && $daysLeft <= 60 && $daysLeft > 0;
                        })->map(function ($asset) {
                            $expiryDate = \Carbon\Carbon::parse($asset->warranty_end_date);
                            $daysLeft = \Carbon\Carbon::today()->diffInDays($expiryDate, false);
                            return [
                                'name' => $asset->asset_name,
                                'category' => $asset->type,
                                'expiry' => $asset->warranty_end_date,
                                'status_class' => $daysLeft > 30 ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800',
                                'status_text' => $daysLeft . ' days left',
                                'days_left' => $daysLeft,
                            ];
                        })->sortBy('days_left')->values()->take(4);

                        $expiringAssetsCount = $assets->filter(function ($asset) {
                            $expiryDate = $asset->warranty_end_date ? \Carbon\Carbon::parse($asset->warranty_end_date) : null;
                            $daysLeft = $expiryDate ? \Carbon\Carbon::today()->diffInDays($expiryDate, false) : null;
                            return $daysLeft !== null && $daysLeft <= 60 && $daysLeft > 0;
                        })->count();
                    @endphp

                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Expiring Assets - {{ $expiringAssetsCount }}</h3>
                        <a href="{{ url('/admin/lifespan') }}" class="text-blue-600 text-sm hover:underline font-medium">View More</a>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($expiringAssetsItems as $item)
                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <span class="text-sm text-gray-700 font-medium block">{{ $item['name'] }}</span>
                                <span class="text-xs text-gray-400 block">{{ $item['category'] }}</span>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['status_class'] }}">
                                {{ $item['status_text'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Assets Table -->
            <div class="bg-white rounded-lg shadow overflow-x-auto col-span-3">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assets</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Expiry</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $today = \Carbon\Carbon::today();
                            $todayAssets = isset($assets) ? $assets->filter(function($asset) use ($today) {
                                return \Carbon\Carbon::parse($asset->created_at)->isSameDay($today);
                            })->take(5) : collect();
                        @endphp
                        @if($todayAssets->count())
                            @foreach($todayAssets as $asset)
                                @php
                                    $expiry = $asset->warranty_end_date;
                                    $expiryDate = $expiry ? \Carbon\Carbon::parse($expiry) : null;
                                    $daysLeft = $expiryDate ? $today->diffInDays($expiryDate, false) : null;
                                    if ($daysLeft === null) {
                                        $status = 'Unknown';
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                    } elseif ($daysLeft > 60) {
                                        $status = 'Warranty Active';
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($daysLeft > 0) {
                                        $status = 'Expiring Soon';
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $status = 'Warranty Expired';
                                        $statusClass = 'bg-red-100 text-red-800';
                                    }
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $asset->asset_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $asset->warranty_end_date }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $asset->type }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4" class="text-center py-4 text-gray-500">No assets found for today.</td></tr>
                        @endif
                    </tbody>
                </table>
                
            </div>

        </div>
                    {{-- View All Assets Link --}}
            <div class="flex justify-end mt-4">
                <a href="{{ url('/admin/assets') }}" class="text-blue-600 hover:underline font-medium pointer">
                    View All Assets &rarr;
                </a>
            </div>
    </div>
</div>