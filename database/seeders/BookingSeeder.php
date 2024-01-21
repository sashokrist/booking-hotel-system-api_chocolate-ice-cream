<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory()->count(10)->make()->each(function ($booking) {
            $booking->room()->associate(Room::inRandomOrder()->first());
            $booking->customer()->associate(Customer::inRandomOrder()->first());
            $booking->save();
        });
    }
}
