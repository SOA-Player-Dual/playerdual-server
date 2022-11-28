<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlayerGame;
use App\Http\Controllers\UserController;

class PlayerGameController extends Controller
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
        PlayerGame::where('player', $request->player)->delete();
        foreach ($request->game as $key => $value) {
            try {
                $playerGame = new PlayerGame();
                $playerGame->player = $request->player;
                $playerGame->game = $value;
                $playerGame->save();
            } catch (\Exception $e) {
            }
        }
        return (new UserController())->show($request->player);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $players = PlayerGame::where('game', $id)
            ->join('Player', 'Player.id', '=', 'PlayerGame.player')
            ->select('Player.*')
            ->with('user')
            ->get();
        return response()->json([
            'user' => $players,
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
    public function update(Request $request)
    {
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
