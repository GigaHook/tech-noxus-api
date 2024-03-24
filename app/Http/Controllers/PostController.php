<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(): JsonResponse
    {
        return $this->postService->getAll()->response();
    }

    public function show(int $id): JsonResponse
    {
        return $this->postService->get($id)->response();
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        $postResource = $this->postService->create(
            $request->validated(),
            $request->file('image'),
        );

        return $postResource->response()->setStatusCode(201);
    }

    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
        $postResource = $this->postService->update(
            $post,
            $request->validated(),
            $request->file('image'),
        );

        return $postResource->response();
    }

    public function delete(Post $post): JsonResponse
    {
        $this->postService->delete($post);
        
        return response()->json()->setStatusCode(204);
    }
}