<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();

        return response()->json([
            "message" => "Buses retrieved successfully",
            "data" => $buses
        ], 200);
    }

    public function show($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                "message" => "Bus not found"
            ], 404);
        }

        return response()->json([
            "message" => "Bus retrieved successfully",
            "data" => $bus
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_number' => 'required|string|unique:buses,bus_number',
            'plate_number' => 'required|string|unique:buses,plate_number',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'status' => 'sometimes|in:active,maintenance,inactive'
        ]);

        $bus = Bus::create([
            'bus_number' => $request->bus_number,
            'plate_number' => $request->plate_number,
            'manufacturer' => $request->manufacturer,
            'model' => $request->model,
            'status' => $request->status ?? 'active'
        ]);

        return response()->json([
            "message" => "Bus created successfully",
            "data" => $bus
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                "message" => "Bus not found"
            ], 404);
        }

        $request->validate([
            'bus_number' => 'required|string|unique:buses,bus_number,' . $id,
            'plate_number' => 'required|string|unique:buses,plate_number,' . $id,
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive'
        ]);

        Bus::where('id', $id)->update([
            'bus_number' => $request->bus_number,
            'plate_number' => $request->plate_number,
            'manufacturer' => $request->manufacturer,
            'model' => $request->model,
            'status' => $request->status
        ]);

        $bus = Bus::find($id);

        return response()->json([
            "message" => "Bus updated successfully",
            "data" => $bus
        ], 200);
    }

    public function destroy($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                "message" => "Bus not found"
            ], 404);
        }

        Bus::where('id', $id)->delete();

        return response()->json([
            "message" => "Bus deleted successfully"
        ], 200);
    }
}
