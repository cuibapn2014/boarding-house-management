<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display registration page
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('home.index');
        }
        
        return view('auth.register');
    }

    /**
     * Handle registration request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'firstname' => 'required|string|max:255|min:2',
            'lastname' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15|min:10',
            'password' => 'required|min:6|max:255|confirmed',
            'terms' => 'required|accepted'
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
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'terms.required' => 'Vui lòng đồng ý với điều khoản sử dụng',
            'terms.accepted' => 'Vui lòng đồng ý với điều khoản sử dụng',
        ]);

        // Create username from email
        $username = explode('@', $attributes['email'])[0];
        
        // Check if username exists, append number if needed
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'username' => $username,
            'firstname' => $attributes['firstname'],
            'lastname' => $attributes['lastname'],
            'email' => $attributes['email'],
            'phone' => $attributes['phone'],
            'password' => $attributes['password'], // Will be hashed by User model
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect()->route('home.index')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với Nhatrototsaigon.');
    }
}
