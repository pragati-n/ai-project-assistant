<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_request_returns_401(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_invalid_bearer_token_returns_401(): void
    {
        $this->withToken('invalid-token')
            ->getJson('/api/user')
            ->assertUnauthorized();
    }

    public function test_valid_personal_access_token_returns_authenticated_user_without_secrets(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('email', $user->email)
            ->assertJsonMissingPath('password');
    }
}
