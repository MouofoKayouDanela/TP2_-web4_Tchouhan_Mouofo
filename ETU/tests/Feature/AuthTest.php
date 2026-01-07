<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum ;

 //https://laravel.com/docs/master/http-tests#authentication-assertions
class AuthTest extends TestCase
{
    use RefreshDatabase;
   
  //https://laravel.com/docs/master/database-testing
    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/signup', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'login' => 'jdoe',
            'email' => 'john@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'john@test.com']);
    }

    public function test_register_fails_with_validation_error()
    {
        $response = $this->postJson('/api/signup', []);
        $response->assertStatus(422)->assertJsonStructure(['message','errors']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'login' => 'jdoe',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/signin', [
            'login' => 'jdoe',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token','user']);
    }

    public function test_login_fails_with_wrong_credentials()
    {
        User::factory()->create([
            'login' => 'jdoe',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/signin', [
            'login' => 'jdoe',
            'password' => 'wrong',
        ]);

        $response->assertStatus(401);
    }

    public function test_logout_requires_authentication()
    {
        $this->postJson('/api/signout')->assertStatus(401);
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/signout')->assertStatus(200);
    }

    public function test_signup_is_throttled_after_5_requests()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/signup', [
                'first_name' => 'A',
                'last_name' => 'B',
                'login' => 'user'.$i,
                'email' => "u$i@test.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);
        }

        $this->postJson('/api/signup', [
            'first_name' => 'A',
            'last_name' => 'B',
            'login' => 'overflow',
            'email' => 'overflow@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(429);
    }
}
