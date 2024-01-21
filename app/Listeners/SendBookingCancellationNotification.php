<?php

namespace App\Listeners;

use App\Events\BookingCanceled;
use App\Mail\BookingCancellationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingCancellationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCanceled $event)
    {
        $booking = $event->booking;

        $recipient = 'staff@hotels.com';

        Mail::to($recipient)->send(new BookingCancellationNotification($booking));
    }
}
