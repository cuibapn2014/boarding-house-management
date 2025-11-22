<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('pages.user-create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'min:2', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'firstname' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal' => ['nullable', 'string', 'max:20'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Will be hashed automatically by model
            'firstname' => $validated['firstname'] ?? null,
            'lastname' => $validated['lastname'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'postal' => $validated['postal'] ?? null,
            'about' => $validated['about'] ?? null,
        ]);

        return redirect()->route('page.index', 'user-management')
            ->with('success', 'Thêm người dùng thành công!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user-edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'min:2', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'firstname' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal' => ['nullable', 'string', 'max:20'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);

        $updateData = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'firstname' => $validated['firstname'] ?? null,
            'lastname' => $validated['lastname'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'postal' => $validated['postal'] ?? null,
            'about' => $validated['about'] ?? null,
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = $validated['password']; // Will be hashed by model
        }

        $user->update($updateData);

        return redirect()->route('page.index', 'user-management')
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deletion of admin user
        if ($user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa tài khoản admin!'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công!'
        ]);
    }
}

