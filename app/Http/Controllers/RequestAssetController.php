<?php

namespace App\Http\Controllers;

use App\Models\RequestAsset;
use App\Models\DeployedAsset;
use App\Models\DeploymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestAssetController extends Controller
{
    public function store(Request $request)
    {
        Log::info('RequestAssetController@store', $request->all());
        $validated = $request->validate([
            'type' => 'required|string',
            'asset_id' => 'required|string',
            'asset_name' => 'required|string',
            'model_serial_num' => 'required|string',
            'assigned_date' => 'required|date',
            'reason' => 'required|string',
            'status' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        RequestAsset::create($validated);
        return redirect()->back()->with('success', 'Asset request submitted successfully!');
    }

    public function index()
    {
        $requests = RequestAsset::with('user')->get();
        return view('admin.requests', compact('requests'));
    }

    public function approve($id)
    {
        try {
            Log::info('Approving request ID: ' . $id);
            $request = RequestAsset::findOrFail($id);
            if ($request->status !== 'Pending') {
                Log::warning('Request ID ' . $id . ' is not pending');
                return response()->json(['success' => false, 'message' => 'Request is not pending'], 403);
            }

            // Find asset by serial number from the request
            Log::info('Looking for asset with serial number: ' . $request->model_serial_num);
            
            // Debug: Show all deployed assets for comparison
            $allDeployedAssets = DeployedAsset::select('id', 'serial_num', 'status')->get();
            Log::info('All deployed assets in table:', $allDeployedAssets->toArray());
            
            // Try multiple search patterns for serial number matching
            $searchPatterns = [
                $request->model_serial_num, // Exact match
                trim(explode('/', $request->model_serial_num)[1] ?? $request->model_serial_num), // After slash
                trim(explode('/', $request->model_serial_num)[0] ?? $request->model_serial_num), // Before slash
            ];
            
            $asset = null;
            foreach ($searchPatterns as $pattern) {
                $pattern = trim($pattern);
                if (!empty($pattern)) {
                    Log::info('Trying search pattern: ' . $pattern);
                    $asset = DeployedAsset::where('serial_num', $pattern)->first();
                    if ($asset) {
                        Log::info('Asset found with pattern: ' . $pattern);
                        break;
                    }
                }
            }
            
            if (!$asset) {
                // Try to initialize assets first
                Log::info('Asset not found, initializing assets from asset_management');
                
                // Debug: Check source table first
                $sourceAssets = \App\Models\AssetsManagement::where('status', 'Active')->get();
                Log::info('Source assets from asset_management:', $sourceAssets->toArray());
                
                DeployedAsset::initializeFromAssetManagement();
                
                // Debug: Show assets after initialization
                $allDeployedAssetsAfter = DeployedAsset::select('id', 'serial_num', 'status')->get();
                Log::info('All deployed assets after initialization:', $allDeployedAssetsAfter->toArray());
                
                $asset = DeployedAsset::where('serial_num', $request->model_serial_num)->first();
                
                if (!$asset) {
                    // Try alternative search patterns
                    Log::info('Trying alternative search patterns...');
                    $similarAssets = DeployedAsset::where('serial_num', 'LIKE', '%' . $request->model_serial_num . '%')->get();
                    Log::info('Assets with similar serial numbers:', $similarAssets->toArray());
                    
                    // Also try case-insensitive search
                    $caseInsensitiveAssets = DeployedAsset::whereRaw('LOWER(serial_num) = ?', [strtolower($request->model_serial_num)])->get();
                    Log::info('Case-insensitive search results:', $caseInsensitiveAssets->toArray());
                }
            }
            
            if ($asset) {
                Log::info('Asset found', ['asset_id' => $asset->id, 'current_status' => $asset->status]);
                
                // If asset is currently deployed to someone else, end their deployment
                if ($asset->status === 'deployed') {
                    Log::info('Ending current deployment for asset: ' . $asset->serial_num . ' (currently assigned to: ' . $asset->assigned_to . ')');
                    $asset->update(['status' => 'ended']);
                }

                // Assign asset to the requesting user
                $user = User::findOrFail($request->user_id);
                $asset->update([
                    'request_id' => $request->id,
                    'user_id' => $user->id,
                    'assigned_to' => $user->name,
                    'status' => 'deployed',
                ]);
                Log::info('Asset assigned to user successfully', [
                    'asset_serial' => $asset->serial_num, 
                    'user_name' => $user->name,
                    'user_id' => $user->id
                ]);
            } else {
                Log::warning('Asset not found for approval even after initialization', [
                    'serial_num' => $request->model_serial_num,
                    'request_id' => $request->id
                ]);
                return response()->json(['success' => false, 'message' => 'Asset not found in deployment system'], 404);
            }

            $request->status = 'Approved';
            $request->save();
            Log::info('Request ID ' . $id . ' approved and asset assigned successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error approving request ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error approving request: ' . $e->getMessage()], 500);
        }
    }

    public function reject($id)
    {
        try {
            Log::info('Rejecting request ID: ' . $id);
            $request = RequestAsset::findOrFail($id);
            if ($request->status !== 'Pending') {
                Log::warning('Request ID ' . $id . ' is not pending');
                return response()->json(['success' => false, 'message' => 'Request is not pending'], 403);
            }
            $request->status = 'Rejected';
            $request->save();
            Log::info('Request ID ' . $id . ' rejected successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error rejecting request ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error rejecting request: ' . $e->getMessage()], 500);
        }
    }

    // Debug method to check data
    public function debugAssets()
    {
        $deployedCount = DeployedAsset::count();
        $sourceCount = \App\Models\AssetsManagement::where('status', 'Active')->count();
        $requestCount = RequestAsset::count();
        
        $sourceAssets = \App\Models\AssetsManagement::where('status', 'Active')->get(['id', 'asset_name', 'serial_number']);
        $deployedAssets = DeployedAsset::get(['id', 'serial_num', 'status']);
        $requests = RequestAsset::get(['id', 'model_serial_num', 'status']);
        
        return response()->json([
            'deployed_count' => $deployedCount,
            'source_count' => $sourceCount,
            'request_count' => $requestCount,
            'source_assets' => $sourceAssets,
            'deployed_assets' => $deployedAssets,
            'requests' => $requests
        ]);
    }
}