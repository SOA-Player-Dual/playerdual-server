<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostController extends Controller
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
        $post = new Post();
        $post->id = Str::orderedUuid();
        $post->user = $request->user;
        $post->content = $request->content;
        $post->media = $request->media;
        $post->created_at = Carbon::now();
        $store = $post->save();
        if ($store) {
            return response()->json([
                'message' => 'Post has been stored',
            ], 200);
        } else {
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
        $post = Post::where('user', $id)->first();
        if ($post) {
            return response()->json([
                'post' => $post,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Post not found',
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
        $post = Post::where('user', $id)->first();
        if ($post) {
            $post->content = $request->content;
            $post->media = $request->media;
            $post->updated_at = Carbon::now();
            $update = $post->save();
            if ($update) {
                return response()->json([
                    'message' => 'Post has been updated',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'Post not found',
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
        $post = Post::where('user', $id)->first();
        if ($post) {
            $delete = $post->delete();
            if ($delete) {
                return response()->json([
                    'message' => 'Post has been deleted',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'Post not found',
            ], 404);
        }
    }
}