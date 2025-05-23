<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function index()
    {
        return view('apps.contact');
    }

    public function store(Request $request)
    {
        return $this->responseSuccess('Thêm mới thành công!');
    }
}
