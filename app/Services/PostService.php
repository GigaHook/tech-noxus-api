<?php

namespace App\Services;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PostService 
{
    public function getAll(): JsonResponse
    {
        return Cache::rememberForever('posts-page-'.request('page', 1), function() {
            return new PostCollection(
                Post::query()->orderBy('created_at', 'desc')->paginate(10)
            );
        })->response();
    }

    public function get(int $id): JsonResponse
    {
        return Cache::rememberForever('post-'.$id, function() use($id) {
            return new PostResource(Post::find($id));
        })->response();
    }

    public function create(array $data): JsonResponse
    {
        $post = Post::create($data);

        $post->storeImage(request()->file('image'));
        
        return Cache::rememberForever('post-'.$post->id, function() use($post) {
            return new PostResource($post);
        })->response()->setStatusCode(201);
    }

    public function update(array $data, Post $post): JsonResponse
    {
        $post->update($data);

        request()->hasFile('image') && $post->updateImage(request()->file('image'));

        Cache::forget('post-'.$post->id);

        return Cache::rememberForever('post-'.$post->id, function() use($post) {
            return new PostResource($post);
        })->response();
    }

    public function delete(Post $post): JsonResponse
    {
        $post->delete();
        $post->deleteImage();
        Cache::forget('post-'.$post->id);

        return response()->json()->setStatusCode(204);
    }
}