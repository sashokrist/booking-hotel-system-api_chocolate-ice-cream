<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchAllCustomers()
    {
        // Create a user for authentication
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        // Create a few customers
        Customer::factory()->count(3)->create();

        // Make a GET request to the customers index route
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/customers');

        $response->assertStatus(200);

        $response->assertStatus(200);

        $customers = $response->json();
        $this->assertIsArray($customers);
        $this->assertCount(3, $customers);
    }

    public function testSuccessfulCustomerCreation()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $customerData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone_number' => '1234567890'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/customers', $customerData);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'johndoe@example.com']);
    }
}
