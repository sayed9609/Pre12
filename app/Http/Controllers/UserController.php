<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function UserRegistration(Request $request): object
    {
        try {
            User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'User Registration Successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    function UserLogin(Request $request): object
    {
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();

        if ($count == 1) {
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'status' => 'Success',
                'message' => 'User Login Successfully',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Login Failed'
            ]);
        }
    }

    function SendOTPCode(Request $request): object
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();

        if ($count == '1') {

            Mail::to($email)->send(new OTPMail($otp));

            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'Success',
                'message' => 'OTP Send Successfully'
            ]);

        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized'
            ]);
        }
    }

    function VerifyOTP(Request $request): object
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)
            ->count();

        if ($count == '1') {



        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized'
            ]);
        }
    }

}
