<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetsManagement;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;

class AssetsManagementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 per page
        if ($perPage === 'All' || $perPage == 0) {
            $perPage = AssetsManagement::count(); // Show all assets if 'All' is selected
        }
        $query = AssetsManagement::query();

        // Only apply filters if the filter form was submitted (i.e., at least one filter is set)
        $hasFilter = false;
        $filters = [
            'filter_name', 'filter_type', 'filter_serial', 'filter_model', 'filter_asset_tag',
            'filter_purchase_date', 'filter_warranty_end_date', 'filter_lifespan', 'filter_status'
        ];
        foreach ($filters as $filter) {
            if (trim($request->input($filter, '')) !== '') {
                $hasFilter = true;
                break;
            }
        }

        if ($hasFilter) {
            if (trim($request->input('filter_name', '')) !== '') {
                $query->where('asset_name', 'like', '%' . trim($request->input('filter_name')) . '%');
            }
            if (trim($request->input('filter_type', '')) !== '') {
                $query->where('type', 'like', '%' . trim($request->input('filter_type')) . '%');
            }
            if (trim($request->input('filter_serial', '')) !== '') {
                $query->where('serial_number', 'like', '%' . trim($request->input('filter_serial')) . '%');
            }
            if (trim($request->input('filter_model', '')) !== '') {
                $query->where('model', 'like', '%' . trim($request->input('filter_model')) . '%');
            }
            if (trim($request->input('filter_asset_tag', '')) !== '') {
                $query->where('asset_tag', 'like', '%' . trim($request->input('filter_asset_tag')) . '%');
            }
            if (trim($request->input('filter_purchase_date', '')) !== '') {
                $query->where('purchase_date', 'like', '%' . trim($request->input('filter_purchase_date')) . '%');
            }
            if (trim($request->input('filter_warranty_end_date', '')) !== '') {
                $query->where('warranty_end_date', 'like', '%' . trim($request->input('filter_warranty_end_date')) . '%');
            }
            if (trim($request->input('filter_lifespan', '')) !== '') {
                $query->where('expected_lifespan', 'like', '%' . trim($request->input('filter_lifespan')) . '%');
            }
            if (trim($request->input('filter_status', '')) !== '') {
                $query->where('status', trim($request->input('filter_status')));
            }
        }

        $assets = $query->paginate($perPage)->appends($request->except('page'));
        return view('admin.assets', compact('assets'));
    }

    public function create()
    {
        return view('admin.add-assets');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name' => 'required',
            'type' => 'required',
            'serial_number' => 'required',
            'model' => 'required',
            'asset_tag' => 'required',
            'purchase_date' => 'required|date',
            'warranty_end_date' => 'required|date',
            'expected_lifespan' => 'required|date',
            'status' => 'required',
            'type_custom' => 'nullable|string',
        ]);

        // If type is 'Other', use type_custom
        $type = $validated['type'] === 'Other' && !empty($validated['type_custom'])
            ? $validated['type_custom']
            : $validated['type'];

        $assetData = $validated;
        $assetData['type'] = $type;
        unset($assetData['type_custom']);

        AssetsManagement::create($assetData);
        return redirect()->route('admin.assets')->with('success', 'Asset created successfully!');
    }

    public function edit($id)
    {
        $asset = AssetsManagement::findOrFail($id);
        return view('admin.update-assets', compact('asset'));
    }

    public function update(Request $request, $id)
    {
        $asset = AssetsManagement::findOrFail($id);
        $validated = $request->validate([
            'asset_name' => 'required',
            'type' => 'required',
            'serial_number' => 'required',
            'model' => 'required',
            'asset_tag' => 'required', // This is just a value, not a lookup
            'purchase_date' => 'required|date',
            'warranty_end_date' => 'required|date',
            'expected_lifespan' => 'required|date',
            'status' => 'required',
            'type_custom' => 'nullable|string',
        ]);

        $type = $validated['type'] === 'Other' && !empty($validated['type_custom'])
            ? $validated['type_custom']
            : $validated['type'];

        $assetData = $validated;
        $assetData['type'] = $type;
        unset($assetData['type_custom']);

        $asset->update($assetData);

        return redirect()->route('admin.assets')->with('success', 'Asset updated successfully!');
    }

    public function destroy($id)
    {
        $asset = AssetsManagement::findOrFail($id);
        $asset->delete();
        return redirect()->route('admin.assets')->with('success', 'Asset deleted successfully!');
    }

    public function export()
    {
        return Excel::download(new \App\Exports\AssetsExport, 'assets.xlsx');
    }

    public function import(Request $request)
    {
        \Log::info('Assets import method called.');
        $status = [
            'success' => false,
            'message' => ''
        ];
        try {
            \Log::info('Assets import try block entered.');
            $importer = new \App\Imports\AssetsImport;
            \Maatwebsite\Excel\Facades\Excel::import($importer, $request->file('import_file'));
            if ($importer->importedCount > 0) {
                $status['success'] = true;
                $status['message'] = "Assets imported successfully! ({$importer->importedCount} added)";
            } else {
                $status['success'] = false;
                $status['message'] = 'No valid assets were imported. Please check your file format and columns.';
            }
        } catch (\Exception $e) {
            \Log::error('Assets import exception: ' . $e->getMessage());
            $status['success'] = false;
            $status['message'] = 'Import failed: Please check the file format and content. Remove any duplicate or invalid assets!';
        }
        return redirect()->route('admin.assets')->with('status', $status);
    }

    // Bulk delete selected assets
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_assets', []);
        if (!empty($ids)) {
            $deleted = AssetsManagement::whereIn('id', $ids)->delete();
            $message = $deleted > 0 ? "Deleted $deleted assets successfully!" : "No assets were deleted.";
            return redirect()->route('admin.assets')->with('success', $message);
        } else {
            return redirect()->route('admin.assets')->with('error', 'No assets selected for deletion.');
        }
    }
}
