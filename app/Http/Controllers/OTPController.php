<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use App\Models\OTP;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        $otp_code = rand(100000, 999999);
        $store = null;
        $update = false;
        $user_id = $request->user_id;
        $mail = User::select('email')->where('id', $user_id)->first();
        $mailData = $request->mailData;
        $type = $request->type;

        $id = ($type == 'Register') ? Str::orderedUuid() : $request->actionId;

        $otp = ($type == 'Register') ?
            OTP::where('user', $user_id)->where('type', $type)->first() :
            OTP::where('id', $id)->first();

        if ($otp) {
            $otp_code = $otp->otp;
            $update = true;
        } else {
            $otp = new OTP();
            $otp->id = $id;
            $otp->user = $user_id;
            $otp->otp = $otp_code;
            $otp->type = $type;
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
        $otp = OTP::where('user', $request->user_id)
            ->where('otp', $request->otp)
            ->where('type', $request->type)
            ->first();
        if ($otp) {
            $otp->delete();
            if (Carbon::now()->lessThan($otp->expired_at)) {
                return response()->json(
                    ($otp->type == 'Register') ? ['message' => 'OTP is valid'] : ['id' => $otp->id],
                    200
                );
            } else {
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
