<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Period\Period;
use Spatie\Period\Precision;

class MonthlyOccupancyRatesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $month)
    {
        //TODO validate request

        $carbonMonth = Carbon::createFromFormat('Y-m', $month);
        $monthPeriod = Period::make(
            $carbonMonth->copy()->startOfMonth(),
            $carbonMonth->copy()->endOfMonth()
        );

        $bookingsSumDuringMonth = Booking::query()
            ->when($request->room_ids, function ($query) use ($request) {
                $query->whereIn('room_id', $request->room_ids);
            })
            ->where(function ($query) use ($monthPeriod) {
                $query->where(function ($query) use ($monthPeriod) {
                    $query->where('starts_at', '<=', $monthPeriod->start());
                    $query->where('ends_at', '>=', $monthPeriod->start());
                })->orWhere(function ($query) use ($monthPeriod) {
                    $query->where('starts_at', '>=', $monthPeriod->start());
                    $query->where('starts_at', '<=', $monthPeriod->end());
                });
            })
            ->get()
            ->map(function ($booking) use ($monthPeriod) {
                $overlapPeriod = Period::make(
                    $booking->starts_at,
                    $booking->ends_at,
                    Precision::DAY()
                )->overlap($monthPeriod);
                return $overlapPeriod->start()->diff($overlapPeriod->end())->days + 1;
            })->sum();


        $blocksSumDuringMonth = Block::query()
            ->when($request->room_ids, function ($query) use ($request) {
                $query->whereIn('room_id', $request->room_ids);
            })
            ->where(function ($query) use ($monthPeriod) {
                $query->where(function ($query) use ($monthPeriod) {
                    $query->where('starts_at', '<=', $monthPeriod->start());
                    $query->where('ends_at', '>=', $monthPeriod->start());
                })->orWhere(function ($query) use ($monthPeriod) {
                    $query->where('starts_at', '>=', $monthPeriod->start());
                    $query->where('starts_at', '<=', $monthPeriod->end());
                });
            })
            ->get()
            ->map(function ($block) use ($monthPeriod) {
                $overlapPeriod = Period::make(
                    $block->starts_at,
                    $block->ends_at,
                    Precision::DAY()
                )->overlap($monthPeriod);
                return $overlapPeriod->start()->diff($overlapPeriod->end())->days + 1;
            })->sum();

        $roomsCapacityPerDay = Room::query()
            ->when($request->room_ids, function ($query) use ($request) {
                $query->whereIn('id', $request->room_ids);
            })->sum('capacity');

        $numberOfDays = $carbonMonth->daysInMonth;
        $roomsCapacityDuringMonth = $roomsCapacityPerDay * $numberOfDays;

        return [
            'occupancy_rate' => number_format(
                $bookingsSumDuringMonth / ($roomsCapacityDuringMonth  - $blocksSumDuringMonth),
                2
            )
        ];
    }
}
