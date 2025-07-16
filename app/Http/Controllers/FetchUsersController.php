<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FetchUsersController extends Controller
{
    // ...existing code...

    public function updateUserSettings(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->back()->with('status', [
            'success' => true,
            'message' => 'Settings updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $status = [
            'success' => true,
            'message' => 'User deleted successfully!'
        ];
        return redirect()->route('admin.users')->with('status', $status);
    }

    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            // Add more validation as needed
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();
        return redirect()->route('admin.users')->with('status', [
            'success' => true,
            'message' => 'User updated successfully!'
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx',
        ]);
        $status = [
            'success' => false,
            'message' => ''
        ];
        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport, $request->file('import_file'));
            $status['success'] = true;
            $status['message'] = 'Users imported successfully!';
        } catch (\Exception $e) {
            $status['success'] = false;
            $status['message'] = 'Import failed: Please check the file format and content . Remove any duplicate users!' ;
        }
        return redirect('admin/users')->with('status', $status);
    }

    public function updateAdminSettings(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->back()->with('status', [
            'success' => true,
            'message' => 'Settings updated successfully!'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->save();
        $status = [
            'success' => true,
            'message' => 'User created successfully!'
        ];
        return redirect()->route('admin.users')->with('status', $status);
    }
}
