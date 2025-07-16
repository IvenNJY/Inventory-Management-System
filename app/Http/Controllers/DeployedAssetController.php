<?php

namespace App\Http\Controllers;

use App\Models\DeployedAsset;
use App\Models\DeploymentHistory;
use App\Models\User;
use App\Models\RequestAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeployedAssetController extends Controller
{
    public function index()
    {
        // Initialize deployed assets from asset_management
        DeployedAsset::initializeFromAssetManagement();

        $deployedAssets = DeployedAsset::with(['user', 'asset'])->get()->map(function ($asset) {
            if (!$asset->asset) {
                Log::error('Asset not found for deployed_asset', [
                    'deployed_asset_id' => $asset->id,
                    'asset_id' => $asset->asset_id,
                    'serial_num' => $asset->serial_num,
                ]);
            }
            
            $status = $asset->status === 'available' ? 'Available' : ($asset->status === 'ended' ? 'Ended' : 'In Use');
            $statusClass = $asset->status === 'available' ? 'bg-blue-100 text-blue-800' : 
                          ($asset->status === 'ended' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
            $action = $asset->status === 'available' || $asset->status === 'ended' ? 'assign' : 'end';
            
            return [
                'id' => $asset->id,
                'name' => $asset->asset ? $asset->asset->asset_name : 'Unknown (Asset ID: ' . $asset->asset_id . ')',
                'category' => $asset->category,
                'serial' => $asset->serial_num,
                'assigned_to' => ($asset->status === 'available') ? 'Available' : ($asset->assigned_to ?? 'Unknown'),
                'purchase_date' => $asset->asset ? $asset->asset->purchase_date : null,
                'warranty_expiry' => $asset->asset ? $asset->asset->warranty_end_date : null,
                'status' => $status,
                'status_class' => $statusClass,
                'action' => $action,
            ];
        });

        // Get ended deployments for history section
        $deploymentHistory = DeployedAsset::with(['user', 'asset'])
            ->where('status', 'ended')
            ->get()
            ->map(function ($asset) {
                if (!$asset->asset) {
                    Log::error('Asset not found for deployment_history', [
                        'deployed_asset_id' => $asset->id,
                        'asset_id' => $asset->asset_id,
                        'serial_num' => $asset->serial_num,
                    ]);
                }
                return [
                    'name' => $asset->asset ? $asset->asset->asset_name : 'Unknown (Asset ID: ' . $asset->asset_id . ')',
                    'category' => $asset->category,
                    'serial' => $asset->serial_num,
                    'assigned_to' => $asset->assigned_to ?? 'Unknown',
                    'purchase_date' => $asset->asset ? $asset->asset->purchase_date : null,
                    'warranty_expiry' => $asset->asset ? $asset->asset->warranty_end_date : null,
                    'status' => 'Ended',
                    'status_class' => 'bg-yellow-100 text-yellow-800',
                    'updated_at' => $asset->updated_at->format('Y-m-d H:i:s'),
                ];
            });

        $users = User::all()->pluck('name', 'id');

        return view('admin.deployment', compact('deployedAssets', 'deploymentHistory', 'users'));
    }

    public function assign(Request $request)
    {
        try {
            $validated = $request->validate([
                'asset_serial' => 'required|string',
                'assign_user' => 'required|exists:users,id',
            ]);

            $asset = DeployedAsset::where('serial_num', $validated['asset_serial'])->firstOrFail();
            if ($asset->status === 'deployed') {
                return response()->json(['success' => false, 'message' => 'Asset is already assigned'], 403);
            }

            $user = User::findOrFail($validated['assign_user']);
            $asset->update([
                'user_id' => $user->id,
                'assigned_to' => $user->name,
                'status' => 'deployed',
            ]);

            Log::info('Asset assigned', ['serial_num' => $validated['asset_serial'], 'user_id' => $user->id]);
            return response()->json(['success' => true, 'message' => 'Asset assigned successfully']);
        } catch (\Exception $e) {
            Log::error('Error assigning asset: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error assigning asset: ' . $e->getMessage()], 500);
        }
    }

    public function endDeployment(Request $request)
    {
        try {
            $validated = $request->validate([
                'asset_serial' => 'required|string',
            ]);

            $asset = DeployedAsset::where('serial_num', $validated['asset_serial'])->firstOrFail();
            if ($asset->status !== 'deployed') {
                return response()->json(['success' => false, 'message' => 'Asset is not currently deployed'], 403);
            }

            // Set asset status to 'ended' instead of moving to history
            $asset->update([
                'status' => 'ended',
            ]);

            Log::info('Deployment ended', ['serial_num' => $validated['asset_serial']]);
            return response()->json(['success' => true, 'message' => 'Deployment ended successfully']);
        } catch (\Exception $e) {
            Log::error('Error ending deployment: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error ending deployment: ' . $e->getMessage()], 500);
        }
    }
}