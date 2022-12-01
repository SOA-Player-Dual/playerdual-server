<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OTPController;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionSuccessOTPMail;
use PHPViet\NumberToWords\Transformer;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::all();
        return response()->json($transaction, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $topUp = Transaction::firstOrCreate([
            'user' => $request->user,
            'amount' => $request->amount,
            'created_at' => Carbon::now(),
        ]);
        $topUp->id = Str::orderedUuid();
        $store = $topUp->save();

        if ($store) {
            $otpRequest = new Request();
            $otpRequest->user_id = $request->user;
            $otpRequest->type = 'Transaction';
            $otpRequest->actionId = $topUp->id;
            $otpRequest->amount = $request->amount;
            (new OTPController)->sendOTP($otpRequest);
            return response()->json([
                'message' => 'Please check your email for OTP',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Failed to transaction',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topup = Transaction::where('user', $id)
            ->where('amount', '>', 0);

        $withdraw = Transaction::where('user', $id)
            ->where('amount', '<', 0);
        return response()->json([
            'topupTotal' => $topup->sum('amount'),
            'topup' => $topup->get(),
            'withdrawTotal' => $withdraw->sum('amount'),
            'withdraw' => $withdraw->get(),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $otpRequest = new Request();
        $otpRequest->user_id = $id;
        $otpRequest->type = 'Transaction';
        $otpRequest->otp = $request->otp;
        $responseVerify = (new OTPController)->verifyOTP($otpRequest);
        if ($responseVerify->getStatusCode() == 200) {
            $topUp = Transaction::where('id', $responseVerify->original['id'])->first();
            $topUp->updated_at = Carbon::now();
            $update = $topUp->save();
            $user = User::where('id', $id)->first();
            $user->balance = $user->balance + $topUp->amount;
            $user->save();
            $mailData['type'] = ($topUp->amount > 0) ? 'Nạp tiền' : 'Rút tiền';
            $mailData['name'] = $user->name;
            $mailData['amountInNumber'] = ($topUp->amount > 0) ? $topUp->amount : $topUp->amount;
            $mailData['amountInWord'] = (new Transformer)->toCurrency($topUp->amount);
            Mail::to($user->email)->send(new TransactionSuccessOTPMail($mailData));
            if ($update) {
                return response()->json([
                    'balance' => $user->balance,
                    'transaction' => self::show($user->id)->original,
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Failed to Transaction'
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'OTP is invalid or expired'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
