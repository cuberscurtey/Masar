<?php

namespace App\Http\Controllers;

use App\Models\TripSeat;
use App\Models\Trip;
use App\Models\BusSeat;
use Illuminate\Http\Request;

class TripSeatController extends Controller
{
    public function index($trip_id)
    {
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        $tripSeats = TripSeat::where('trip_id', $trip_id)->get();

        return response()->json([
            'message' => 'Trip seats retrieved successfully',
            'data' => $tripSeats
        ], 200);
    }

    public function show($id)
    {
        $tripSeat = TripSeat::find($id);

        if (!$tripSeat) {
            return response()->json([
                'message' => 'Trip seat not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Trip seat retrieved successfully',
            'data' => $tripSeat
        ], 200);
    }

    public function store(Request $request, $trip_id)
    {
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        $request->validate([
            'bus_seat_id' => 'required|integer|exists:bus_seats,id'
        ]);

        $busSeat = BusSeat::find($request->bus_seat_id);

        if ($busSeat->bus_id != $trip->bus_id) {
            return response()->json([
                'message' => 'This seat does not belong to the trip bus'
            ], 422);
        }

        $exists = TripSeat::where('trip_id', $trip_id)
            ->where('bus_seat_id', $request->bus_seat_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'This seat already exists in the trip'
            ], 422);
        }

        $tripSeat = TripSeat::create([
            'trip_id' => $trip_id,
            'bus_seat_id' => $request->bus_seat_id,
            'booking_passenger_id' => null,
            'status' => 'available',
            'hold_expires_at' => null
        ]);

        return response()->json([
            'message' => 'Trip seat created successfully',
            'data' => $tripSeat
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $tripSeat = TripSeat::find($id);

        if (!$tripSeat) {
            return response()->json([
                'message' => 'Trip seat not found'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:available,held,booked,blocked'
        ]);

        if ($tripSeat->status == 'booked' &&
            $request->status != 'booked') {

            return response()->json([
                'message' => 'A booked seat cannot be changed directly'
            ], 422);
        }

        TripSeat::where('id', $id)->update([
            'status' => $request->status
        ]);

        $tripSeat = TripSeat::find($id);

        return response()->json([
            'message' => 'Trip seat updated successfully',
            'data' => $tripSeat
        ], 200);
    }

    public function destroy($id)
    {
        $tripSeat = TripSeat::find($id);

        if (!$tripSeat) {
            return response()->json([
                'message' => 'Trip seat not found'
            ], 404);
        }

        if ($tripSeat->status != 'available') {
            return response()->json([
                'message' => 'Only available seats can be deleted'
            ], 422);
        }

        TripSeat::where('id', $id)->delete();

        return response()->json([
            'message' => 'Trip seat deleted successfully'
        ], 200);
    }
}
