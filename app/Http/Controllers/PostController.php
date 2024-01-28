<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(
        private PostService $service = new PostService
    ) {}

    public function index()
    {
        return $this->service->getAll();
    }

    public function show(int $id)
    {
        return $this->service->get($id);
    }

    public function store(PostStoreRequest $request)
    {
        return $this->service->create($request->safe()->except(['image']));
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        return $this->service->update($request->safe()->except(['image']), $post);
    }

    public function delete(Post $post)
    {
        return $this->service->delete($post);
    }
}
