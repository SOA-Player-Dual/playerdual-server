<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Player;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryPassword;
use App\Models\OTP;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all user sort by avgRate
        $user = Player::with('getGame')
            ->with('user')
            ->orderBy('Player.avgRate', 'desc')->take(8)->get();

        foreach ($user as $key => $value) {
            unset($user[$key]['password']);
            unset($user[$key]['username']);
            unset($user[$key]['email']);
        }
        return response()->json([
            'user' => $user,
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('User.id', $id)
            ->with('player')
            ->with('getGame')
            ->with('contract')
            ->with('follow')
            ->with('post')
            ->first();
        if ($user) {
            return response()->json([
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }
    }

    public function showByURLCode($urlCode)
    {
        $user = User::where('urlCode', $urlCode)
            ->with('getGame')
            ->with('player')
            ->with('contract')
            ->with('follow')
            ->with('post')
            ->first();

        if ($user) {
            return response()->json([
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }
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
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            try {
                $update = $user->update($request->all());
                $dataResponse = User::where('id', $id)
                    ->with('player')
                    ->with('getGame')
                    ->first();
                return response()->json([
                    'user' => $dataResponse,
                ], 200);
            } catch (\Exception $e) {
                if ($request->has('urlCode') && $e->errorInfo[1] == 1062) {
                    return response()->json([
                        'error' => 'Url code already exists',
                    ], 400);
                } else {
                    return response()->json([
                        'error' => 'Failed to update user',
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'error' => 'Update failed',
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

    public function recoverPassword(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if ($user) {
            $otp_code = rand(100000, 999999);
            $otp = OTP::firstOrCreate(
                [
                    'user' => $user->id,
                    'type' => 'Password Recovery',
                ]
            );

            if ($otp->id == "0") {
                $otp->id = Str::orderedUuid();
                $otp->expired_at = Carbon::now()->addMinutes(5);
                $otp->otp = $otp_code;
                $otp->save();
            } else {
                $otp->expired_at = Carbon::now()->addMinutes(5);
                $otp_code = $otp->otp;
                $otp->save();
            }
            $data = [
                'otp' => $otp->otp,
                'username' => $user->username,
            ];
            Mail::to($user->email)->send(new RecoveryPassword($data));
            return response()->json([
                'message' => 'OTP has been sent to your email',
            ], 200);
        } else {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }
    }

    public function verifyRecoverPassword(Request $request)
    {
        $otp = OTP::where('otp', $request->otp)
            ->where('type', 'Password Recovery')
            ->first();
        if ($otp) {
            $otp->delete();
            if (Carbon::now()->lessThan($otp->expired_at)) {
                return response()->json([
                    'userID' => $otp->user,
                    'role' => $otp->getRoles->role,
                ], 200);
            } else {
                return response()->json([
                    'error' => 'OTP has expired',
                ], 400);
            }
        } else {
            return response()->json([
                'error' => 'OTP is invalid',
            ], 404);
        }
    }
}
