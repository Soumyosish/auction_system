<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Check if user is logged in and is admin
if(!is_logged_in() || !is_admin()) {
    redirect('index.php');
    exit;
}

// Get auctions with status filter
$status = isset($_GET['status']) ? sanitize_input($_GET['status']) : '';
$query = "SELECT a.*, c.name as category_name, u.username as seller_username
          FROM auctions a
          INNER JOIN categories c ON a.category_id = c.category_id
          INNER JOIN users u ON a.seller_id = u.user_id";

if($status == 'active') {
    $query .= " WHERE a.status = 'active' AND a.end_date > NOW()";
} else if($status == 'ended') {
    $query .= " WHERE a.status = 'ended' OR (a.status = 'active' AND a.end_date <= NOW())";
} else if($status == 'cancelled') {
    $query .= " WHERE a.status = 'cancelled'";
}

$query .= " ORDER BY a.created_at DESC";

global $conn;
$result = mysqli_query($conn, $query);
$auctions = [];
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $auctions[] = $row;
    }
}

// Get users
$users_query = "SELECT * FROM users ORDER BY created_at DESC";
$users_result = mysqli_query($conn, $users_query);
$users = [];
if($users_result) {
    while($row = mysqli_fetch_assoc($users_result)) {
        $users[] = $row;
    }
}

// Calculate statistics
$total_auctions = count($auctions);
$active_auctions = 0;
$ended_auctions = 0;
$total_users = count($users);
$total_bids = 0;

foreach($auctions as $auction) {
    if($auction['status'] == 'active' && strtotime($auction['end_date']) > time()) {
        $active_auctions++;
    } else if($auction['status'] == 'ended' || (strtotime($auction['end_date']) <= time())) {
        $ended_auctions++;
    }

    // Get bids for this auction
    $bid_query = "SELECT COUNT(*) as bid_count FROM bids WHERE auction_id = " . $auction['auction_id'];
    $bid_result = mysqli_query($conn, $bid_query);
    if($bid_result) {
        $bid_row = mysqli_fetch_assoc($bid_result);
        $total_bids += $bid_row['bid_count'];
    }
}

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-blue-600 text-white py-6 rounded-lg mb-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <p class="text-lg mt-2">Manage your auction platform</p>
    </div>
</div>

<!-- Dashboard Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-800 mr-4">
                <i class="fas fa-gavel text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Auctions</p>
                <p class="text-xl font-bold"><?php echo $total_auctions; ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-800 mr-4">
                <i class="fas fa-dollar-sign text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Auctions</p>
                <p class="text-xl font-bold"><?php echo $active_auctions; ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-800 mr-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Users</p>
                <p class="text-xl font-bold"><?php echo $total_users; ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-800 mr-4">
                <i class="fas fa-hand-paper text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Bids</p>
                <p class="text-xl font-bold"><?php echo $total_bids; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-8">
    <ul class="flex border-b border-gray-200">
        <li class="mr-1">
            <a href="#auctions" class="inline-block py-2 px-4 bg-white rounded-t-lg border-l border-t border-r border-gray-200 text-blue-600 font-medium active-tab">
                Auctions
            </a>
        </li>
        <li class="mr-1">
            <a href="#users" class="inline-block py-2 px-4 bg-gray-100 rounded-t-lg border-l border-t border-r border-gray-200 text-gray-600 hover:text-blue-600 hover:bg-white">
                Users
            </a>
        </li>
    </ul>
</div>

<!-- Auctions Section -->
<div id="auctions-content" class="tab-content">
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold">Manage Auctions</h2>
        </div>
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-wrap items-center">
                <a href="admin_dashboard.php" class="mr-4 mb-2 px-4 py-2 rounded-md <?php echo empty($status) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'; ?>">All</a>
                <a href="admin_dashboard.php?status=active" class="mr-4 mb-2 px-4 py-2 rounded-md <?php echo $status == 'active' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'; ?>">Active</a>
                <a href="admin_dashboard.php?status=ended" class="mr-4 mb-2 px-4 py-2 rounded-md <?php echo $status == 'ended' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'; ?>">Ended</a>
                <a href="admin_dashboard.php?status=cancelled" class="mb-2 px-4 py-2 rounded-md <?php echo $status == 'cancelled' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'; ?>">Cancelled</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if(empty($auctions)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No auctions found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($auctions as $auction): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo $auction['auction_id']; ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="<?php echo $auction['image_url']; ?>" alt="<?php echo $auction['title']; ?>">
                                </div>
                                <div>
                                    <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium"><?php echo $auction['title']; ?></a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $auction['seller_username']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $auction['category_name']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">$<?php echo number_format($auction['current_price'], 2); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($auction['status'] == 'active'): ?>
                                <?php if(strtotime($auction['end_date']) > time()): ?>
                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Active</span>
                                <?php else: ?>
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Ended</span>
                                <?php endif; ?>
                            <?php elseif($auction['status'] == 'ended'): ?>
                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Ended</span>
                            <?php else: ?>
                            <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo date('M d, Y H:i', strtotime($auction['end_date'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                            <!-- <a href="#" class="text-red-600 hover:text-red-800">Cancel</a> -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Users Section -->
<div id="users-content" class="tab-content hidden">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold">Manage Users</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($users as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo $user['user_id']; ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 mr-3">
                                    <img class="h-10 w-10 rounded-full object-cover" src="<?php echo $user['profile_image'] ? $user['profile_image'] : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?d=mp&s=200'; ?>" alt="<?php echo $user['username']; ?>">
                                </div>
                                <div>
                                    <p class="font-medium"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                                    <p class="text-sm text-gray-500">@<?php echo $user['username']; ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $user['email']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($user['is_admin']): ?>
                            <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Admin</span>
                            <?php else: ?>
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">User</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- <a href="#" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                            <a href="#" class="text-red-600 hover:text-red-800">Suspend</a> -->
                            <span class="text-gray-400">Actions Coming Soon</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs functionality
    const tabs = document.querySelectorAll('.tab-content');
    const tabLinks = document.querySelectorAll('a[href^="#"]');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Update tabs
            tabs.forEach(tab => {
                tab.classList.add('hidden');
            });

            // Update navigation
            tabLinks.forEach(tabLink => {
                tabLink.classList.remove('text-blue-600', 'bg-white', 'active-tab');
                tabLink.classList.add('text-gray-600', 'bg-gray-100');
            });

            // Show the active tab
            const targetId = this.getAttribute('href').substring(1) + '-content';
            document.getElementById(targetId).classList.remove('hidden');

            // Highlight the active tab
            this.classList.remove('text-gray-600', 'bg-gray-100');
            this.classList.add('text-blue-600', 'bg-white', 'active-tab');
        });
    });
});
</script>

<?php include 'php/footer.php'; ?>
