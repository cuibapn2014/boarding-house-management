<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        auth()->login($user);

        // Nếu request mong muốn JSON response (API request)
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký tài khoản thành công!',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                    ]
                ]
            ], 201);
        }

        return redirect('/dashboard')->with('success', 'Đăng ký tài khoản thành công!');
    }
}
