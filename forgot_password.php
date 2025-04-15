
<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';
require_once 'email.php'; // Include the email functionality

$error = '';
$message = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email address from the form
    $email = sanitize_input($_POST['email']);

    // Check if the email exists in the database
    if (email_exists($email)) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32));

        // Save the token in the database with an expiration time
        if (save_password_reset_token($email, $token)) {
            // Prepare the password reset email
            $subject = "Password Reset Request";
            $reset_link = "http://localhost/auction_system/reset_password.php?token=$token";
            $message = "
                <html>
                <head>
                    <title>Password Reset Request</title>
                </head>
                <body>
                    <h1>Password Reset Request</h1>
                    <p>We received a request to reset your password. Click the link below to reset your password:</p>
                    <a href='$reset_link' style='display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>Reset Password</a>
                    <p>If you did not request this, please ignore this email.</p>
                </body>
                </html>
            ";

            // Send the email
            if (send_email($email, $subject, $message)) {
                $message = "A password reset link has been sent to your email address.";
            } else {
                $error = "Failed to send the password reset email. Please try again.";
            }
        } else {
            $error = "Failed to generate a password reset token. Please try again.";
        }
    } else {
        $error = "No account found with that email address.";
    }
}

include 'php/header.php';
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 mt-8">
    <h1 class="text-2xl font-bold text-center mb-6">Forgot Password</h1>

    <?php if (!empty($message)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $message; ?></span>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <form action="forgot_password.php" method="POST" id="forgotPasswordForm">
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Send Reset Link</button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">Remembered your password? <a href="login.php" class="text-blue-600 hover:text-blue-800">Login</a></p>
    </div>
</div>

<?php include 'php/footer.php'; ?>