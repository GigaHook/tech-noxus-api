<?php

namespace App\Services;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PostService 
{
    public function __construct(
        private FileService $fileService = new FileService
    ) {}
    
    public function getAll(): JsonResponse
    {
        return Cache::remember('posts-page-'.request('page', 1), 60 * 6, function() {
            return new PostCollection(Post::paginate(10));
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
        $data['image'] = $this->fileService->uploadImage();

        $post = Post::create($data);

        return Cache::rememberForever('post-'.$post->id, function() use($post) {
            return new PostResource($post);
        })->response()->setStatusCode(201);
    }

    public function update(array $data, Post $post): JsonResponse
    {
        if (isset($data['image'])) {
            $this->fileService->deleteImage($data['image']);
            $data['image'] = $this->fileService->uploadImage();
        }
        
        $post->update($data);
        
        info(Cache::get('post-'.$post->id));
        Cache::forget('post-'.$post->id);
        info(Cache::get('post-'.$post->id));

        return Cache::rememberForever('post-'.$post->id, function() use($post) {
            return new PostResource($post);
        })->response();
    }

    public function delete(Post $post): JsonResponse
    {
        info(Cache::get('post-'.$post->id));
        Cache::forget('post-'.$post->id);
        info(Cache::get('post-'.$post->id));
        $post->delete();
        return response()->json()->setStatusCode(204);
    }
}