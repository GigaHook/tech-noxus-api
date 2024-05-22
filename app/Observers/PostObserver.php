<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->forgetCachedPages();
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $this->forgetCachedPages();
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->forgetCachedPages();
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        $this->forgetCachedPages();
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        $this->forgetCachedPages();
    }

    /**
     * Метод забывает кэшированные страницы, проверяя их существование и затем удаляя их из кэша
     */
    private function forgetCachedPages(): void
    {
        $page = 1;
        while (Cache::has("posts-page-".$page)) {
            Cache::forget("posts-page-".$page++);
        }
    }
}
