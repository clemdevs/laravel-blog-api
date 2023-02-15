<?php

namespace Tests\Feature;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public User $user;

    public Collection $posts;

    /**
     * TODO:This is __construct. The best practice is to use sqlite and memory storage when make tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
                      ->hasRoles(new Sequence(['name' => 'Admin'], ['name' => 'User']))->create();

        $this->posts = Post::factory(10)->hasComments(3)->for($this->user)->create();

        Category::factory(7)->create();
    }

    /** @test */
    public function test_auth_user_can_view_posts()
    {
        //create post
        $posts = Post::with('tags', 'categories', 'comments')->paginate();
        $data = PostResource::collection($posts)->resolve();

        //1. Test unauthenticated user can get posts. Response should be 401 forbidden
        $this->json('GET', '/api/posts')->assertStatus(401);

        //2. User Login
        $this->actingAs($this->user);

        //3. Try to get posts. Response must be the same as generated above
        $this->json('GET', '/api/posts')->assertExactJson(['data' => $data]);
    }

    /** @test */
    public function test_auth_user_can_create_posts()
    {

        $attributes = Post::factory()->raw();
        $attributes['tags'] = 'tag1, tag2, tag3';
        $attributes['categories'] = Category::all()->random(5)->implode('name', ', ');

        $this->actingAs($this->user->fetchAdmins()->get()->random()->first());

        $this->json('POST', "/api/posts", $attributes)->assertStatus(201);

        $this->assertDatabaseHas('posts', ['title' => $attributes['title']]);

    }

    /** @test */
    public function test_user_can_view_approved_comment()
    {
        $comment = Comment::find(1)->approved()->first();

        $data = (new CommentResource($comment))->resolve();

        $this->json('GET', "/api/comments/{$comment->id}")->assertStatus(401);

        $this->actingAs($this->user);

        $this->json('GET', "/api/comments/{$comment->id}")->assertExactJson(['data' => $data]);
    }
}
