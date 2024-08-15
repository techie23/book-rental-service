<!DOCTYPE html>
<html>
<head>
    <title>Overdue Rental Notification</title>
</head>
<body>
    <h1>Overdue Rental Notice</h1>
    <p>Dear {{ $userName }},</p>
    <p>The book "{{ $bookTitle }}" you rented was due on {{ $dueDate }} and is now overdue. Please return it as soon as possible.</p>
    <p>Thank you!</p>
</body>
</html>
