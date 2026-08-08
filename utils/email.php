<?php
function sendVerificationCode($userEmail, $code) {
    $subject = "Verify Your Email - StayVora";
    $message = "Your email verification code is: " . $code . "\n\n";
    $message .= "Enter this code on the verification page to activate your account.\n";
    $message .= "This code expires in 10 minutes.\n\n";
    $message .= "If you did not request this, you can safely ignore this email.\n\n";
    $message .= "Thank you for choosing StayVora!";

    $headers = "From: noreply@stayvora.com\r\n";
    $headers .= "Reply-To: support@stayvora.com\r\n";

    $logEntry = date('Y-m-d H:i:s') . " - TO: $userEmail - SUBJECT: $subject - MESSAGE: " . str_replace("\n", " ", $message) . PHP_EOL;
    $logFile = __DIR__ . '/../logs/emails.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        @mail($userEmail, $subject, $message, $headers);
    }
}

function sendBookingConfirmation($userEmail, $bookingDetails, $hotelName) {
    $subject = "Booking Confirmation - " . $hotelName;
    $message = "Your booking at " . $hotelName . " has been confirmed.\n\n";
    $message .= "Booking Details:\n";
    $message .= "Check-in: " . $bookingDetails['check_in'] . "\n";
    $message .= "Check-out: " . $bookingDetails['check_out'] . "\n";
    $message .= "Guests: " . $bookingDetails['guests'] . "\n";
    $message .= "Total Price: $" . number_format($bookingDetails['total_price'], 2) . "\n";
    $message .= "Status: " . $bookingDetails['status'] . "\n\n";
    $message .= "Thank you for choosing StayVora!";

    $headers = "From: noreply@stayvora.com\r\n";
    $headers .= "Reply-To: support@stayvora.com\r\n";

    $logEntry = date('Y-m-d H:i:s') . " - TO: $userEmail - SUBJECT: $subject - MESSAGE: " . str_replace("\n", " ", $message) . PHP_EOL;
    $logFile = __DIR__ . '/../logs/emails.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        @mail($userEmail, $subject, $message, $headers);
    }
}

function sendBookingStatusUpdate($userEmail, $bookingDetails, $status) {
    $subject = "Booking Status Update - StayVora";
    $message = "Your booking status has been updated.\n\n";
    $message .= "Booking ID: " . $bookingDetails['id'] . "\n";
    $message .= "New Status: " . $status . "\n";
    $message .= "Check-in: " . $bookingDetails['check_in'] . "\n";
    $message .= "Check-out: " . $bookingDetails['check_out'] . "\n";
    $message .= "Total Price: $" . number_format($bookingDetails['total_price'], 2) . "\n\n";
    $message .= "Thank you for choosing StayVora!";

    $headers = "From: noreply@stayvora.com\r\n";
    $headers .= "Reply-To: support@stayvora.com\r\n";

    $logEntry = date('Y-m-d H:i:s') . " - TO: $userEmail - SUBJECT: $subject - MESSAGE: " . str_replace("\n", " ", $message) . PHP_EOL;
    $logFile = __DIR__ . '/../logs/emails.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        @mail($userEmail, $subject, $message, $headers);
    }
}
