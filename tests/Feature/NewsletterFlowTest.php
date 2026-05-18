<?php

namespace Tests\Feature;

use App\Jobs\SendNewsletterPostJob;
use App\Mail\NewsletterConfirmMail;
use App\Models\Category;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_confirm_and_unsubscribe(): void
    {
        Mail::fake();

        $this->post(route('newsletter.subscribe'), ['email' => 'client@example.com'])
            ->assertRedirect();

        $subscriber = NewsletterSubscriber::query()->where('email', 'client@example.com')->first();

        $this->assertNotNull($subscriber);
        $this->assertSame(NewsletterSubscriber::STATUS_PENDING, $subscriber->status);
        $this->assertNotNull($subscriber->confirm_token);

        Mail::assertSent(NewsletterConfirmMail::class);

        $this->get(route('newsletter.confirm', $subscriber->confirm_token))->assertRedirect(route('home'));

        $subscriber->refresh();
        $this->assertSame(NewsletterSubscriber::STATUS_ACTIVE, $subscriber->status);

        $this->get(route('newsletter.unsubscribe', [
            'subscriber' => $subscriber->id,
            'hash' => sha1($subscriber->email),
        ]))->assertRedirect(route('home'));

        $subscriber->refresh();
        $this->assertSame(NewsletterSubscriber::STATUS_UNSUBSCRIBED, $subscriber->status);
    }

    public function test_published_post_creates_campaign_and_dispatches_jobs_for_active_subscribers(): void
    {
        Queue::fake();

        NewsletterSubscriber::query()->create([
            'email' => 'active@example.com',
            'status' => NewsletterSubscriber::STATUS_ACTIVE,
            'confirmed_at' => now(),
        ]);

        NewsletterSubscriber::query()->create([
            'email' => 'pending@example.com',
            'status' => NewsletterSubscriber::STATUS_PENDING,
            'confirm_token' => 'abc',
        ]);

        $user = User::factory()->create();
        $category = Category::query()->create([
            'name' => 'News',
            'slug' => 'news-cat',
            'type' => 'news',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $post = Post::query()->create([
            'title' => 'New published post',
            'slug' => 'new-published-post',
            'status' => 'published',
            'type' => 'news',
            'author_id' => $user->id,
            'category_id' => $category->id,
            'published_at' => now(),
        ]);

        $this->assertDatabaseHas('newsletter_campaigns', ['post_id' => $post->id]);

        $campaign = NewsletterCampaign::query()->where('post_id', $post->id)->first();
        $this->assertNotNull($campaign);

        Queue::assertPushed(SendNewsletterPostJob::class, 1);
    }
}
