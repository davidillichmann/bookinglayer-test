<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * ExampleSeeder seeding example scenario from README.md
 */
class ExampleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Rooms
        $roomA = Room::factory()->create([
            'capacity' => 6,
        ]);

        $roomB = Room::factory()->create([
            'capacity' => 4,
        ]);

        $roomC = Room::factory()->create([
            'capacity' => 2,
        ]);


        // Bookings
        // Room A Bookings
        Booking::factory(3)->for($roomA)->create([
            'starts_at' => Carbon::create('2022-01-01')->toDateString(),
            'ends_at' => Carbon::create('2022-01-05')->toDateString(),
        ]);

        // Room B Bookings
        Booking::factory()->for($roomB)->create([
            'starts_at' => Carbon::create('2022-01-01')->toDateString(),
            'ends_at' => Carbon::create('2022-01-05')->toDateString(),
        ]);
        Booking::factory()->for($roomB)->create([
            'starts_at' => Carbon::create('2022-01-03')->toDateString(),
            'ends_at' => Carbon::create('2022-01-08')->toDateString(),
        ]);

        //Blocks
        Block::factory()->for($roomB)->create([
            'starts_at' => Carbon::create('2022-01-01')->toDateString(),
            'ends_at' => Carbon::create('2022-01-10')->toDateString(),
        ]);
    }
}
