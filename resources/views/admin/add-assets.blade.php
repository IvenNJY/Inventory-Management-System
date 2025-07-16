<head>
    <title>Add New Asset</title>
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
                <h1 class="text-2xl font-semibold text-gray-800">Add New Asset</h1>
                <a href="{{ url()->previous() }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    Back
                </a>
            </div>

            <form method="POST" action="{{ route('admin.store-assets') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="asset_name" class="block text-sm font-medium mb-2 text-gray-700">Asset Name</label>
                        <input type="text" id="asset_name" name="asset_name" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="asset-type-select" class="block text-sm font-medium mb-2 text-gray-700">Type</label>
                        <div class="flex gap-x-2">
                            <select id="asset-type-select" name="type" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 basis-1/4" required>
                                <option value="" selected disabled>Select a Type</option>
                                <option value="Laptop">Laptop</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Printer">Printer</option>
                                <option value="Scanner">Scanner</option>
                                <option value="Projector">Projector</option>
                                <option value="Server">Server</option>
                                <option value="Networking Equipment">Networking Equipment</option>
                                <option value="Software License">Software License</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Mobile Phone">Mobile Phone</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="custom-type-wrapper" class="hidden basis-3/4">
                                <input type="text" id="asset-type-custom" name="type_custom" placeholder="Enter custom type" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="serial_number" class="block text-sm font-medium mb-2 text-gray-700">Serial Number</label>
                        <input type="text" id="serial_number" name="serial_number" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="model" class="block text-sm font-medium mb-2 text-gray-700">Model</label>
                        <input type="text" id="model" name="model" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="asset_tag" class="block text-sm font-medium mb-2 text-gray-700">Asset Tag</label>
                        <input type="text" id="asset_tag" name="asset_tag" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="purchase_date" class="block text-sm font-medium mb-2 text-gray-700">Purchase Date</label>
                        <input type="date" id="purchase_date" name="purchase_date" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="warranty_end_date" class="block text-sm font-medium mb-2 text-gray-700">Warranty End Date</label>
                        <input type="date" id="warranty_end_date" name="warranty_end_date" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="expected_lifespan" class="block text-sm font-medium mb-2 text-gray-700">Expected Lifespan (date)</label>
                        <input type="date" id="expected_lifespan" name="expected_lifespan" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium mb-2 text-gray-700">Status</label>
                        <select id="status" name="status" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="Active">Active</option>
                            <option value="In Repair">In Repair</option>
                            <option value="Retired">Retired</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" id="createAssetButton" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Create Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const assetTypeSelect = document.getElementById('asset-type-select');
    const customTypeWrapper = document.getElementById('custom-type-wrapper');
    const customTypeInput = document.getElementById('asset-type-custom');

    // Disable Create Asset button if any required field is empty
    const createAssetButton = document.getElementById('createAssetButton');
    const assetForm = createAssetButton.closest('form');
    const requiredFields = assetForm.querySelectorAll('[required]');

    function checkRequiredFields() {
        let allFilled = true;
        requiredFields.forEach(field => {
            if (field.offsetParent !== null && !field.value) {
                allFilled = false;
            }
        });
        createAssetButton.disabled = !allFilled;
        if (!allFilled) {
            createAssetButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            createAssetButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    requiredFields.forEach(field => {
        field.addEventListener('input', checkRequiredFields);
    });
    // Also check on type select change (for custom type)
    assetTypeSelect.addEventListener('change', checkRequiredFields);
    if (customTypeInput) customTypeInput.addEventListener('input', checkRequiredFields);
    checkRequiredFields();

    // Confirmation modal for Create Asset
    createAssetButton.addEventListener('click', function(event) {
        event.preventDefault();
        if (createAssetButton.disabled) return;
        showConfirmationModal(
            'Create Asset',
            'Are you sure you want to create this asset?',
            function() {
                assetForm.submit();
            },
            'confirmation'
        );
    });

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
            customTypeInput.value = ''; // Clear custom input when other type is selected
        }
    });
    // Initial check in case the form is reloaded with 'Other' selected (though less likely for add form)
    if (assetTypeSelect.value === 'Other') {
        customTypeWrapper.classList.remove('hidden');
        assetTypeSelect.classList.remove('w-full');
        assetTypeSelect.classList.add('basis-1/4');
        customTypeInput.setAttribute('required', 'required');
    } else {
        assetTypeSelect.classList.add('w-full');
        assetTypeSelect.classList.remove('basis-1/4');
    }

    // ...existing code...
});
</script>
