<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class DailyOccupancyRateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $date)
    {
        //TODO validate request

        $occupancyOnDate = Booking::query()
            ->when($request->room_ids, function ($query) use ($request) {
                $query->whereIn('room_id', $request->room_ids);
            })
            ->where(function ($query) use ($date) {
                $query->where('starts_at', '<=', $date);
                $query->where('ends_at', '>=', $date);
            })->count();

        $blocksOnDate = Block::query()
            ->when($request->room_ids, function ($query) use ($request) {
                $query->whereIn('room_id', $request->room_ids);
            })
            ->where(function ($query) use ($date) {
                $query->where('starts_at', '<=', $date);
                $query->where('ends_at', '>=', $date);
            })->count();

        $totalRoomsCapacity = Room::query()
            ->when($request->room_ids, function ($query) use ($request) {
                $query->whereIn('id', $request->room_ids);
            })->sum('capacity');

        return [
            'occupancy_rate' => number_format(
                $occupancyOnDate / ($totalRoomsCapacity - $blocksOnDate),
                2
            )
        ];
    }
}
