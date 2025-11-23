<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
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
                return redirect()->route('home.index')->with('success', 'Đăng nhập thành công!');
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
                return redirect()->route('home.index')->with('success', 'Tài khoản Google đã được liên kết thành công!');
            }
            
            // Create new user
            $newUser = $this->createUserFromGoogle($googleUser);
            
            Auth::login($newUser);
            return redirect()->route('home.index')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với Nhatrototsaigon.');
            
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }
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
            'password' => null // No password for social login
        ]);
    }
}
