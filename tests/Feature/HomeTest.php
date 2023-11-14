<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;
    
    private User $user;
    private Collection $posts;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->posts = Post::factory(10)->create(['user_id' => $this->user->id, 'active' => 1, 'published_at' => date('Y-m-d H:i:s')]);
    }

    public function test_home_has_all_sections(): void
    {

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(__('popular posts'));
        $response->assertSee(__('latest posts'));
        $response->assertSee(__('recommended posts'));
        $response->assertSee(__('recent categories'));
    }

    public function test_newest_post_shows_on_latest_posts_section(): void
    {
        $latestPost = $this->posts->first();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('latestPosts', function ($latestPosts) use ($latestPost) {
            return $latestPosts->contains('title', $latestPost->title);
        });
    }
}
