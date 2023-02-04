<?php

namespace Tests\Feature\Booking;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Room;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StoreBookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_booking_is_stored()
    {
        $room = Room::factory()->create();

        $startsAt = Carbon::create($this->faker->dateTimeBetween('- 1 months', '+ 1 week'));
        $endsAt = $startsAt->copy()->addDays(3);

        $response = $this->postJson(
            route('bookings.store'),
            [
                'room_id' => $room->id,
                'starts_at' => $startsAt->toDateString(),
                'ends_at' => $endsAt->toDateString(),
            ]
        );

        $response->assertCreated();
        $response->assertJson([
            'room_id' => $room->id,
            'starts_at' => $startsAt->toDateString(),
            'ends_at' => $endsAt->toDateString(),
        ]);
    }
}
