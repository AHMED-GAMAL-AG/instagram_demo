<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified'])->except(['show']); // do authentication on all methods except show
    }

    public function index()
    {
        abort(404); // the user should see his posts only not all the posts
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'post_caption' => 'string',
            'image_path' => 'required|image'
        ]);

        // same as below
        // $data['user_id'] = auth()->id();
        // $image_path = request('image_path')->store('uploads', 'public'); // save image to a folder called user using public storage
        // $data['image_path'] = $image_path;
        // Post::create($data);

        $image_path = request('image_path')->store('uploads', 'public'); // save image to a folder called user using public storage

        // auth()->user()->posts()->create([ // ignore error
        //     'post_caption' => $data['post_caption'],
        //     'image_path' => $image_path
        // ]);

        // return redirect()->route('user_profile', ['username' => auth()->user()->username]);

        return view('applyFilters', [
            'post_caption' => $data['post_caption'],
            'image_path' => $image_path,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // if post is not found
        if ($post == null) {
            abort(404);
        }

        if (auth()->user() != null || $post->user->status == 'private') {
            $this->authorize('view', $post);
        }
        return view('posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // if post is not found
        if ($post == null) {
            abort(404);
        }

        $this->authorize('update', $post); // the policy a have created pass to it the post and check if the user is authorized

        return view('posts.edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        abort_if($post == null, 404);

        $this->authorize('update', $post); // the policy a have created pass to it the post and check if the user is authorized

        $data = request()->validate([
            'post_caption' => 'string',
            'image_path' => 'image|nullable'
        ]);

        // if the request has image_path then update it else use old image_path if both not found the 404
        request()->has('image_path') ? $image_path = request('image_path')->store('uploads', 'public') : $image_path = $post->image_path ?? abort(404);
        $data['image_path'] = $image_path;

        $post->update($data);

        return redirect($post->user->username);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        abort_if($post == null, 404);

        $this->authorize('delete', $post); // the policy a have created pass to it the post and check if the user is authorized
        $post->delete();
        Storage::delete("public/" . $post->image_path); // delete the post image

        return redirect($post->user->username);
    }
}
