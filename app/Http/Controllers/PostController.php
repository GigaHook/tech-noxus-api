<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        return $postResource->response()->setStatusCode(Response::HTTP_CREATED);
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

    public function destroy(Post $post): JsonResponse
    {
        $this->postService->delete($post);
        
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}