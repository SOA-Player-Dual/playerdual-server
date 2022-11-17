<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rating;
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
            ->join('Player', 'Player.id', '=', 'User.id')
            ->select('User.*', 'Player.*')
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
            ->join('Player', 'Player.id', '=', 'User.id')
            ->select('User.*', 'Player.*')
            ->first();

        if ($user) {
            return response()->json([
                'gender' => $user->gender,
                'nickname' => $user->nickname,
                'dateOfBirth' => $user->dateOfBirth,
                'language' => $user->language,
                'nation' => $user->nation,
                'avatar' => $user->avatar,
                'dateJoin' => $user->dateJoin,
                'fee' => $user->fee,
                'name' => $user->name,
                'description' => $user->description,
                'status' => $user->status,
                'hiredTime' => $user->hiredTime,
                'completeRate' => $user->completeRate,
                'album' => $user->album,
                'devives' => $user->devives,
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

    public function getAll()
    {

        $user = User::join('Player', 'Player.id', '=', 'User.id')
            ->select('User.avatar', 'User.urlCode', 'Player.name', 'Player.fee', 'Player.description')
            ->orderBy('Player.completeRate', 'desc')
            ->take(8)->get();
        // get all user order by rating
        // $users = User::join('Player', 'Player.id', '=', 'User.id')
        //     ->join('Rating', 'Rating.player', '=', 'User.id')
        //     ->select('User.*', 'Player.*', 'AVG(Rating.rate) as rating')
        //     ->orderBy('Rating.rate', 'desc')
        //     ->paginate(10);

        // $user = User::join('Rating', 'Rating.player', '=', 'User.id')
        //     ->join('Player', 'Player.id', '=', 'User.id')
        //     ->select('User.*', 'Player.*')
        //     ->groupBy('User.id')
        //     ->orderByRaw('AVG(Rating.rate) DESC')
        //     ->take(10)
        //     ->get();


        return response()->json([
            'user' => $user,
        ], 200);
    }
}
