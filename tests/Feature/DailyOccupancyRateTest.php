<?php

namespace Tests\Feature\Booking;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Database\Seeders\ExampleSeeder;
use Tests\TestCase;

class DailyOccupancyRateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_daily_occupancy_rates_all_rooms()
    {
        $this->seed(ExampleSeeder::class);

        $response = $this->getJson(
            route('daily-occupancy-rates', '2022-01-02'),
        );

        $response->assertOk();
        $response->assertJson([
            'occupancy_rate' => 0.36
        ]);
    }

    /**
     * @return void
     */
    public function test_daily_occupancy_rates_filtered_rooms()
    {
        $this->seed(ExampleSeeder::class);

        $roomB = Room::query()->where('capacity', 4)->firstOrFail();
        $roomC = Room::query()->where('capacity', 2)->firstOrFail();

        $response = $this->getJson(
            route(
                'daily-occupancy-rates',
                [
                    'date' => '2022-01-06',
                    'room_ids' => [
                        $roomB->id,
                        $roomC->id,
                    ]
                ]
            ),
        );

        $response->assertOk();
        $response->assertJson([
            'occupancy_rate' => 0.2
        ]);
    }
}
