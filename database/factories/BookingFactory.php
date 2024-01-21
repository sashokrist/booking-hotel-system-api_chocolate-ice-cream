<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'room_id' => \App\Models\Room::factory(),
            'customer_id' => \App\Models\Customer::factory(),
            'check_in_date' => $startDate = $this->faker->dateTimeThisYear(),
            'check_out_date' => $this->faker->dateTimeInInterval($startDate, '+7 days'),
            'total_price' => $this->faker->randomFloat(2, 100, 500)
        ];
    }
}
