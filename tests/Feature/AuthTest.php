<?php

namespace Tests\Feature;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public User $user;

    /**
     * TODO:This is __construct. The best practice is to use sqlite and memory storage when make tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function test_auth_user_can_view_posts()
    {
        //TODO: this is a example how to make test

        //Create posts
        Post::factory(10)->create();
        $posts = Post::with('tags', 'categories', 'comments')->paginate();
        $data = PostResource::collection($posts)->resolve();

        //1. Test unauthenticated user can get posts. Response should be 401 forbidden
        $this->json('GET', '/api/posts')->assertStatus(401);

        //2. User Login
        $this->actingAs($this->user);

        //3. Try to get posts. Response must be the same as generated above
        $this->json('GET', '/api/posts')->assertExactJson(['data' => $data]);
    }
}
