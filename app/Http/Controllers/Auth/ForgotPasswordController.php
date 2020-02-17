<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        if($request->expectsJson())
        {
            return response()->json(['message' => trans($response)]);
        }

        return back()->with('status', trans($response));
    }

}
