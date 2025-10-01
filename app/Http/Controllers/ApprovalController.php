<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function wait()
    {
        return view('auth.approval-wait');
    }
}
