<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Trip;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::all();

        return response()->json([
            "message" => "Trips retrieved successfully",
            "data" => $trips
        ], 200);
    }

    public function show($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                "message" => "Trip not found"
            ], 404);
        }

        return response()->json([
            "message" => "Trip retrieved successfully",
            "data" => $trip
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bus_id' => 'required|integer|exists:buses,id',
            'driver_id' => 'nullable|integer|exists:drivers,id',
            'origin_id' => 'required|integer|exists:destinations,id|different:destination_id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'departure_at' => 'required|date',
            'arrival_at' => 'required|date|after:departure_at',
            'price' => 'required|numeric|min:0',
            'status' => 'sometimes|in:scheduled,departed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $trip = Trip::create([
            'bus_id' => $request->bus_id,
            'driver_id' => $request->driver_id,
            'origin_id' => $request->origin_id,
            'destination_id' => $request->destination_id,
            'departure_at' => $request->departure_at,
            'arrival_at' => $request->arrival_at,
            'price' => $request->price,
            'status' => $request->status ?? 'scheduled'
        ]);

        return response()->json([
            "message" => "Trip created successfully",
            "data" => $trip
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                "message" => "Trip not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'bus_id' => 'required|integer|exists:buses,id',
            'driver_id' => 'nullable|integer|exists:drivers,id',
            'origin_id' => 'required|integer|exists:destinations,id|different:destination_id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'departure_at' => 'required|date',
            'arrival_at' => 'required|date|after:departure_at',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,departed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $trip->update([
            'bus_id' => $request->bus_id,
            'driver_id' => $request->driver_id,
            'origin_id' => $request->origin_id,
            'destination_id' => $request->destination_id,
            'departure_at' => $request->departure_at,
            'arrival_at' => $request->arrival_at,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return response()->json([
            "message" => "Trip updated successfully",
            "data" => $trip
        ], 200);
    }

    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                "message" => "Trip not found"
            ], 404);
        }

        $trip->delete();

        return response()->json([
            "message" => "Trip deleted successfully"
        ], 200);
    }
}
