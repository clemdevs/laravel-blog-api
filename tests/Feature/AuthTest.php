<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    protected $user;

    protected $token;

    public function __construct()
    {
        parent::__construct();

        $this->user = User::factory()->make();

        $this->token = $this->user->createToken('api_token')->plainTextToken;
    }

    /** @test */
    public function test_auth_user_can_view_posts(){
        $this->actingAs($this->user, 'sanctum');
        $this->get('/api/posts');
    }

}
