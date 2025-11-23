<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('home.index');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect to intended page or home
            $intendedUrl = session()->pull('url.intended', route('home.index'));
            
            return redirect($intendedUrl)->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home.index')->with('success', 'Đăng xuất thành công!');
    }
}
