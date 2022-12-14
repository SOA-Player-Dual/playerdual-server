<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use App\Models\OTP;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use PHPViet\NumberToWords\Transformer;
use App\Mail\TransactionOTPMail;
use App\Mail\RegisterOTPMail;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        $otp_code = rand(100000, 999999);
        $store = null;
        $update = false;
        $user_id = $request->user_id;
        $user = User::where('id', $user_id)->first();
        if ($request->type == 'Transaction' && $user->balance < ($request->amount * -1)) {
            return response()->json([
                'message' => 'Your balance is not enough to withdraw',
            ], 400);
        }
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
            $otp->expired_at = Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(5);
            $store = $otp->save();
        }
        if ($store || $update) {
            $mailData['otp'] = $otp_code;
            $mailData['name'] = $user->nickname;
            if ($type != 'Register') {
                $mailData['amountInNumber'] = ($request->amount < 0) ? $request->amount * -1 * 0.9 : $request->amount;
                $mailData['amountInWord'] = (new Transformer())->toCurrency(($request->amount < 0) ? $request->amount * -1 * 0.9 : $request->amount);
                $mailData['fee'] = ($request->amount < 0) ? $request->amount * -1 * 0.1 : 0;
                $mailData['type'] = ($request->amount < 0) ? 'r??t ti???n' : 'n???p ti???n';
                $sent = Mail::to($user->email)->send(new TransactionOTPMail($mailData));
            } else {
                $sent = Mail::to($user->email)->send(new RegisterOTPMail($mailData));
            }
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
            if (Carbon::now('Asia/Ho_Chi_Minh')->lessThan($otp->expired_at)) {
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
