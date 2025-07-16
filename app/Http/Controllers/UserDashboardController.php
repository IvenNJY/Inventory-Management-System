<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetsManagement;
use App\Models\DeployedAsset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch available assets for the request form
        $availableAssets = AssetsManagement::where('status', 'Active')->get()->map(function ($asset) {
            return [
                'id' => $asset->id, // Use the actual ID from asset_management
                'name' => $asset->asset_name,
                'type' => $asset->type,
                'model' => $asset->model,
                'serial' => $asset->serial_number,
            ];
        })->toArray();

        // Fetch assets assigned to the current user from deployed_asset
        $userAssets = DeployedAsset::where('user_id', $userId)
            ->where('status', 'deployed') // Only show deployed assets
            ->with(['asset', 'user']) // Eager load relationships
            ->get()
            ->map(function ($deployedAsset) {
                return [
                    'name' => $deployedAsset->asset->asset_name,
                    'type' => $deployedAsset->asset->type,
                    'model_sn' => $deployedAsset->asset->model . ' / ' . $deployedAsset->serial_num,
                    'assigned_to' => $deployedAsset->user ? $deployedAsset->user->name : ($deployedAsset->assigned_to ?? 'N/A'),
                    'status' => ucfirst($deployedAsset->status),
                    'can_return' => $deployedAsset->status === 'deployed',
                    'asset_id' => $deployedAsset->asset_id,
                    'serial_num' => $deployedAsset->serial_num,
                ];
            })->toArray();

        // Fetch notifications for assets with status 'ended'
        $returnNotifications = DeployedAsset::where('user_id', $userId)
            ->where('status', 'ended') // Only show notifications for ended assets
            ->with('asset')
            ->get()
            ->map(function ($deployedAsset) {
                return [
                    'asset' => $deployedAsset->asset->asset_name,
                    'model_sn' => $deployedAsset->asset->model . ' / ' . $deployedAsset->serial_num,
                ];
            })->toArray();

        return view('user.dashboard', compact('availableAssets', 'userAssets', 'userId', 'returnNotifications'));
    }

    public function returnAsset(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:deployed_asset,asset_id',
            'serial_num' => 'required|exists:deployed_asset,serial_num',
        ]);

        $userId = Auth::id();
        $deployedAsset = DeployedAsset::where('asset_id', $request->asset_id)
            ->where('serial_num', $request->serial_num)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Update status to 'ended'
        $deployedAsset->status = 'ended';
        $deployedAsset->updated_at = now();
        $deployedAsset->save();

        return redirect()->route('user.dashboard')->with('success', 'Asset returned successfully.');
    }
}