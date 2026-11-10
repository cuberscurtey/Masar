<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::all();

        return response()->json([
            "message" => "Destinations retrieved successfully",
            "data" => $destinations
        ], 200);
    }

    public function show($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json([
                "message" => "Destination not found"
            ], 404);
        }

        return response()->json([
            "message" => "Destination retrieved successfully",
            "data" => $destination
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'governorate' => 'required|string|max:150',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive'
        ]);

        $destination = Destination::create([
            'name' => $request->name,
            'governorate' => $request->governorate,
            'description' => $request->description,
            'status' => $request->status ?? 'active'
        ]);

        return response()->json([
            "message" => "Destination created successfully",
            "data" => $destination
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json([
                "message" => "Destination not found"
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'governorate' => 'required|string|max:150',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        Destination::where('id', $id)->update([
            'name' => $request->name,
            'governorate' => $request->governorate,
            'description' => $request->description,
            'status' => $request->status
        ]);

        $destination = Destination::find($id);

        return response()->json([
            "message" => "Destination updated successfully",
            "data" => $destination
        ], 200);
    }

    public function destroy($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json([
                "message" => "Destination not found"
            ], 404);
        }

        Destination::where('id', $id)->delete();

        return response()->json([
            "message" => "Destination deleted successfully"
        ], 200);
    }
}
