<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Booking Notification</title>
</head>
<body>
<h1>New Booking Alert</h1>
<p>A new booking has been made!</p>
<p><strong>Booking Details:</strong></p>
<ul>
    <li>Customer Name: {{ $booking->customer->name }}</li>
    <li>Room Number: {{ $booking->room->number }}</li>
    <li>Check-in Date: {{ $booking->check_in_date }}</li>
    <li>Check-out Date: {{ $booking->check_out_date }}</li>
</ul>
</body>
</html>
