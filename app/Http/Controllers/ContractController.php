<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContractNotificationMail;

class ContractController extends Controller
{
    const contractStatus = ['Pending', 'Processing', 'Completed', 'Canceled'];
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
        $pendingContract = Contract::where(['player' => $request->player, 'user' => $request->user, 'status' => 'Pending'])->first();
        $processingContract = Contract::where(['player' => $request->player, 'user' => $request->user, 'status' => 'Processing'])->first();
        $playerMail = User::where('id', $request->player)->first()->email;
        if (!$pendingContract && !$processingContract) {
            try {
                $fee = Player::select('fee')->where('id', $request->player)->first();
                $contract = new Contract();
                $contract->id = Str::orderedUuid();
                $contract->user = $request->user;
                $contract->player = $request->player;
                $contract->time = $request->time;
                $contract->fee = $fee->fee;
                $contract->status = self::contractStatus[0];
                $contract->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                $store = $contract->save();
                $contractResponse = Contract::where('user', $contract->user)
                    ->where('status', self::contractStatus[0])
                    ->orWhere('status', self::contractStatus[1])
                    ->select('player', 'status')
                    ->get();
                if ($store) {
                    $mailData['name'] = User::select('nickname')->where('id', $contract->user)->first()->nickname;
                    $mailData['time'] = $contract->time;
                    $mailData['fee'] = $contract->fee * $contract->time;
                    Mail::to($playerMail)->send(new ContractNotificationMail($mailData));
                    return response()->json([
                        'contract' => $contractResponse
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Contract creation failed'
                    ], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    // 'error' => 'Something went wrong',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'You are in the process of hiring this player',
            ], 400);
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
        $contract = Contract::where('Contract.id', "$id")
            ->join('User as player', 'player.id', '=', 'Contract.player')
            ->join('User as user', 'user.id', '=', 'Contract.user')
            ->select('Contract.*', 'player.urlCode as playerURLCode', 'player.avatar as playerAvatar', 'player.nickName as playerNickName', 'user.urlCode as userURLCode', 'user.avatar as userAvatar', 'user.nickName as userNickName')
            ->get();
        if ($contract) {
            return response()->json([
                'contract' => $contract,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Contract not found',
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
    public function update(Request $request, $id)
    {
        $contract = Contract::find($id);
        if ($contract) {
            if ($contract->status == self::contractStatus[2]) {
                return response()->json([
                    'error' => 'Contract has been completed',
                ], 400);
            }
            if ($contract->status == self::contractStatus[3]) {
                return response()->json([
                    'error' => 'Contract has been canceled',
                ], 400);
            }
            if ($request->status == self::contractStatus[2]) {
                if ($contract->status == self::contractStatus[1]) {
                    $completeTime = Carbon::parse($contract->created_at)->addMinutes($contract->time * 60);
                    if (Carbon::now('Asia/Ho_Chi_Minh')->isAfter($completeTime)) {
                        $user = User::where('id', '=', $contract->user)->first();
                        $player = User::where('id', '=', $contract->player)->first();
                        $contract->status = $request->status;
                        $update = $contract->save();
                        $user->balance = $user->balance - ($contract->fee * $contract->time);
                        $player->balance = $player->balance + ($contract->fee * $contract->time);
                        Player::find($contract->player)->increment('hiredTime', $contract->time);
                        $user->save();
                        $player->save();
                        $contractResponse = Contract::where('user', $contract->user)
                            ->where('status', self::contractStatus[0])
                            ->orWhere('status', self::contractStatus[1])
                            ->select('player', 'status')
                            ->get();
                        if ($update) {
                            return response()->json([
                                'contract' => $contractResponse
                            ], 200);
                        } else {
                            return response()->json([
                                'error' => 'Contract completion failed'
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'error' => 'Contract is not completed yet'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'error' => 'Contract is not in the process of being completed'
                    ], 400);
                }
            }
            if ($request->status == self::contractStatus[3]) {
                $contract->status = $request->status;
                $update = $contract->save();
                if ($update) {
                    return response()->json([
                        'message' => 'Contract canceled successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Contract cancellation failed'
                    ], 500);
                }
            }
            if ($contract->player == $request->actor_id) {
                if ($contract->status == self::contractStatus[0] && ($request->status == self::contractStatus[1] || $request->status == self::contractStatus[3])) {
                    $contract->status = $request->status;
                    $update = $contract->save();
                    if ($update) {
                        return response()->json([
                            'data' => self::showByUserPlayerID($request->actor_id)->original['data']
                        ], 200);
                    } else {
                        return response()->json([
                            'error' => 'Contract update failed'
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'error' => 'Contract is not pending'
                    ], 400);
                }
            } else {
                return response()->json([
                    'error' => 'Something went wrong'
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'Contract not found'
            ], 404);
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

    public function showByUserID($id)
    {
        $contract = Contract::where('user', $id)
            ->join('User', 'User.id', '=', 'Contract.player')
            ->select('Contract.*', 'User.nickname as user_name', 'User.urlCode as user_url_code', 'User.avatar as avatar')
            ->get();
        if ($contract) {
            return response()->json([
                'data' => $contract,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Contract not found'
            ], 404);
        }
    }

    public function showByUserPlayerID($id)
    {
        $contract = Contract::where('player', $id)
            ->join('User', 'User.id', '=', 'Contract.user')
            ->select('Contract.*', 'User.nickname as user_name', 'User.urlCode as user_url_code', 'User.avatar as avatar')
            ->get();
        if ($contract) {
            return response()->json([
                'data' => $contract,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Contract not found'
            ], 404);
        }
    }
}
