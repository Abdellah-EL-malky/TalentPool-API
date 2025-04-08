<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
use RefreshDatabase;

public function test_user_can_register()
{
$userData = [
'name' => 'John Doe',
'email' => 'john@example.com',
'password' => 'password123',
'role' => 'candidate'
];

$response = $this->postJson('/api/auth/register', $userData);

$response
->assertStatus(201)
->assertJsonStructure([
'user' => ['id', 'name', 'email', 'role'],
'token'
]);

$this->assertDatabaseHas('users', [
'name' => 'John Doe',
'email' => 'john@example.com',
'role' => 'candidate'
]);
}

public function test_user_can_login()
{
$user = User::factory()->create([
'email' => 'test@example.com',
'password' => bcrypt('password123')
]);

$response = $this->postJson('/api/auth/login', [
'email' => 'test@example.com',
'password' => 'password123'
]);

$response
->assertStatus(200)
->assertJsonStructure(['token', 'user']);
}
}
