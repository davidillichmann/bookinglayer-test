<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Room;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateBookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_booking_is_updated()
    {
        $booking = Booking::factory()->create();

        $room = Room::factory()->create();
        $startsAt = Carbon::create($this->faker->dateTimeBetween('- 1 months', '+ 1 week'));
        $endsAt = $startsAt->copy()->addDays(3);

        $response = $this->putJson(
            route('bookings.update', $booking),
            [
                'room_id' => $room->id,
                'starts_at' => $startsAt->toDateString(),
                'ends_at' => $endsAt->toDateString(),
            ]
        );

        $response->assertOk();
        $response->assertJson([
            'room_id' => $room->id,
            'starts_at' => $startsAt->toDateString(),
            'ends_at' => $endsAt->toDateString(),
        ]);
    }
}
