<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donate;
use App\Http\Requests\DonateRequest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class DonateController extends Controller
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
    public function store(DonateRequest $request)
    {
        try {
            $donate = new Donate();
            $donate->id = Str::orderedUuid();
            $donate->user = $request->user;
            $donate->player = $request->player;
            $donate->money = $request->money;
            $donate->displayName = $request->displayName;
            $donate->message = $request->message;
            $donate->created_at = Carbon::now();
            $store = $donate->save();
            if ($store) {
                $user = User::find($request->user);
                $user->donateTotal = $user->donateTotal + $request->money;
                $user->save();
                return response()->json([
                    'message' => 'Donation has been stored',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Something went wrong',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
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
        //get 10 row from donate order by sum of money desc
        $donate = Donate::select('Donate.user', Donate::raw('SUM(Donate.money) as donateTotal'))
            ->where('Donate.player', $id)
            ->groupBy('Donate.user')
            ->orderByRaw('donateTotal DESC')
            ->with('user')
            ->take(10)
            ->get();

        //unset password, username and email
        // foreach ($donate as $key => $value) {
        //     unset($donate[$key]['password']);
        //     unset($donate[$key]['username']);
        //     unset($donate[$key]['email']);
        // }

        return response()->json([
            'donate' => $donate,
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
