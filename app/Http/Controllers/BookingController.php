<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())->get();

        return response()->json([
            'message' => 'Bookings retrieved successfully',
            'data' => $bookings
        ], 200);
    }

    public function show($id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'data' => $booking
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|integer|exists:trips,id'
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'trip_id' => $request->trip_id,
            'total_amount' => 0,
            'currency' => 'YER',
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10)
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        if ($booking->status != 'pending') {
            return response()->json([
                'message' => 'Only pending bookings can be updated'
            ], 422);
        }

        $request->validate([
            'trip_id' => 'required|integer|exists:trips,id'
        ]);

        Booking::where('id', $id)->update([
            'trip_id' => $request->trip_id
        ]);

        $booking = Booking::find($id);

        return response()->json([
            'message' => 'Booking updated successfully',
            'data' => $booking
        ], 200);
    }

    public function destroy($id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        if ($booking->status != 'pending') {
            return response()->json([
                'message' => 'Only pending bookings can be cancelled'
            ], 422);
        }

        Booking::where('id', $id)->update([
            'status' => 'cancelled',
            'expires_at' => null
        ]);

        return response()->json([
            'message' => 'Booking cancelled successfully'
        ], 200);
    }
}
