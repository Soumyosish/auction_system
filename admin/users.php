<?php
session_start();
// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Include database connection
require_once "../includes/db.php";

// Fetch all users from the database
$users = $db->query("SELECT user_id, username, email FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .dashboard-gradient { 
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        .page-background {
            background: linear-gradient(to bottom right, #eef2ff 0%, #f5f3ff 50%, #ede9fe 100%);
        }
        .sidebar-gradient {
            background: linear-gradient(150deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        .menu-item {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }
        .menu-item:hover {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.2) 100%);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen page-background">
        <!-- Sidebar -->
        <aside class="w-72 sidebar-gradient text-white">
            <div class="p-6 dashboard-gradient">
                <h2 class="text-2xl font-bold text-center flex items-center justify-center gap-3">
                    <i class="fas fa-gavel text-3xl"></i>
                    <span class="tracking-wider">AUCTION HUB</span>
                </h2>
            </div>
            <nav class="p-6">
                <ul class="space-y-4">
                    <li>
                        <a href="dashboard.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-white">
                            <i class="fas fa-chart-line text-lg"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="products.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-white">
                            <i class="fas fa-box text-lg"></i>
                            <span class="font-medium">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="auctions.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-white">
                            <i class="fas fa-hammer text-lg"></i>
                            <span class="font-medium">Auctions</span>
                        </a>
                    </li>
                    <li>
                        <a href="bids.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-white">
                            <i class="fas fa-hand-holding-usd text-lg"></i>
                            <span class="font-medium">Bids</span>
                        </a>
                    </li>
                    <li>
                        <a href="users.php" class="menu-item active flex items-center gap-3 px-6 py-4 rounded-xl text-white">
                            <i class="fas fa-users text-lg"></i>
                            <span class="font-medium">Users</span>
                        </a>
                    </li>
                    <li class="mt-8">
                        <a href="logout.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                            <span class="font-medium">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden">
            <!-- Top Navigation -->
            <nav class="bg-white/80 backdrop-blur-md border-b border-white/20">
                <div class="mx-8 py-5">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-transparent bg-clip-text">
                        Manage Users
                    </h1>
                </div>
            </nav>

            <!-- Users Table -->
            <div class="p-8">
                <div class="table-container p-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">User ID</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Username</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($users->num_rows > 0): ?>
                                <?php while ($user = $users->fetch_assoc()): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($user['user_id']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($user['email']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>