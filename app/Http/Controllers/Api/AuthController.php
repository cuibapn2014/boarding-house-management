<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Đăng ký tài khoản mới
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());

            // Tạo token cho user
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->responseSuccess('Đăng ký tài khoản thành công!', [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'phone' => $user->phone,
                    'is_admin' => $user->is_admin
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        } catch (\Exception $e) {
            return $this->responseError('Đăng ký tài khoản thất bại. Vui lòng thử lại!');
        }
    }

    /**
     * Đăng nhập
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thông tin đăng nhập không chính xác'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess('Đăng nhập thành công!', [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'address' => $user->address,
                'city' => $user->city,
                'country' => $user->country,
                'postal' => $user->postal,
                'about' => $user->about,
                'is_admin' => $user->is_admin
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Đăng xuất
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Xóa token hiện tại
            $request->user()->currentAccessToken()->delete();

            return $this->responseSuccess('Đăng xuất thành công!');
        } catch (\Exception $e) {
            return $this->responseError('Đăng xuất thất bại. Vui lòng thử lại!');
        }
    }

    /**
     * Lấy thông tin user hiện tại
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->responseSuccess('Lấy thông tin thành công', [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'address' => $user->address,
                'city' => $user->city,
                'country' => $user->country,
                'postal' => $user->postal,
                'about' => $user->about,
                'is_admin' => $user->is_admin
            ]
        ]);
    }

    /**
     * Đổi mật khẩu
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc',
            'new_password.required' => 'Mật khẩu mới là bắt buộc',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp',
        ]);

        $user = $request->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mật khẩu hiện tại không chính xác'
            ], 400);
        }

        // Cập nhật mật khẩu mới
        $user->password = $request->new_password;
        $user->save();

        return $this->responseSuccess('Đổi mật khẩu thành công!');
    }

    /**
     * Cập nhật thông tin profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $user->update($request->only([
            'firstname',
            'lastname',
            'phone',
            'address',
            'city',
            'country',
            'postal',
            'about'
        ]));

        return $this->responseSuccess('Cập nhật thông tin thành công!', [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'address' => $user->address,
                'city' => $user->city,
                'country' => $user->country,
                'postal' => $user->postal,
                'about' => $user->about,
                'is_admin' => $user->is_admin
            ]
        ]);
    }
}

