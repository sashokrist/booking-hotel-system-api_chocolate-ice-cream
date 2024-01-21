<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchAllBookings()
    {
        // Create a user for authentication
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        // Create a few bookings
        Booking::factory()->count(1)->create();

        // Make a GET request to the bookings index route
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/bookings');
        //dd($response->json());
        $this->assertDatabaseCount('bookings', 1);

        // Assert that the response has a 200 status code and contains all bookings
        $response->assertJsonCount(1);
    }

    public function testSuccessfulBookingCreation()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['status' => 'available']);
        $customer = Customer::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $bookingData = [
            'room_id' => $room->id,
            'customer_id' => $customer->id,
            'check_in_date' => now()->addDays(1)->format('Y-m-d'),
            'check_out_date' => now()->addDays(3)->format('Y-m-d'),
            'total_price' => 300.00
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/bookings', $bookingData);

        $response->assertStatus(201)
            ->assertJsonFragment(['room_id' => $room->id]);
    }
}
