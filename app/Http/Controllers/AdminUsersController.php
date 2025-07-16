<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminUsersController extends Controller
{
    /**
     * Delete the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting the currently authenticated user (optional)
            if (auth()->id() === $user->id) {
                return redirect()->route('admin.users.index')->with('status', [
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ]);
            }

            $user->delete();

            return redirect()->route('admin.users.index')->with('status', [
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('status', [
                'success' => false,
                'message' => 'Failed to delete user. Please try again.'
            ]);
        }
    }

    public function import(Request $request)
    {
        Log::info('Import method called'); // Debug log
        try {
            $request->validate([
                'import_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            $file = $request->file('import_file');
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);

            if (!$header || array_diff(['name', 'email', 'password', 'role'], $header)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid CSV format. Expected columns: name, email, password, role.'
                ], 400);
            }

            $users = [];
            $errors = [];
            $line = 1; // Start counting from 1 (header is line 0)
    
            while ($row = fgetcsv($handle)) {
                $line++;
                $userData = [
                    'name' => $row[array_search('name', $header)] ?? '',
                    'email' => $row[array_search('email', $header)] ?? '',
                    'password' => $row[array_search('password', $header)] ?? '',
                    'role' => $row[array_search('role', $header)] ?? '',
                ];

                $validator = Validator::make($userData, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8',
                    'role' => 'required|in:admin,user',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Line $line: " . implode(' ', $validator->errors()->all());
                    continue;
                }

                $users[] = [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some users failed validation:',
                    'errors' => $errors
                ], 400);
            }

            if (empty($users)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid users found in the CSV file.'
                ], 400);
            }

            User::insert($users);

            return response()->json([
                'success' => true,
                'message' => 'Users imported successfully!',
                'count' => count($users)
            ]);

        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during import: ' . $e->getMessage()
            ], 500);
        }
    }
}