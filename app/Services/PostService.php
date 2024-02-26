<?php

namespace App\Services;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasMedia;

/**
 * Кеш автоматом очищается в обсервере
 */
class PostService 
{
    use HasMedia;

    public function __construct(
        ImageService $imageService = new ImageService,
    ) {}

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
        //$post = Post::create([
        //    ...$data,
        //    'image' => $this->storeImage(request()->file('image')),
        //]);

        $post = Post::create($data);

        //TODO посмотреть что будет в масива images

        return Cache::rememberForever('post-'.$post->id, function() use($post) {
            return new PostResource($post);
        })->response()->setStatusCode(201);
    }

    public function update(array $data, Post $post): JsonResponse
    {
        $image = request()->file('image');
        
        if ($image) {
            $data['image'] = $this->updateImage($image, $post->image);
        }

        $post->update($data);

        Cache::forget('post-'.$post->id);

        return Cache::rememberForever('post-'.$post->id, function() use($post) {
            return new PostResource($post);
        })->response();
    }

    public function delete(Post $post): JsonResponse
    {
        $this->deleteImage($post->image);
        Cache::forget('post-'.$post->id);
        $post->delete();

        return response()->json();
    }
}