<head>
    <title>Update Asset</title>
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
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800">Update Asset ({{ $asset->asset_name ?? 'N/A' }})</h1>
                <a href="{{ url()->previous() }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    Back
                </a>
            </div>

            {{-- Show success/error messages --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(isset($asset))
            <form method="POST" action="{{ route('admin.update-asset', $asset->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="asset_name" class="block text-sm font-medium mb-2 text-gray-700">Asset Name</label>
                        <input type="text" id="asset_name" name="asset_name" value="{{ old('asset_name', $asset->asset_name) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="asset-type-select" class="block text-sm font-medium mb-2 text-gray-700">Type</label>
                        <div class="flex gap-x-2">
                            <select id="asset-type-select" name="type" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 basis-1/4" required>
                                <option value="" disabled>Select a Type</option>
                                @php
                                    $types = ['Laptop','Desktop','Monitor','Keyboard','Mouse','Printer','Scanner','Projector','Server','Networking Equipment','Software License','Furniture','Mobile Phone','Tablet','Other'];
                                @endphp
                                @foreach($types as $typeOption)
                                    <option value="{{ $typeOption }}" {{ (old('type', $asset->type) == $typeOption) ? 'selected' : '' }}>{{ $typeOption }}</option>
                                @endforeach
                            </select>
                            <div id="custom-type-wrapper" class="{{ (old('type', $asset->type) == 'Other') ? '' : 'hidden' }} basis-3/4">
                                <input type="text" id="asset-type-custom" name="type_custom" placeholder="Enter custom type" value="{{ old('type_custom', (in_array($asset->type, $types) ? '' : $asset->type)) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="serial_number" class="block text-sm font-medium mb-2 text-gray-700">Serial Number</label>
                        <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="model" class="block text-sm font-medium mb-2 text-gray-700">Model</label>
                        <input type="text" id="model" name="model" value="{{ old('model', $asset->model) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="asset_tag" class="block text-sm font-medium mb-2 text-gray-700">Asset Tag</label>
                        <input type="text" id="asset_tag" name="asset_tag" value="{{ old('asset_tag', $asset->asset_tag) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required readonly>
                    </div>
                    <div>
                        <label for="purchase_date" class="block text-sm font-medium mb-2 text-gray-700">Purchase Date</label>
                        <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="warranty_end_date" class="block text-sm font-medium mb-2 text-gray-700">Warranty End Date</label>
                        <input type="date" id="warranty_end_date" name="warranty_end_date" value="{{ old('warranty_end_date', $asset->warranty_end_date) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="expected_lifespan" class="block text-sm font-medium mb-2 text-gray-700">Expected Lifespan (date)</label>
                        <input type="date" id="expected_lifespan" name="expected_lifespan" value="{{ old('expected_lifespan', $asset->expected_lifespan) }}" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium mb-2 text-gray-700">Status</label>
                        <select id="status" name="status" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="Active" {{ old('status', $asset->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="In Repair" {{ old('status', $asset->status) == 'In Repair' ? 'selected' : '' }}>In Repair</option>
                            <option value="Retired" {{ old('status', $asset->status) == 'Retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Asset</button>
                </div>
            </form>
            @else
                <div class="text-red-600">Asset not found.</div>
            @endif
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const assetTypeSelect = document.getElementById('asset-type-select');
    const customTypeWrapper = document.getElementById('custom-type-wrapper');
    const customTypeInput = document.getElementById('asset-type-custom');
    assetTypeSelect.addEventListener('change', function () {
        if (this.value === 'Other') {
            customTypeWrapper.classList.remove('hidden');
            assetTypeSelect.classList.remove('w-full');
            assetTypeSelect.classList.add('basis-1/4');
            customTypeInput.setAttribute('required', 'required');
            customTypeInput.focus();
        } else {
            customTypeWrapper.classList.add('hidden');
            assetTypeSelect.classList.add('w-full');
            assetTypeSelect.classList.remove('basis-1/4');
            customTypeInput.removeAttribute('required');
            customTypeInput.value = '';
        }
    });
    if (assetTypeSelect.value === 'Other') {
        customTypeWrapper.classList.remove('hidden');
        assetTypeSelect.classList.remove('w-full');
        assetTypeSelect.classList.add('basis-1/4');
        customTypeInput.setAttribute('required', 'required');
    } else {
        assetTypeSelect.classList.add('w-full');
        assetTypeSelect.classList.remove('basis-1/4');
    }
});
</script>
