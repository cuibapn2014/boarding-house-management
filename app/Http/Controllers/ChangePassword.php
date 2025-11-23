<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Requests\ResetPasswordRequest;

class ChangePassword extends Controller
{
    protected $user;

    public function __construct()
    {
        // Logout any authenticated user
        Auth::logout();

        // Verify the signed URL and get user ID
        if (request()->hasValidSignature()) {
            $id = intval(request()->id);
            $this->user = User::find($id);
        } else {
            $this->user = null;
        }
    }

    /**
     * Show the change password form
     */
    public function show(Request $request)
    {
        // Check if the URL signature is valid
        if (!$request->hasValidSignature()) {
            return redirect()->route('login')
                ->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu liên kết mới.');
        }

        // Check if user exists
        if (!$this->user) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy người dùng. Vui lòng thử lại.');
        }

        return view('auth.change-password', ['userId' => $this->user->id]);
    }

    /**
     * Update user password
     */
    public function update(ResetPasswordRequest $request)
    {
        try {
            // Verify the signed URL again
            if (!$request->hasValidSignature()) {
                return redirect()->route('login')
                    ->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
            }

            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->with('error', 'Email không tồn tại trong hệ thống.');
            }

            // Verify that the user ID in URL matches the email provided
            if ($user->id != intval($request->id)) {
                return back()->with('error', 'Email không khớp với yêu cầu đặt lại mật khẩu.');
            }

            // Update password
            $user->update([
                'password' => $request->password
            ]);

            Log::info('Password successfully reset for user: ' . $user->email);

            return redirect()->route('login')
                ->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập với mật khẩu mới.');

        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đặt lại mật khẩu. Vui lòng thử lại.');
        }
    }
}
