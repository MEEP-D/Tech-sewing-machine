<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\NewsletterService;

class PostObserver
{
    public function created(Post $post): void
    {
        app(NewsletterService::class)->queueCampaignForPost($post);
    }

    public function updated(Post $post): void
    {
        if (! $post->wasChanged(['status', 'published_at'])) {
            return;
        }

        app(NewsletterService::class)->queueCampaignForPost($post);
    }
}
