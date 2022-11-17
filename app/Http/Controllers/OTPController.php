<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use App\Models\OTP;
use Carbon\Carbon;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        $otp_code = rand(100000, 999999);
        $user_id = $request->user_id;
        $mail = $request->mail;
        if ($user_id == null) {
            return response()->json([
                'error' => 'User ID is required',
            ], 422);
        }
        if ($mail == null) {
            return response()->json([
                'error' => 'Mail is required',
            ], 422);
        }

        $store = null;
        $update = null;

        $otp = OTP::where('user', $user_id)->first();
        if ($otp) {
            $otp->otp = $otp_code;
            $update = $otp->save();
        } else {
            $otp = new OTP();
            $otp->user = $user_id;
            $otp->mail = $mail;
            $otp->otp = $otp_code;
            $otp->expired_at = Carbon::now()->addMinutes(5);
            $store = $otp->save();
        }
        if ($store || $update) {
            $sent = Mail::to($mail)->send(new OTPMail([
                'otp' => $otp_code,
            ]));
            if ($sent) {
                return response()->json([
                    'message' => 'OTP has been sent to your mail',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $otp = OTP::where('user', $request->user_id)->where('otp', $request->otp)->first();
        if ($otp) {
            if (Carbon::now()->lessThan($otp->expired_at)) {
                $otp->delete();
                return response()->json([
                    'message' => 'OTP is valid',
                ], 200);
            } else {
                $otp->delete();
                return response()->json([
                    'error' => 'OTP is expired',
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'OTP is invalid',
            ], 422);
        }
    }
}
