<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessfulPaymentCreation()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $paymentData = [
            'booking_id' => $booking->id,
            'amount' => 100.00,
            'payment_date' => now()->format('Y-m-d'),
            'status' => 'completed'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/payments', $paymentData);

        $response->assertStatus(201)
            ->assertJsonFragment(['booking_id' => $booking->id]);
    }

    public function testPaymentCreationFailsDueToValidationErrors()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/payments', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['booking_id', 'amount', 'payment_date', 'status']);
    }
}
