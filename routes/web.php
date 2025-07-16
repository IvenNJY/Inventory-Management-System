<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\FetchUsersController;
use App\Http\Controllers\AssetsManagementController;
use App\Http\Controllers\RequestAssetController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\DeployedAssetController;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

// --------------------
// Public Routes
// --------------------
Route::get('/', function () {
    return view('index');
})->name('login');

Route::get('/debug-user', function () {
    return auth()->user();
})->middleware('auth');

// --------------------
// Auth & Registration
// --------------------
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// --------------------
// Password Reset
// --------------------
Route::middleware('guest')->group(function (){
    Route::get('/forgot-password', function () {
        return view('forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', [ResetPasswordController::class,'passwordEmail']);

    Route::get('/reset-password/{token}',[ResetPasswordController::class,'passwordReset'])->name('password.reset');

    Route::post('/reset-password',[ResetPasswordController::class,'passwordUpdate'])->name('password.update');

    Route::get('/reset-password', function () {
        return view('reset-password');
    });
});

Route::get('/set-password', function () {
    return view('set_new_password');
});

Route::middleware(['auth', 'can:user-only'])->group(function () {
    // --------------------
    // User Dashboard & Settings
    // --------------------
    Route::get('user/dashboard', function () {
        return view('/user/dashboard');
    })->name('user.dashboard');

    Route::get('user/settings', function () {
        return view('/user/settings');
    });
    
    Route::post('user/settings/update', [FetchUsersController::class, 'updateUserSettings'])->name('user.settings.update');

    Route::get('user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::post('user/request-asset', [RequestAssetController::class, 'store'])->name('user.request-asset');

    Route::post('user/return-asset', [UserDashboardController::class, 'returnAsset'])->name('user.return-asset');

    Route::post('user/mark-notifications-read', [UserDashboardController::class, 'markNotificationsRead'])->name('user.mark-notifications-read');
});

Route::middleware(['auth', 'can:admin-only'])->group(function () {
    // --------------------
    // Admin Dashboard
    // --------------------
    Route::get('admin/dashboard', function () {
        $assets = \App\Models\AssetsManagement::all();
        return view('admin.dashboard', compact('assets'));
    })->name('admin.dashboard');

    // --------------------
    // Assets Management
    // --------------------
    Route::get('admin/assets', [AssetsManagementController::class, 'index'])->name('admin.assets');
    Route::get('admin/add-assets', [AssetsManagementController::class, 'create'])->name('admin.add-assets');
    Route::post('admin/add-assets', [AssetsManagementController::class, 'store'])->name('admin.store-assets');
    Route::get('admin/update-assets/{id}', [AssetsManagementController::class, 'edit'])->name('admin.edit-asset');
    Route::put('admin/update-assets/{id}', [AssetsManagementController::class, 'update'])->name('admin.update-asset');
    Route::delete('admin/delete-assets/{id}', [AssetsManagementController::class, 'destroy'])->name('admin.delete-asset');
    Route::get('admin/assets/export', [AssetsManagementController::class, 'export'])->name('admin.assets.export');
    Route::post('admin/assets/import', [AssetsManagementController::class, 'import'])->name('admin.assets.import');
    Route::post('admin/assets/bulk-delete', [AssetsManagementController::class, 'bulkDelete'])->name('admin.assets.bulk-delete');

    // --------------------
    // Admin Asset Views
    // --------------------
    Route::get('admin/deployment', [DeployedAssetController::class, 'index'])->name('admin.deployment');
    Route::post('admin/deployment/assign', [DeployedAssetController::class, 'assign'])->name('admin.deployment.assign');
    Route::post('admin/deployment/end', [DeployedAssetController::class, 'endDeployment'])->name('admin.deployment.end');

    // Route::get('admin/deployment', function () {
    //     return view('/admin/deployment');
    // });

    Route::get('admin/warranty', function () {
        $assets = \App\Models\AssetsManagement::all();
        return view('admin.warranty-tracking', compact('assets'));
    });

    Route::get('admin/lifespan', function () {
        $assets = \App\Models\AssetsManagement::all();
        return view('admin.lifespan-tracking', compact('assets'));
    });

    // Route::get('admin/requests', function () {
    //     return view('/admin/requests');
    // });

    Route::get('admin/requests', [RequestAssetController::class, 'index'])->name('admin.requests');

    // Debug route for checking asset data
    Route::get('admin/debug-assets', [RequestAssetController::class, 'debugAssets'])->name('admin.debug.assets');
    
    // Asset request routes without CSRF protection
    Route::post('admin/asset-requests/approve/{id}', [RequestAssetController::class, 'approve'])->name('admin.requests.approve');
    Route::post('admin/asset-requests/reject/{id}', [RequestAssetController::class, 'reject'])->name('admin.requests.reject');

    // --------------------
    // Admin User Management
    // --------------------
    Route::get('admin/users', [FetchUsersController::class, 'index'])->name('admin.users');

    Route::get('admin/users/{id}/edit', [FetchUsersController::class, 'edit'])->name('admin.users.edit');

    Route::post('admin/users/{id}', [FetchUsersController::class, 'update'])->name('admin.users.update');

    Route::post('admin/user/import', [FetchUsersController::class, 'import'])->name('admin.users.import');

    Route::post('admin/users', [FetchUsersController::class, 'store'])->name('admin.users.store');

    Route::delete('admin/users/{id}', [FetchUsersController::class, 'destroy'])->name('admin.users.destroy');

    // --------------------
    // Admin Settings
    // --------------------
    Route::get('admin/settings', function () {
        return view('admin.settings');
    });
    Route::post('admin/settings/update', [FetchUsersController::class, 'updateAdminSettings'])->name('admin.settings.update');
});


// --------------------
// Logout
// --------------------
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');