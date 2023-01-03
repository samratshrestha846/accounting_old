<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileController extends TestCase
{
    use RefreshDatabase;

    public function test_it_get_user_profile_data()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get('/api/auth/me/profile');

        $response->assertStatus(200);
    }

    public function test_it_get_anauthenticated_error()
    {
        $response = $this->get('/api/auth/me/profile');

        $response->assertStatus(401);
    }
}
