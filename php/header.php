<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auction_functions.php';
update_auction_status(); // Update auction status on each page load
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Auction System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .countdown {
            font-weight: bold;
            color: #ff4500;
        }
        .auction-card {
            transition: transform 0.3s ease;
        }
        .auction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo -->
            <a href="index.php" class="text-2xl font-bold flex items-center">
                <i class="fas fa-gavel mr-2"></i>
                <span>BidPulse</span>
            </a>
            <!-- Centered Navigation -->
            <nav class="hidden md:flex flex-1 justify-center space-x-6 items-center">
                <a href="index.php" class="hover:text-blue-200 transition">Home</a>
                <a href="browse.php" class="hover:text-blue-200 transition">Browse Auctions</a>
                <?php if(is_logged_in()): ?>
                    <a href="create_auction.php" class="hover:text-blue-200 transition">Create Auction</a>
                <?php endif; ?>
                <a href="about.php" class="hover:text-blue-200 transition">About Us</a>
            </nav>
            <!-- Hamburger Button & Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Hamburger Button -->
                <button id="menu-toggle" class="md:hidden text-2xl focus:outline-none" aria-label="Open Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Right Side (About, Login/Register, User Dropdown) -->
                <nav class="hidden md:flex items-center space-x-4">
                    
                    <?php if(is_logged_in()): ?>
                        <div class="relative group">
                            <button class="flex items-center hover:text-blue-200 transition">
                                <span class="mr-1"><?php echo $_SESSION['username']; ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 top-full w-48 bg-white text-gray-800 shadow-lg rounded-md overflow-hidden z-10 hidden group-hover:block">
                                <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> My Profile
                                </a>
                                <a href="my_auctions.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-list mr-2"></i> My Auctions
                                </a>
                                <a href="my_bids.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-gavel mr-2"></i> My Bids
                                </a>
                                <?php if(is_admin()): ?>
                                <a href="admin_dashboard.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Admin Panel
                                </a>
                                <?php endif; ?>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="hover:text-blue-200 transition">Login</a>
                        <a href="register.php" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-blue-50 transition">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-blue-700 text-white px-4 pb-4">
            <nav class="flex flex-col space-y-2 pt-2 items-center">
                <a href="index.php" class="block py-2 hover:text-blue-200 transition">Home</a>
                <a href="browse.php" class="block py-2 hover:text-blue-200 transition">Browse Auctions</a>
                <?php if(is_logged_in()): ?>
                    <a href="create_auction.php" class="block py-2 hover:text-blue-200 transition">Create Auction</a>
                <?php endif; ?>
                <a href="about.php" class="block py-2 hover:text-blue-200 transition">About Us</a>
                <?php if(is_logged_in()): ?>
                    <a href="my_bids.php" class="block py-2 hover:text-blue-200 transition">My Bids</a>
                    <a href="profile.php" class="block py-2 hover:text-blue-200 transition">My Profile</a>
                    <a href="my_auctions.php" class="block py-2 hover:text-blue-200 transition">My Auctions</a>
                    <?php if(is_admin()): ?>
                    <a href="admin_dashboard.php" class="block py-2 hover:text-blue-200 transition">Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" class="block py-2 hover:text-blue-200 transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="block py-2 hover:text-blue-200 transition">Login</a>
                    <a href="register.php" class="block py-2 hover:text-blue-200 transition">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    <!-- Main Content Container -->
    <main class="container mx-auto px-4 py-6 flex-grow">