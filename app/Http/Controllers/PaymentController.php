<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Payments",
 *     description="API Endpoints of Payments"
 * )
 */
class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/payments",
     *     tags={"Payments"},
     *     summary="Create a new payment",
     *     description="Stores a new payment record",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"booking_id", "amount", "payment_date", "status"},
     *             @OA\Property(property="booking_id", type="integer", example=1),
     *             @OA\Property(property="amount", type="number", format="float", example=100.50),
     *             @OA\Property(property="payment_date", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="status", type="string", example="processed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="item", type="string", example="Store new payment")
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $booking = Booking::find($request->booking_id);
        if (!$booking) {
            return response()->json(['error' => 'No booking found with this ID'], 404);
        }

        if ($booking->payments()->exists()) {
            return response()->json(['error' => 'Payment has already been made for this booking'], 400);
        }

        // Validate that the payment amount matches the booking's total price
        if ($request->amount != $booking->total_price) {
            return response()->json(['error' => 'Payment amount not match with the booking-total price'], 400);
        }

        $payment = new Payment;
        $payment->booking_id = $request->booking_id;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->status = $request->status;
        $payment->save();

        return response()->json($payment, 201);
    }
}