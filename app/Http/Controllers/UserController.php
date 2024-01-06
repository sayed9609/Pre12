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
        $this->validate($request,[
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:8'
        ]);

        try {
            User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            return response()->json([
                'status' => 'Successful',
                'message' => 'User Registration Successfully'
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 'Failed',
                'message' => $exception->getMessage()
            ]);
        }
    }

    function UserLogin(Request $request): object
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();

        if ($count == 1) {
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'status' => 'Successful',
                'message' => 'User Login Successfully',
            ])->cookie('token', $token, 60*60);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Wrong Email Or Password'
            ]);
        }
    }

    function SendOTP(Request $request): object
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();

        if ($count == 1) {

            Mail::to($email)->send(new OTPMail($otp));

            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'Successful',
                'message' => 'OTP Send Successfully'
            ]);

        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized User'
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

        if ($count == 1) {

            // update otp to zero
            User::where('otp', '=', $otp)->update(['otp' => '0']);

            //Create token for verifying OTP
            $token = JWTToken::CreateOTPToken($email);

            return response()->json([
                'status' => 'Successful',
                'message' => 'OTP Verification Successful',
                'token' => $token
            ])->cookie('token', $token, 60*60);

        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized User'
            ]);
        }
    }

    function ResetPass(Request $request): object
    {
        try {

            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'Successful',
                'message' => 'Password Reset Successfully'
            ]);


        } catch (Exception $exception) {

            return response()->json([
                'status' => 'Failed',
                'message' => $exception->getMessage()
            ]);

        }
    }


    function Login()
    {
        return view('pages.auth.login-page');
    }

    function Dashboard()
    {
        return view('pages.dashboard.dashboard-page');
    }

    function Registration()
    {
        return view('pages.auth.registration-page');
    }

    function Send_OTP()
    {
        return view('pages.auth.send-otp-page');
    }

    function Verify_OTP()
    {
        return view('pages.auth.verify-otp-page');
    }

    function Reset_Password()
    {
        return view('pages.auth.reset-pass-page');
    }

}
