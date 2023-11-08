<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $mailData['title'] }}</title>
</head>
<body>
    <h3> Hi, </h3>
   <p>
        <p>Your tickets for Spirit, Body, and Mind Festival is here! 🎫</p>
        <p>Date: 2nd December, 2023</p>
        <p>Time: 8am</p>
        <p>Venue: Genesis Wellness Park, 2 Wobo Street off Amaechi Drive, GRA phase 3</p>
        <p>Your Ticket Details:</p>
        <p>Ticket Type: {{ $mailData['Ticket_type'] }}</p>
        <p>Number of Tickets: {{ $mailData['Number_of_tickets'] }}</p>
        <p>Ticket ID: {{ $mailData['Ticket_id'] }}</p>
        <p>Questions? Contact us at +2348064529494.</p>  <br>
        <p>Can't wait to see you there!</p>
   </p>
</body>
</html>
{{-- 'body' => "
            Hi,

            Your tickets for are here! 🎫\n

            Event: Spirit, Body, and Mind Festival\n
            Date: 2nd December, 2023\n
            Time: 8am\n
            Venue: Genesis Wellness Park, 2 Wobo Street off Amaechi Drive, GRA phase 3\n
            \n
            Your Ticket Details:\n
            Ticket Type: $plan\n
            Number of Tickets: $num\n
            Ticket ID: $ticket\n
            Questions? Contact us at +2348064529494.\n
            \n
            Can't wait to see you there!" --}}
