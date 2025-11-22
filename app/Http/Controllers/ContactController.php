<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        return view('apps.contact');
    }

    /**
     * Store a new contact message.
     */
    public function store(ContactRequest $request): JsonResponse
    {
        try {
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return $this->responseSuccess('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.', $contact);
        } catch (\Exception $e) {
            return $this->responseError('Đã xảy ra lỗi. Vui lòng thử lại sau.');
        }
    }
}
