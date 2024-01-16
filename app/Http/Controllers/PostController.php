<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

//TODO сделать сервис
class PostController extends Controller
{
    public function index()
    {
        return Cache::remember('posts-page-'.request('page', 1), 60 * 24, function() {
            return (new PostCollection(Post::paginate(10)))->response();
        });
    }

    public function show(Post $post)
    {
        return (new PostResource($post))->response();
    }

    public function store(Request $request)
    {
        Post::create($request->all());
    }

    public function update(Request $request, Post $post)
    {

    }

    public function delete(Post $post)
    {
        $post->delete();
    }
}
