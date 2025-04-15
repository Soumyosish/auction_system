<?php
function send_email($to, $subject, $message) {
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: shreyatpath2005@gmail.com" . "\r\n";

    // Debugging: Log email details
    error_log("Attempting to send email to: $to");
    error_log("Subject: $subject");
    error_log("Headers: $headers");

    // Use mail() function to send the email
    if (mail($to, $subject, $message, $headers)) {
        error_log("Email sent successfully to: $to");
        return true; // Email sent successfully
    } else {
        error_log("Failed to send email to: $to");
        return false; // Failed to send email
    }
}
?>