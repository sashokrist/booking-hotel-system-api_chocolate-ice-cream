<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Rooms",
 *     description="API Endpoints of Rooms"
 * )
 */
class RoomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="List all rooms",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *            @OA\Property(property="item", type="string", example="Fetch all rooms")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $rooms = Room::all();

        if ($rooms->isEmpty()) {
            return response()->json(['message' => 'No rooms found'], 200);
        }
        return response()->json($rooms);
    }

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Create a new room",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"number", "type", "price_per_night", "status"},
     *             @OA\Property(property="number", type="string", example="101"),
     *             @OA\Property(property="type", type="string", example="suite"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     *             @OA\Property(property="status", type="string", example="available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Room created successfully",
     *        @OA\JsonContent(
     *             @OA\Property(property="item", type="string", example="Add new room")
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $messages = [
            'number.unique' => 'The room number is already in use.',
        ];
        $validator = Validator::make($request->all(), [
            'number' => 'required|string|unique:rooms',
            'type' => 'required|string',
            'price_per_night' => 'required|numeric',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $room = Room::create($request->all());
        return response()->json($room, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Show a specific room",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Room ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *        @OA\JsonContent(
     *             @OA\Property(property="item", type="string", example="Room details")
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found"
     *     )
     * )
     */
    public function show($id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return response()->json($room);
    }

    /**
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Delete a room",
     *     description="Deletes a room if no bookings are associated with it",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Room ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Room cannot be deleted, it has bookings"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        if ($room->bookings()->exists()) {
            return response()->json(['error' => 'Room cannot be deleted, it has bookings'], 400);
        }

        $room->delete();

        return response()->json(['message' => 'Room deleted successfully'], 200);
    }
}
