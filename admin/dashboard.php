<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php"); // Redirect to login page
    exit();
}

// Include database connection
require_once "../includes/db.php";

// Fetch dashboard stats
// Removed products table query since it doesn't exist
$total_products = $db->query("SELECT COUNT(*) AS count FROM auctions")->fetch_assoc()['count'] ?? 0;
$active_auctions = $db->query("SELECT COUNT(*) AS count FROM auctions WHERE status = 'active'")->fetch_assoc()['count'] ?? 0;
$total_bids = $db->query("SELECT COUNT(*) AS count FROM bids")->fetch_assoc()['count'] ?? 0;
$total_users = $db->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Auction System</title>
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
        .menu-item.active {
            background: linear-gradient(90deg, rgba(99,102,241,0.2) 0%, rgba(139,92,246,0.2) 100%);
            border-left: 4px solid #818cf8;
        }
        .menu-item:not(.active):hover::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #818cf8, #8b5cf6);
            animation: slideIn 0.3s ease forwards;
        }
        @keyframes slideIn {
            from { transform: scaleY(0); }
            to { transform: scaleY(1); }
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
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
                    <span class="tracking-wider">BIDPULSE</span>
                </h2>
            </div>
            <nav class="p-6">
                <ul class="space-y-4">
                    <li>
                        <a href="dashboard.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl bg-white/10 text-white">
                            <i class="fas fa-chart-line text-lg"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="products.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                            <i class="fas fa-box"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="auctions.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                            <i class="fas fa-hammer"></i>
                            Auctions
                        </a>
                    </li>
                    <li>
                        <a href="bids.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                            <i class="fas fa-hand-holding-usd"></i>
                            Bids
                        </a>
                    </li>
                    <li>
                        <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                            <i class="fas fa-users"></i>
                            Users
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-600 transition-all mt-8 text-red-400 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden">
            <!-- Top Navigation -->
            <nav class="bg-white/80 backdrop-blur-md border-b border-white/20">
                <div class="mx-8 py-5 flex justify-between items-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-transparent bg-clip-text">
                        Dashboard Overview
                    </h1>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-3 bg-white/80 px-4 py-2 rounded-lg shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Welcome back,</p>
                                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Stats Grid with updated styling -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Update each stat card with new styling -->
                    <div class="stat-card rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-indigo-600 bg-indigo-100 p-3 rounded-lg">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-green-600 bg-green-100 px-2.5 py-0.5 rounded-full">
                                +12% ↑
                            </span>
                        </div>
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Total Products</h3>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $total_products; ?></p>
                    </div>

                    <!-- Active Auctions -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-purple-600 bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-gavel text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-green-600 bg-green-100 px-2.5 py-0.5 rounded-full">
                                +5% ↑
                            </span>
                        </div>
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Active Auctions</h3>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $active_auctions; ?></p>
                    </div>

                    <!-- Total Bids -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-blue-600 bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-hand-holding-usd text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-green-600 bg-green-100 px-2.5 py-0.5 rounded-full">
                                +18% ↑
                            </span>
                        </div>
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Total Bids</h3>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $total_bids; ?></p>
                    </div>

                    <!-- Registered Users -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-green-600 bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-green-600 bg-green-100 px-2.5 py-0.5 rounded-full">
                                +8% ↑
                            </span>
                        </div>
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Registered Users</h3>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>