<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('{{ asset('assets/img/bg-img.jpeg') }}');
            background-size: cover;
            color: #333; /* Text color */
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        h3 {
            color: #b8860b; /* Gold color for heading */
        }

        p {
            margin-bottom: 10px;
        }

        /* Red color for important information */
        .important {
            color: #ff0000;
        }
    </style>
    <title>{{ $mailData['title'] }}</title>
</head>
<body>
    <h3>Hi,</h3>
    <div>
        <p>Your tickets for Spirit Mind Body Festival are here! 🎫</p>
        <p>Date: 2nd December, 2023</p>
        <p>Time: 8am</p>
        <p>Venue: Genesis Wellness Park, 2 Wobo Street off Amaechi Drive, GRA phase 3</p>
        <p class="important">Your Ticket Details:</p>
        <p>Ticket Type: {{ $mailData['Ticket_type'] }}</p>
        <p>Number of Tickets: {{ $mailData['Number_of_tickets'] }}</p>
        <p>Ticket ID: {{ $mailData['Ticket_id'] }}</p>
        <p>Questions? Contact us at +2349157611144.</p>
        <br>
        <p>Can't wait to see you there!</p>
    </div>
</body>
</html>
