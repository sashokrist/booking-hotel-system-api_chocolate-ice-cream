<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Mail\NewBookingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewBookingNotification
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
    public function handle(BookingCreated $event)
    {
        $booking = $event->booking;

        $recipient = 'staff@hotels.com';

        Mail::to($recipient)->send(new NewBookingNotification($booking));
    }
}
