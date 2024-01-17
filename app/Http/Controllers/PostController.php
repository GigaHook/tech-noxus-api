<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $this->postService->create($request->all());
    }

    public function update(Request $request, Post $post)
    {
        $this->postService->update($request->all(), $post);
    }

    public function delete(int $id)
    {
        $this->postService->delete($id);
    }
}
