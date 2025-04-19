<?php
session_start();
// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Include database connection
require_once "../includes/db.php";

// Initialize error message
$error_message = "";

// Handle new auction creation
// Fetch auctions with highest bid (current bid) dynamically
// At the top of the file, after error_message initialization
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'end_date';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Modify the SQL query to include dynamic sorting
// Update the SQL query to show only auctions that have bids
$sql = "SELECT 
    a.auction_id, 
    a.title, 
    a.image_url, 
    a.starting_price, 
    a.current_price,
    a.end_date, 
    a.status,
    COALESCE(MAX(b.bid_amount), a.starting_price) AS current_bid
FROM auctions a
INNER JOIN bids b ON a.auction_id = b.auction_id
WHERE a.status IN ('ended', 'active')
GROUP BY a.auction_id, a.title, a.image_url, a.starting_price, a.current_price, a.end_date, a.status
ORDER BY 
    CASE 
        WHEN a.status = 'ended' THEN 0 
        ELSE 1 
    END,
    a.current_price ASC";

// Update the form processing section
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'], $_POST['start_price'], $_POST['end_date'], $_POST['image_url'])) {
    $title = $_POST['product'];
    $starting_price = floatval($_POST['start_price']);
    $end_date = $_POST['end_date'];
    $image_url = $_POST['image_url'];
    $status = $_POST['status'];
    $seller_id = $_SESSION['user_id']; // Assuming you have the seller_id in session
    $category_id = 1; // Set a default category or add a category selector to your form
    $current_price = $starting_price; // Initially set current_price same as starting_price

    // Validate inputs
    if (empty($title) || empty($starting_price) || empty($end_date) || empty($image_url)) {
        $error_message = "All fields are required to create an auction.";
    } else {
        // Insert the new auction into the database
        $stmt = $db->prepare("INSERT INTO auctions (title, description, image_url, seller_id, category_id, starting_price, current_price, end_date, status) VALUES (?, '', ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiddss", $title, $image_url, $seller_id, $category_id, $starting_price, $current_price, $end_date, $status);
        $stmt->execute();
        $stmt->close();

        // Redirect to avoid form resubmission
        header("Location: auctions.php");
        exit();
    }
}

$result = $db->query($sql);
$auctions = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Auctions - Admin Dashboard</title>
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
                        <a href="products.php" class="menu-item flex items-center gap-3 px-6 py-4 rounded-xl text-white">
                            <i class="fas fa-box text-lg"></i>
                            <span class="font-medium">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="auctions.php" class="menu-item active flex items-center gap-3 px-6 py-4 rounded-xl text-white">
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
                <div class="mx-8 py-5">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-transparent bg-clip-text">
                        Manage Auctions
                    </h1>
                </div>
            </nav>

            <!-- Error Message -->
            <?php if (!empty($error_message)): ?>
                <div class="mx-8 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <p class="font-medium"><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>

            <!-- Auctions Table -->
            <div class="p-8">
                <div class="table-container p-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Auction ID</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Product</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Image</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Start Price</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Current Bid</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">End Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($auctions)): ?>
                                <?php foreach ($auctions as $auction): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($auction['auction_id']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($auction['title']); ?></td>
                                        <td class="px-4 py-3">
                                            <img src="<?php echo htmlspecialchars($auction['image_url']); ?>" 
                                                 alt="Product Image" 
                                                 class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                        </td>
                                        <td class="px-4 py-3">Rs. <?php echo number_format($auction['starting_price'], 2); ?></td>
                                        <td class="px-4 py-3">Rs. <?php echo number_format($auction['current_bid'] ?? $auction['current_price'], 2); ?></td>
                                        <td class="px-4 py-3"><?php echo date('M d, Y H:i', strtotime($auction['end_date'])); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                <?php echo $auction['status'] === 'active' ? 'bg-green-100 text-green-700' : 
                                                    ($auction['status'] === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'); ?>">
                                                <?php echo htmlspecialchars($auction['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">No auctions found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Create Auction Modal -->
    <div id="create-auction-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-1/2 max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Create New Auction</h2>
                <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="product" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Price</label>
                    <input type="number" name="start_price" step="0.01" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="datetime-local" name="end_date" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="url" name="image_url" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="active">Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="ended">Ended</option>
                    </select>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="toggleModal()" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="btn-gradient text-white px-6 py-2.5 rounded-lg">Create Auction</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>