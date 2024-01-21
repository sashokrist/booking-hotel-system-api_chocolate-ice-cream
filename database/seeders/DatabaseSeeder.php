<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@test.com',
             'email_verified_at' => now(),
             'password' => 'password',
             'remember_token' => Str::random(10),
         ]);

        $this->call([
            RoomSeeder::class,
            CustomerSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
