<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
            $user = Auth::user();
            
            // Kiểm tra nếu user bị lock
            if (($user->status ?? 'active') === 'lock') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Nếu request mong muốn JSON response (API request)
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                        'errors' => [
                            'email' => ['Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']
                        ]
                    ], 403);
                }

                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            // Nếu request mong muốn JSON response (API request)
            if ($request->wantsJson()) {
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

            return redirect()->intended('boarding-house')->with('success', 'Đăng nhập thành công!');
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

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists with this Google ID
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // User exists, update avatar if changed
                if ($googleUser->avatar && $user->avatar !== $googleUser->avatar) {
                    $user->update(['avatar' => $googleUser->avatar]);
                }
                
                Auth::login($user);
                return redirect()->intended('boarding-house')->with('success', 'Đăng nhập thành công!');
            }
            
            // Check if user exists with this email
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'provider' => 'google',
                    'email_verified_at' => now()
                ]);
                
                Auth::login($existingUser);
                return redirect()->intended('boarding-house')->with('success', 'Tài khoản Google đã được liên kết thành công!');
            }
            
            // Create new user
            $newUser = $this->createUserFromGoogle($googleUser);
            
            Auth::login($newUser);
            return redirect()->intended('boarding-house')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với Nhatrototsaigon.');
            
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }
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

    /**
     * Create new user from Google data
     */
    private function createUserFromGoogle($googleUser)
    {
        // Split name into firstname and lastname
        $nameParts = explode(' ', $googleUser->name);
        $firstname = array_pop($nameParts);
        $lastname = implode(' ', $nameParts) ?: $firstname;
        
        // Generate unique username from email
        $username = explode('@', $googleUser->email)[0];
        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return User::create([
            'username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'provider' => 'google',
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(10)) // No password for social login
        ]);
    }
}
