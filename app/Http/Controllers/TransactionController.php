<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OTPController;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $otpRequest->mailData = [
                'amount' => $request->amount,
            ];
            return (new OTPController)->sendOTP($otpRequest);
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
        //
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
            User::where('id', $id)->increment('balance', $topUp->amount);
            if ($update) {
                return response()->json([
                    'message' => 'Transaction successfully'
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
