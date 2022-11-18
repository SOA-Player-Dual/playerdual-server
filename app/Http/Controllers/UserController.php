<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Player;
use App\Http\Requests\UpdateUserRequest;

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
            ->first();
        if ($user) {
            unset($user['password']);
            unset($user['username']);
            unset($user['email']);
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
            ->first();

        if ($user) {
            unset($user['password']);
            unset($user['username']);
            unset($user['email']);
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
        $user = User::where('id', $id)->first();
        $update = $user->update($request->all());
        if ($update) {
            return response()->json([
                'msg' => 'Update success',
            ], 200);
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
}
