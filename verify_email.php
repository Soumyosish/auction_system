<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';

$error = '';
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Find user by verification token
    $stmt = $conn->prepare("SELECT user_id, is_verified FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['is_verified']) {
            $success = "Your email is already verified. You can <a href='login.php' class='text-blue-600 hover:text-blue-800'>login here</a>.";
        } else {
            // Mark user as verified
            $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE user_id = ?");
            $update->bind_param("i", $user['user_id']);
            if ($update->execute()) {
                $success = "Your email has been verified successfully! You can <a href='login.php' class='text-blue-600 hover:text-blue-800'>login here</a>.";
            } else {
                $error = "Failed to verify email. Please try again later.";
            }
        }
    } else {
        $error = "Invalid or expired verification link.";
    }
} else {
    $error = "No verification token provided.";
}

include 'php/header.php';
?>
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 mt-8">
    <h1 class="text-2xl font-bold text-center mb-6">Email Verification</h1>
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
</div>
<?php include 'php/footer.php'; ?>
