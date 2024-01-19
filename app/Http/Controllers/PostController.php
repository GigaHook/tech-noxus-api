<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService = new PostService
    ) {}

    public function index()
    {
        return $this->postService->getAll();
    }

    public function show(int $id)
    {
        return $this->postService->get($id);
    }

    public function store(PostStoreRequest $request)
    {
        return $this->postService->create($request->validated());
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        return $this->postService->update($request->validated(), $post);
    }

    public function delete(Post $post)
    {
        return $this->postService->delete($post);
    }
}
