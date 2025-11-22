<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Nếu request mong muốn JSON response (API request)
            if ($request->wantsJson()) {
                $user = Auth::user();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đăng nhập thành công!',
                    'data' => [
                        'user' => [
                            'id' => $user->id,
                            'username' => $user->username,
                            'email' => $user->email,
                            'firstname' => $user->firstname,
                            'lastname' => $user->lastname,
                        ]
                    ]
                ]);
            }

            return redirect()->intended('dashboard')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu request mong muốn JSON response (API request)
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thông tin đăng nhập không chính xác.',
                'errors' => [
                    'email' => ['Thông tin đăng nhập không chính xác.']
                ]
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Nếu request mong muốn JSON response (API request)
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thành công!'
            ]);
        }

        return redirect('/login');
    }
}
