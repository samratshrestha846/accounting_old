<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginController extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_successfully()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->json('POST','/api/login',[
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_not_authenticated_with_validation_error()
    {

        $response = $this->json('POST','/api/login',[

        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_not_authenticated_with_login_fail()
    {

        $response = $this->json('POST','/api/login',[
            'email' => 'abc@gmail.com',
            'password' => 'xyz1243'
        ]);

        $response->assertStatus(401);
    }
}
