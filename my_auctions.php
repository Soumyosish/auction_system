<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Check if user is logged in
if(!is_logged_in()) {
    redirect('login.php');
    exit;
}

// Get user's auctions
$auctions = get_user_auctions($_SESSION['user_id']);

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-blue-600 text-white py-6 rounded-lg mb-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold">My Auctions</h1>
        <p class="text-lg mt-2">Manage your listed auctions</p>
    </div>
</div>

<!-- Create Auction Button -->
<div class="mb-8 flex justify-end">
    <a href="create_auction.php" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex items-center">
        <i class="fas fa-plus mr-2"></i> Create New Auction
    </a>
</div>

<!-- Auctions Section -->
<?php if (empty($auctions)): ?>
<div class="bg-white rounded-lg shadow-md p-6 text-center">
    <p class="text-gray-600 mb-4">You haven't created any auctions yet.</p>
    <a href="create_auction.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Create Your First Auction</a>
</div>
<?php else: ?>
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bids</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($auctions as $auction):
                    // Get bids for this auction
                    $auction_bids = get_auction_bids($auction['auction_id']);
                    $bid_count = count($auction_bids);
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" src="<?php echo $auction['image_url']; ?>" alt="<?php echo $auction['title']; ?>">
                            </div>
                            <div>
                                <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium"><?php echo $auction['title']; ?></a>
                                <div class="text-sm text-gray-500">
                                    <?php echo substr($auction['description'], 0, 50) . (strlen($auction['description']) > 50 ? '...' : ''); ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold">
                        Rs <?php echo number_format($auction['current_price'], 2); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php echo $bid_count; ?>
                    </td>
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if(strtotime($auction['end_date']) > time() && $auction['status'] == 'active'): ?>
                            <span class="countdown text-sm" data-end="<?php echo $auction['end_date']; ?>">Loading...</span>
                        <?php else: ?>
                            <span class="text-gray-500"><?php echo date('M d, Y H:i', strtotime($auction['end_date'])); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <?php if($auction['status'] == 'active' && $bid_count == 0): ?>
                        <!-- <a href="edit_auction.php?id=<?php echo $auction['auction_id']; ?>" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                        <a href="cancel_auction.php?id=<?php echo $auction['auction_id']; ?>" class="text-red-600 hover:text-red-800">Cancel</a> -->
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Auction Stats -->
<?php if (!empty($auctions)): ?>
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-800 mr-4">
                <i class="fas fa-gavel text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Auctions</p>
                <p class="text-xl font-bold"><?php echo count($auctions); ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-800 mr-4">
                <i class="fas fa-inr text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Auctions</p>
                <p class="text-xl font-bold">
                    <?php
                    $active_count = 0;
                    foreach($auctions as $auction) {
                        if($auction['status'] == 'active' && strtotime($auction['end_date']) > time()) {
                            $active_count++;
                        }
                    }
                    echo $active_count;
                    ?>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-800 mr-4">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Ended Auctions</p>
                <p class="text-xl font-bold">
                    <?php
                    $ended_count = 0;
                    foreach($auctions as $auction) {
                        if($auction['status'] == 'ended' || strtotime($auction['end_date']) <= time()) {
                            $ended_count++;
                        }
                    }
                    echo $ended_count;
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'php/footer.php'; ?>
