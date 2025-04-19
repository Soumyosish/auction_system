<?php
require_once "../includes/config.php";
require_once "../includes/functions.php";
require_once "../includes/db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check admin credentials
    $query = "SELECT * FROM admins WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        // Set session variables for admin
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['user_type'] = 'admin'; // Set user type as admin
        header("Location: dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Auction System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">🏆 Auction System</h1>
            <p class="text-white/80">Admin Dashboard Access</p>
        </div>

        <!-- Login Form -->
        <form method="POST" class="bg-white rounded-2xl shadow-2xl p-8 space-y-6 transform transition-all hover:scale-[1.01]">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Welcome Back</h2>
            
            <?php if (isset($error_message)): ?>
                <div class="bg-red-50 text-red-500 p-4 rounded-lg text-sm mb-6">
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo $error_message; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <input type="text" 
                               name="username" 
                               id="username" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all outline-none" 
                               required>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all outline-none" 
                               required>
                    </div>
                </div>
            </div>

            <button type="submit" 
                    name="login" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Sign In
            </button>

            <div class="text-center mt-6">
                <a href="../index.php" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors">
                    ← Return to Main Site
                </a>
            </div>
        </form>
    </div>
</body>
</html>