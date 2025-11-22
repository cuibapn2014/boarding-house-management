<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'username' => ['required','max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255'],
            'phone' => ['nullable','digits:10'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048']
        ]);

        $updateData = [
            'username' => $request->get('username'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email') ,
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'postal' => $request->get('postal'),
            'about' => $request->get('about'),
            'phone' => $request->get('phone')
        ];

        // Handle avatar upload
        if($request->hasFile('avatar')) {
            try {
                $uploadedFile = cloudinary()->upload($request->file('avatar')->getRealPath(), [
                    'folder' => 'avatars',
                    'transformation' => [
                        'width' => 300,
                        'height' => 300,
                        'crop' => 'fill',
                        'gravity' => 'face'
                    ]
                ]);
                
                // Delete old avatar if exists
                if(auth()->user()->avatar) {
                    $publicId = $this->getPublicIdFromUrl(auth()->user()->avatar);
                    if($publicId) {
                        cloudinary()->destroy($publicId);
                    }
                }
                
                $updateData['avatar'] = $uploadedFile->getSecurePath();
            } catch(\Exception $e) {
                return back()->with('error', 'Lỗi khi tải ảnh lên. Vui lòng thử lại.');
            }
        }
        
        DB::beginTransaction();
        try {
            /* @var \App\Models\User $user */
            $user = Auth::user();
            $user->update($updateData);
            DB::commit();
            return back()->with('success', 'Cập nhật hồ sơ thành công');
        } catch(\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật hồ sơ. Vui lòng thử lại.');
        }
    }

    private function getPublicIdFromUrl($url)
    {
        if(empty($url)) return null;
        
        // Extract public_id from Cloudinary URL
        // Example: https://res.cloudinary.com/xxx/image/upload/v123456/avatars/abc123.jpg
        preg_match('/\/avatars\/([^\/]+)\.[^.]+$/', $url, $matches);
        return isset($matches[1]) ? 'avatars/' . $matches[1] : null;
    }
}
