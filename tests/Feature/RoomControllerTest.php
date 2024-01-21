<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
   use RefreshDatabase;

    public function testRoomCreationSuccessfully()
    {
        $user = User::factory()->create(); // Create a user for authentication
        $token = $user->createToken('TestToken')->plainTextToken;

        $roomData = [
            'number' => '101',
            'type' => 'suite',
            'price_per_night' => 150.00,
            'status' => 'available'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/rooms', $roomData);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'number', 'type', 'price_per_night', 'status']);
    }

    public function testRoomIsSuccessfullyRetrieved()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $room = Room::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/rooms/' . $room->id);

        $response->assertStatus(200)
            ->assertJson($room->toArray());
    }

    public function testRoomNotFoundReturns404()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/rooms/999'); // Assuming 999 is an ID that does not exist

        $response->assertStatus(404)
            ->assertJson(['message' => 'Room not found']);
    }
}

