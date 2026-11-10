<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPassenger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingPassengerController extends Controller
{
    public function index($booking_id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($booking_id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        $passengers = BookingPassenger::where('booking_id', $booking_id)->get();

        return response()->json([
            'message' => 'Passengers retrieved successfully',
            'data' => $passengers
        ], 200);
    }

    public function show($id)
    {
        $passenger = BookingPassenger::whereHas('booking', function ($query) {
            $query->where('user_id', auth()->id());
        })->find($id);

        if (!$passenger) {
            return response()->json([
                'message' => 'Passenger not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Passenger retrieved successfully',
            'data' => $passenger
        ], 200);
    }

    public function store(Request $request, $booking_id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($booking_id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        if ($booking->status != 'pending' ||
            $booking->expires_at <= now()) {

            return response()->json([
                'message' => 'This booking is not available'
            ], 422);
        }

        $request->validate([
            'full_name' => 'required|string|max:200',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'document_type' => 'nullable|in:national_id,passport,family_card,other',
            'document_number' => 'nullable|string|max:100',
            'document_expiry_date' => 'nullable|date|after:today',
            'phone' => 'nullable|string|max:30',
            'passenger_type' => 'sometimes|in:adult,child'
        ]);

        $passenger = BookingPassenger::create([
            'booking_id' => $booking_id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'document_expiry_date' => $request->document_expiry_date,
            'phone' => $request->phone,
            'passenger_type' => $request->passenger_type ?? 'adult',
            'fare_amount' => 0,
            'ticket_number' => 'MASAR-' . strtoupper(Str::random(12))
        ]);

        return response()->json([
            'message' => 'Passenger added successfully',
            'data' => $passenger
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $passenger = BookingPassenger::whereHas('booking', function ($query) {
            $query->where('user_id', auth()->id());
        })->find($id);

        if (!$passenger) {
            return response()->json([
                'message' => 'Passenger not found'
            ], 404);
        }

        if ($passenger->booking->status != 'pending' ||
            $passenger->booking->expires_at <= now()) {

            return response()->json([
                'message' => 'Passenger cannot be updated'
            ], 422);
        }

        $request->validate([
            'full_name' => 'required|string|max:200',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'document_type' => 'nullable|in:national_id,passport,family_card,other',
            'document_number' => 'nullable|string|max:100',
            'document_expiry_date' => 'nullable|date|after:today',
            'phone' => 'nullable|string|max:30',
            'passenger_type' => 'required|in:adult,child'
        ]);

        BookingPassenger::where('id', $id)->update([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'document_expiry_date' => $request->document_expiry_date,
            'phone' => $request->phone,
            'passenger_type' => $request->passenger_type
        ]);

        $passenger = BookingPassenger::find($id);

        return response()->json([
            'message' => 'Passenger updated successfully',
            'data' => $passenger
        ], 200);
    }

    public function destroy($id)
    {
        $passenger = BookingPassenger::whereHas('booking', function ($query) {
            $query->where('user_id', auth()->id());
        })->find($id);

        if (!$passenger) {
            return response()->json([
                'message' => 'Passenger not found'
            ], 404);
        }

        if ($passenger->booking->status != 'pending' ||
            $passenger->booking->expires_at <= now()) {

            return response()->json([
                'message' => 'Passenger cannot be deleted'
            ], 422);
        }

        BookingPassenger::where('id', $id)->delete();

        return response()->json([
            'message' => 'Passenger deleted successfully'
        ], 200);
    }
}
