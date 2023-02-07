<?php

namespace Tests\Feature\Booking;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Database\Seeders\ExampleSeeder;
use Tests\TestCase;

class MonthlyOccupancyRatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_monthly_occupancy_rates_all_rooms()
    {
        $this->seed(ExampleSeeder::class);

        $response = $this->getJson(
            route('monthly-occupancy-rates', '2022-01'),
        );

        $response->assertOk();
        $response->assertJson([
            'occupancy_rate' => 0.07
        ]);
    }

    /**
     * @return void
     */
    public function test_monthly_occupancy_rates_filtered_rooms()
    {
        $this->seed(ExampleSeeder::class);

        $roomB = Room::query()->where('capacity', 4)->firstOrFail();
        $roomC = Room::query()->where('capacity', 2)->firstOrFail();

        $response = $this->getJson(
            route(
                'monthly-occupancy-rates',
                [
                    'month' => '2022-01',
                    'room_ids' => [
                        $roomB->id,
                        $roomC->id,
                    ]
                ]
            ),
        );

        $response->assertOk();
        $response->assertJson([
            'occupancy_rate' => 0.06
        ]);
    }
}
