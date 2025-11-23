<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Display user profile page
     */
    public function show()
    {
        $user = Auth::user();
        $savedListingsCount = $user->savedListings()->count();
        
        return view('pages.user-profile', compact('user', 'savedListingsCount'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $attributes = $request->validate([
            'firstname' => ['required', 'string', 'max:100', 'min:2'],
            'lastname' => ['required', 'string', 'max:100', 'min:2'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:15', 'min:10'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'about' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ], [
            'firstname.required' => 'Vui lòng nhập tên',
            'firstname.min' => 'Tên phải có ít nhất 2 ký tự',
            'lastname.required' => 'Vui lòng nhập họ',
            'lastname.min' => 'Họ phải có ít nhất 2 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 số',
            'current_password.required_with' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        // Check current password if changing password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
            }
            $attributes['password'] = $request->new_password;
        }

        // Remove password fields from attributes if not changing
        unset($attributes['current_password'], $attributes['new_password']);

        $user->update($attributes);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
