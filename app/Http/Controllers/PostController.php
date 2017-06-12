<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;

class PostController extends Controller
{
    /**
     * Display all post to the administrator
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
     * Show a page to create a new post to the administrator
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.posts.create');
    }

    /**
     * Store new post in the database
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
     * Display a post to the administrator
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
     * Display edit page for a post to the administrator
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('pages.admin.posts.edit', compact('post'));
    }

    /**
     * Update a post
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

        return redirect()->route('post.index')->with('success', 'Post successfully updated.');
    }

    /**
     * Soft delete a chosen post
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

    /**
     * Display all posts publicly
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function all()
    {
        $posts = Post::where('active', 1)->latest()->paginate(10);

        return view('pages.posts.index', compact('posts'));
    }

    /**
     * Soft delete a post (place in trash rather than delete)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trash()
    {
        $posts = Post::onlyTrashed()->orderBy('deleted_at')->paginate(5);

        return view('pages.admin.posts.trash', compact('posts'));
    }

    /**
     * Restore a soft deleted post
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->restore();
        return redirect()->route('post.trash')->with('success', 'Post restored successfully');
    }

    /**
     * Delete a post forever
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('post.trash')->with('success', 'Post deleted forever successfully');
    }
}
