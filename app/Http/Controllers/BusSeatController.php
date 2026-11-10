<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\BusSeat;

class BusSeatController extends Controller
{
    public function index($bus_id)
    {
        $bus = Bus::find($bus_id);

        if (!$bus) {
            return response()->json([
                "message" => "Bus not found"
            ], 404);
        }

        $busSeats = BusSeat::where('bus_id', $bus_id)->get();

        return response()->json([
            "message" => "Bus seats retrieved successfully",
            "data" => $busSeats
        ], 200);
    }

    public function show($id)
    {
        $busSeat = BusSeat::find($id);

        if (!$busSeat) {
            return response()->json([
                "message" => "Bus seat not found"
            ], 404);
        }

        return response()->json([
            "message" => "Bus seat retrieved successfully",
            "data" => $busSeat
        ], 200);
    }

    public function store(Request $request, $bus_id)
    {
        $bus = Bus::find($bus_id);

        if (!$bus) {
            return response()->json([
                "message" => "Bus not found"
            ], 404);
        }

        $request->validate([
            'seat_number' => 'required|string|max:255|unique:bus_seats,seat_number,NULL,id,bus_id,' . $bus_id,
            'row_number' => 'required|integer|min:1',
            'column_number' => 'required|integer|min:1',
            'seat_type' => 'sometimes|in:regular,premium',
            'is_active' => 'sometimes|boolean'
        ]);

        $busSeat = BusSeat::create([
            'bus_id' => $bus_id,
            'seat_number' => $request->seat_number,
            'row_number' => $request->row_number,
            'column_number' => $request->column_number,
            'seat_type' => $request->seat_type ?? 'regular',
            'is_active' => $request->is_active ?? true
        ]);

        return response()->json([
            "message" => "Bus seat created successfully",
            "data" => $busSeat
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $busSeat = BusSeat::find($id);

        if (!$busSeat) {
            return response()->json([
                "message" => "Bus seat not found"
            ], 404);
        }

        $request->validate([
            'seat_number' => 'required|string|max:255|unique:bus_seats,seat_number,' . $id . ',id,bus_id,' . $busSeat->bus_id,
            'row_number' => 'required|integer|min:1',
            'column_number' => 'required|integer|min:1',
            'seat_type' => 'required|in:regular,premium',
            'is_active' => 'required|boolean'
        ]);

        BusSeat::where('id', $id)->update([
            'seat_number' => $request->seat_number,
            'row_number' => $request->row_number,
            'column_number' => $request->column_number,
            'seat_type' => $request->seat_type,
            'is_active' => $request->is_active
        ]);

        $busSeat = BusSeat::find($id);

        return response()->json([
            "message" => "Bus seat updated successfully",
            "data" => $busSeat
        ], 200);
    }

    public function destroy($id)
    {
        $busSeat = BusSeat::find($id);

        if (!$busSeat) {
            return response()->json([
                "message" => "Bus seat not found"
            ], 404);
        }

        BusSeat::where('id', $id)->delete();

        return response()->json([
            "message" => "Bus seat deleted successfully"
        ], 200);
    }
}
