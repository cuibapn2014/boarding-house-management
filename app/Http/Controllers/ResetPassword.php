<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Notifications\ForgotPassword;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Log;

class ResetPassword extends Controller
{
    use Notifiable;

    /**
     * Show the reset password form
     */
    public function show()
    {
        return view('auth.reset-password');
    }

    /**
     * Route notification for mail channel
     */
    public function routeNotificationForMail() 
    {
        return request()->email;
    }

    /**
     * Send password reset link to user's email
     */
    public function send(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Send password reset notification
                $this->notify(new ForgotPassword($user->id));
                
                Log::info('Password reset email sent to: ' . $request->email);
                
                return back()->with('succes', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.');
            }
            
            return back()->with('error', 'Không tìm thấy tài khoản với email này.');
            
        } catch (\Exception $e) {
            Log::error('Error sending password reset email: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.');
        }
    }
}
