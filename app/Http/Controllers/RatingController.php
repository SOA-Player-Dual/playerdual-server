<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Contract;
use Carbon\Carbon;
use App\Models\Player;

class RatingController extends Controller
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
        $contract = Contract::where([
            'user' => $request->user,
            'player' => $request->player,
            'status' => 'Completed'
        ])->first();

        if ($contract) {
            $rating = Rating::where('user', $request->user)
                ->where('player', $request->player)->first();
            $player = Player::find($request->player);
            if ($rating) {
                // $rating->comment = $request->comment;
                // $rating->rate = $request->rate;
                // $rating->updated_at = Carbon::now();
                $update = Rating::where('user', $request->user)
                    ->where('player', $request->player)->update([
                        'comment' => $request->comment,
                        'rate' => $request->rate,
                        'updated_at' => Carbon::now()
                    ]);
                $player->avgRate = ($request->rate) / $player->totalRate;
                $player->save();
                if ($update) {
                    return response()->json([
                        'message' => 'Rating has been updated',
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Something went wrong',
                    ], 500);
                }
            } else {
                $rating = new Rating();
                $rating->user = $request->user;
                $rating->player = $request->player;
                $rating->comment = $request->comment;
                $rating->rate = $request->rate;
                $rating->created_at = Carbon::now();
                $store = $rating->save();
                $player->totalRate = $player->totalRate + 1;
                $player->avgRate = ($request->rate + $player->avgRate) / $player->totalRate;
                $player->save();
                if ($store) {
                    return response()->json([
                        'message' => 'Rating has been stored',
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Something went wrong',
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'error' => 'You cannot rate this player',
            ], 403);
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
        $rating = Rating::where('player', $id)
            ->join('User', 'User.id', '=', 'Rating.user')
            ->select('Rating.comment', 'Rating.rate', 'Rating.created_at', 'User.nickname', 'User.avatar', 'User.urlCode')
            ->get();

        if ($rating) {
            return response()->json([
                'rating' => $rating,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Rating not found',
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
}
