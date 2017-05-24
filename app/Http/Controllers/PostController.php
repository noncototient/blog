<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postsCount = Post::count();
        $deletedPostsCount = Post::onlyTrashed()->count();
        $posts = Post::orderBy('updated_at', 'DESC')->paginate(5);
        return view('pages.admin.posts.index', compact('postsCount','deletedPostsCount', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $request->active
            ? $request->merge(['active' => true, 'published_at' => Carbon::now()])
            : $request->merge(['active' => false, 'published_at' => null]);

        $request->user()->posts()->create($request->all());
        return redirect()->route('post.index')->with('success', 'Your post has been successfully published.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (!$post->isActive) {
            return redirect()->route('post.all');
        }
        return view('pages.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('pages.admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        $request->active
            ? $request->merge(['active' => true, 'published_at' => Carbon::now()])
            : $request->merge(['active' => false, 'published_at' => null]);
        $post->update($request->all());
        return redirect()->route('post.index')->with('Success', 'Post successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->update([
            'active' => false,
            'published_at' => null
        ]);
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Post has been placed in trash.');
    }

    public function all()
    {
        $posts = Post::where('active')->latest()->paginate(10);
        return view('pages.posts.index', compact('posts'));
    }
}
