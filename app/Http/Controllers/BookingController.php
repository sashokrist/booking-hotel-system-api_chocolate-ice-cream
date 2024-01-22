<?php

namespace App\Http\Controllers;

use App\Events\BookingCanceled;
use App\Events\BookingCreated;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Bookings",
 *     description="API Endpoints of Bookings"
 * )
 */
class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bookings",
     *      tags={"Bookings"},
     *      summary="List all bookings",
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Bookings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="item", type="string", example="Fetch all bokkings")
     *         )
     *     )
     *     )
     * )
     */
    public function index()
    {
        $bookings = Booking::all();

        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'No bookings found'], 200);
        }
        return response()->json($bookings);
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Create a new booking",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"room_id","customer_id","check_in_date","check_out_date","total_price"},
     *             @OA\Property(property="room_id", type="integer", format="int64", example=1),
     *             @OA\Property(property="customer_id", type="integer", format="int64", example=1),
     *             @OA\Property(property="check_in_date", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="check_out_date", type="string", format="date", example="2024-01-05"),
     *             @OA\Property(property="total_price", type="number", format="float", readOnly=true, example=100.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking successfully created",
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      )
     * )
     */
    public function store(StoreBookingRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $room = Room::where('id', $request->room_id)
                ->where('status', 'available')
                ->firstOrFail();

            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);
            $numberOfNights = $checkOutDate->diffInDays($checkInDate);
            $totalPrice = $numberOfNights * $room->price_per_night;

            // Create the booking
            $booking = Booking::create([
                'room_id' => $room->id,
                'customer_id' => $request->customer_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'total_price' => $totalPrice
            ]);

            $room->update(['status' => 'booked']);

            BookingCreated::dispatch($booking);

            return response()->json($booking, 201);
        });
    }

    /**
     * @OA\Delete(
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Cancel a booking",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking canceled and room status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Room is not currently booked"
     *     )
     * )
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $room = Room::find($booking->room_id);
        if (!$room) {
            return response()->json(['error' => 'Room associated with this booking not found'], 404);
        }

        if ($room->status !== 'booked') {
            return response()->json(['error' => 'Room is not currently booked'], 400);
        }

        $room->update(['status' => 'available']);

        $booking->delete();

        BookingCanceled::dispatch($booking);

        return response()->json(['message' => 'Booking canceled and room status updated successfully'], 200);
    }
}
