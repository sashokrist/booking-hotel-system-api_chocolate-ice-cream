<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    //use RefreshDatabase;

    public function testIndexReturnsAllRooms()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a token for the user
        $token = $user->createToken('TestToken')->plainTextToken;

        // Create some rooms
        Room::factory()->count(3)->create();

        // Make a GET request to the rooms index route with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/rooms');

       // dd($response->json());
        $this->assertDatabaseCount('rooms', 3);

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }
}
