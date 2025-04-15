<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';

$error = '';
$success = '';
$show_form = true;

// Check if token is present
if (!isset($_GET['token']) && !isset($_POST['token'])) {
    $error = "Invalid or missing password reset token.";
    $show_form = false;
} else {
    $token = isset($_GET['token']) ? $_GET['token'] : $_POST['token'];

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate passwords
        if (empty($new_password) || strlen($new_password) < 6) {
            $error = "Password must be at least 6 characters.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Find user by token and check expiration
            $stmt = $conn->prepare("SELECT user_id, reset_token_expires FROM users WHERE reset_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (strtotime($user['reset_token_expires']) < time()) {
                    $error = "This password reset link has expired.";
                    $show_form = false;
                } else {
                    // Update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE user_id = ?");
                    $update->bind_param("si", $hashed_password, $user['user_id']);
                    if ($update->execute()) {
                        $success = "Your password has been reset successfully. <a href='login.php' class='text-blue-600 hover:text-blue-800'>Login here</a>.";
                        $show_form = false;
                    } else {
                        $error = "Failed to reset password. Please try again.";
                    }
                }
            } else {
                $error = "Invalid or expired password reset token.";
                $show_form = false;
            }
        }
    }
}

include 'php/header.php';
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 mt-8">
    <h1 class="text-2xl font-bold text-center mb-6">Reset Password</h1>

    <?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $success; ?></span>
    </div>
    <?php endif; ?>

    <?php if ($show_form): ?>
    <form action="reset_password.php" method="POST" id="resetPasswordForm">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <div class="mb-4">
            <label for="new_password" class="block text-gray-700 font-medium mb-2">New Password</label>
            <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-6">
            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Reset Password</button>
    </form>
    <?php endif; ?>
</div>

<?php include 'php/footer.php'; ?>