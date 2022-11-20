<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\Player;

class FollowController extends Controller
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
        if ($request->player_id == $request->user_id) {
            return response()->json([
                'message' => 'You cannot follow yourself'
            ], 400);
        }
        try {
            $follow = new Follow();
            $follow->player_id = $request->player_id;
            $follow->user_id = $request->user_id;
            $store = $follow->save();

            $player = Player::find($request->player_id);
            $player->follower = $player->follower + 1;
            $player->save();

            if ($store) {
                return response()->json([
                    'message' => 'Followed',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Failed to follow',
                ], 500);
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'error' => 'Already followed',
                ], 409);
            }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $follow = Follow::where('player_id', $request->player_id)
            ->where('user_id', $request->user_id);

        if ($follow) {
            $player = Player::find($request->player_id);
            $player->follower = $player->follower - 1;
            $player->save();
            $delete = Follow::where('player_id', $request->player_id)
                ->where('user_id', $request->user_id)->delete();
            if ($delete) {
                return response()->json([
                    'message' => 'Unfollowed',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Failed to unfollow',
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'Failed to unfollow',
            ], 500);
        }
    }

    public function showFollower($id)
    {
        $follower = Follow::where('player_id', $id)
            ->join('User', 'User.id', '=', 'Follow.user_id')
            ->select('User.avatar', 'User.nickname', 'User.urlCode')
            ->get();
        return response()->json([
            'follower' => $follower->count(),
            'followerData' => $follower,
        ], 200);
    }

    public function showFollowing($id)
    {
        $following = Follow::where('user_id', $id)
            ->join('User', 'User.id', '=', 'Follow.player_id')
            ->select('User.avatar', 'User.nickname', 'User.urlCode')
            ->get();
        return response()->json([
            'following' => $following->count(),
            'followingData' => $following,
        ], 200);
    }
}
