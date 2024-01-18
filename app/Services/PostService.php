<?php

namespace App\Services;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class PostService 
{
    public function __construct(
        private FileService $fileService = new FileService
    ) {}
    
    public function getAll(): JsonResponse
    {
        return Cache::remember('posts-page-'.request('page', 1), 60 * 4, function() {
            return (new PostCollection(Post::paginate(10)))->response();
        });
    }

    //TODO Разобраться с ответами

    public function get(int $id): JsonResponse
    {
        $post = Cache::rememberForever('post-'.$id, function() use($id) {
            return Post::find($id);
        });
        
        return (new PostResource($post))->response();
    }

    public function create(array $data)
    {
        $data['image'] = $this->fileService->uploadImage();

        $post = Post::create($data);

        Cache::rememberForever('post-'.$post->id, function() use($post) {
            return $post;
        });

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    public function update(array $data, Post $post): Response
    {
        if ($data['image']) {
            $this->fileService->deleteImage($data['image']);
            $data['image'] = $this->fileService->uploadImage();
        }

        $post->update($data);

        if (Cache::has('post-'.$post->id)) {
            Cache::forget('post-'.$post->id);
        }

        Cache::rememberForever('post-'.$post->id, function() use($post){
            return $post;
        });

        return response('', 204);
    }

    public function delete(int $id): Response
    {
        if (Cache::has('post-'.$id)) {
            Cache::get('post-'.$id)->delete();
            Cache::forget('post-'.$id);
        } else {
            Post::find($id)->delete();
        }

        return response('', 204);
    }
}