<?php
session_start();
// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Include database connection
require_once "../includes/db.php";

// Handle product addition (insert into auctions table)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $title = $_POST['name'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $start_price = $_POST['price'];
    $duration = (int)$_POST['duration'];
    $status = 'upcoming';
    $end_date = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

    $stmt = $db->prepare("INSERT INTO auctions (title, description, image_url, start_price, end_date, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $description, $image_url, $start_price, $end_date, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: products.php");
    exit();
}

// Handle product deletion (delete from auctions table)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $stmt = $db->prepare("DELETE FROM auctions WHERE auction_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
    header("Location: products.php");
    exit();
}

// Fetch all products from the auctions table
// Update the query to sort products by auction_id in ascending order
// Get the filter parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Modify the products query based on filter
$sql = "SELECT auction_id, title, description, image_url, starting_price, end_date, status FROM auctions";
if ($filter !== 'all') {
    $sql .= " WHERE status = ?";
}
$sql .= " ORDER BY auction_id ASC";

if ($filter !== 'all') {
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $db->query($sql);
}

// Get total auctions count (all auctions regardless of status)
$total_auctions = $db->query("SELECT COUNT(*) as total FROM auctions")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Dashboard</title>
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
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
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
                        <a href="products.php" class="menu-item active flex items-center gap-3 px-6 py-4 rounded-xl text-white">
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
                        <a href="users.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-white">
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
                <div class="mx-8 py-5 flex justify-between items-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-transparent bg-clip-text">
                        Manage Products
                    </h1>
                    <div class="flex items-center gap-6">
                        <span class="text-lg font-semibold text-indigo-600">Total Auctions: <?php echo $total_auctions; ?></span>
                        <button id="add-product-btn" class="btn-gradient text-white px-6 py-2.5 rounded-lg font-medium" 
                                onclick="document.getElementById('product-modal').classList.remove('hidden')">
                            <i class="fas fa-plus mr-2"></i>Add Product
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Filter Options -->
            <div class="mx-8 mt-6">
                <div class="flex gap-4 mb-6">
                    <a href="?filter=all" 
                       class="px-6 py-2 rounded-lg font-medium transition-all <?php echo $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">
                        All Products
                    </a>
                    <a href="?filter=active" 
                       class="px-6 py-2 rounded-lg font-medium transition-all <?php echo $filter === 'active' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">
                        Active
                    </a>
                    <a href="?filter=ended" 
                       class="px-6 py-2 rounded-lg font-medium transition-all <?php echo $filter === 'ended' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">
                        Ended
                    </a>
                </div>
            </div>

            <!-- Products Table -->
            <div class="p-8">
                <div class="table-container p-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Product ID</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Image</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Start Price</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">End Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($products->num_rows > 0): ?>
                                <?php while ($product = $products->fetch_assoc()): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($product['auction_id']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($product['title']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($product['description']); ?></td>
                                        <td class="px-4 py-3">
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                                 alt="Product Image" 
                                                 class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                        </td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($product['starting_price']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($product['end_date']); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                <?php echo $product['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'; ?>">
                                                <?php echo htmlspecialchars($product['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="product_id" value="<?php echo $product['auction_id']; ?>">
                                                <button type="submit" name="delete_product" 
                                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all">
                                                    <i class="fas fa-trash-alt mr-2"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div id="product-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-1/2 max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Add New Product</h2>
                <button class="text-gray-400 hover:text-gray-600 text-2xl" 
                        onclick="document.getElementById('product-modal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="add_product" value="1">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Product Title</label>
                    <input type="text" id="name" name="name" required class="border border-gray-300 rounded px-4 py-2 w-full">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" required class="border border-gray-300 rounded px-4 py-2 w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Starting Price</label>
                    <input type="number" id="price" name="price" step="0.01" required class="border border-gray-300 rounded px-4 py-2 w-full">
                </div>
                <div class="mb-4">
                    <label for="duration" class="block text-gray-700">Auction Duration</label>
                    <select id="duration" name="duration" required class="border border-gray-300 rounded px-4 py-2 w-full">
                        <option value="1">1 Day</option>
                        <option value="3">3 Days</option>
                        <option value="5">5 Days</option>
                        <option value="7">7 Days</option>
                        <option value="10">10 Days</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image_url" class="block text-gray-700">Image URL</label>
                    <input type="url" id="image_url" name="image_url" required class="border border-gray-300 rounded px-4 py-2 w-full">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
