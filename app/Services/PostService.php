<?php

namespace App\Services;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

class PostService 
{
    public function getAll(): PostCollection
    {
        $postCollection = Cache::rememberForever('posts-page-'.request('page', 1), function() {
            return new PostCollection(
                Post::query()->orderBy('created_at', 'desc')->paginate(10)
            );
        });

        return $postCollection;
    }

    public function get(int $id): PostResource
    {
        return Cache::rememberForever('post-'.$id, fn() => new PostResource(Post::find($id)));
    }

    public function create(array $data, UploadedFile $image): PostResource
    {
        $post = Post::create($data);
        $post->storeImage($image);

        return tap(new PostResource($post), function($resource) {
            Cache::forever('post-'.$resource->id, $resource);
        });
    }

    public function update(Post $post, array $data, ?UploadedFile $image): PostResource
    {
        $post->update($data);
        $image && $post->updateImage($image);

        return tap(new PostResource($post), function($resource) {
            Cache::forget('post-'.$resource->id);
            Cache::forever('post-'.$resource->id, $resource);
        });
    }

    public function delete(Post $post): void
    {
        $post->delete();
        Cache::forget('post-'.$post->id);
        if (is_null(Post::firstWhere('image', $post->iamge))) {
            $post->deleteImage();
        }
    }
}