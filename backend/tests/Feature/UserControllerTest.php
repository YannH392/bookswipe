<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user_without_uuid()
    {
        $response = $this->postJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure(['uuid']);

        $this->assertDatabaseCount('users', 1);
        $this->assertNotNull($response->json('uuid'));
    }

    /** @test */
    public function it_creates_a_user_with_given_uuid()
    {
        $uuid = (string) Str::uuid();

        $response = $this->postJson('/api/users', ['uuid' => $uuid]);

        $response->assertStatus(200)
                 ->assertJson(['uuid' => $uuid]);

        $this->assertDatabaseHas('users', ['id' => $uuid]);
    }

    /** @test */
    public function it_does_not_duplicate_user_with_same_uuid()
    {
        $uuid = (string) Str::uuid();

        $this->postJson('/api/users', ['uuid' => $uuid]);
        $this->postJson('/api/users', ['uuid' => $uuid]);

        $this->assertDatabaseCount('users', 1);
    }
}
