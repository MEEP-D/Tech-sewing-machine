<?php

namespace App\Services;

use App\Mail\NewsletterConfirmMail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Jobs\SendNewsletterPostJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NewsletterService
{
    public function subscribe(string $email): NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::query()->firstOrNew(['email' => strtolower(trim($email))]);
        $subscriber->markPending();
        $subscriber->save();

        $confirmUrl = route('newsletter.confirm', ['token' => $subscriber->confirm_token]);
        app(DynamicMailConfigService::class)->apply();
        Mail::to($subscriber->email)->send(new NewsletterConfirmMail($subscriber, $confirmUrl));

        return $subscriber;
    }

    public function confirm(string $token): ?NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::query()->where('confirm_token', $token)->first();
        if (! $subscriber) {
            return null;
        }

        $subscriber->markActive();
        $subscriber->save();

        return $subscriber;
    }

    public function unsubscribe(NewsletterSubscriber $subscriber): void
    {
        if ($subscriber->status === NewsletterSubscriber::STATUS_UNSUBSCRIBED) {
            return;
        }

        $subscriber->markUnsubscribed();
        $subscriber->save();
    }

    public function queueCampaignForPost(Post $post): void
    {
        if ($post->status !== 'published' || ! $post->published_at || $post->published_at->isFuture()) {
            return;
        }

        DB::transaction(function () use ($post): void {
            $campaign = NewsletterCampaign::query()->firstOrCreate(
                ['post_id' => $post->id],
                [
                    'subject' => 'Tin mới: ' . $post->title,
                    'queued_at' => now(),
                ]
            );

            if (! $campaign->wasRecentlyCreated) {
                return;
            }

            NewsletterSubscriber::query()
                ->where('status', NewsletterSubscriber::STATUS_ACTIVE)
                ->select('id')
                ->chunkById(200, function ($subscribers) use ($campaign): void {
                    foreach ($subscribers as $subscriber) {
                        SendNewsletterPostJob::dispatch($campaign->id, $subscriber->id);
                    }
                });
        });
    }

    public function queueCampaignsForDuePosts(): int
    {
        $queued = 0;

        Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereDoesntHave('newsletterCampaign')
            ->orderBy('id')
            ->chunkById(100, function ($posts) use (&$queued): void {
                foreach ($posts as $post) {
                    $this->queueCampaignForPost($post);
                    $queued++;
                }
            });

        return $queued;
    }
}
