<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;

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
        try {
            $follow = new Follow();
            $follow->player_id = $request->player_id;
            $follow->user_id = $request->user_id;
            $store = $follow->save();
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
    public function destroy($id)
    {
        //
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
