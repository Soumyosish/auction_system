<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Check if user is logged in
if(!is_logged_in()) {
    redirect('login.php');
    exit;
}

// Get user's bids
$bids = get_user_bids($_SESSION['user_id']);

// Get user's won auctions
$won_auctions = get_user_won_auctions($_SESSION['user_id']);

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-blue-600 text-white py-6 rounded-lg mb-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold">My Bids</h1>
        <p class="text-lg mt-2">Track your bidding activity and auction wins</p>
    </div>
</div>

<!-- Tabs -->
<div class="mb-8">
    <ul class="flex border-b border-gray-200">
        <li class="mr-1">
            <a href="#active-bids" class="inline-block py-2 px-4 bg-white rounded-t-lg border-l border-t border-r border-gray-200 text-blue-600 font-medium active-tab">
                Active Bids
            </a>
        </li>
        <li class="mr-1">
            <a href="#won-auctions" class="inline-block py-2 px-4 bg-gray-100 rounded-t-lg border-l border-t border-r border-gray-200 text-gray-600 hover:text-blue-600 hover:bg-white">
                Won Auctions
            </a>
        </li>
    </ul>
</div>

<!-- Active Bids Section -->
<div id="active-bids-content" class="tab-content">
    <?php if (empty($bids)): ?>
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <p class="text-gray-600 mb-4">You haven't placed any bids yet.</p>
        <a href="browse.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Browse Auctions</a>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Your Bid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bid Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach($bids as $bid): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="auction.php?id=<?php echo $bid['auction_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium"><?php echo $bid['auction_title']; ?></a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">
                            Rs <?php echo number_format($bid['bid_amount'], 2); ?>
                            <?php if($bid['bid_amount'] == $bid['current_price']): ?>
                            <span class="inline-block ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Highest Bid</span>
                            <?php elseif($bid['bid_amount'] < $bid['current_price']): ?>
                            <span class="inline-block ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Outbid</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            Rs <?php echo number_format($bid['current_price'], 2); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($bid['status'] == 'active'): ?>
                                <?php if(strtotime($bid['end_date']) > time()): ?>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Active</span>
                                <?php else: ?>
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Ended</span>
                                <?php endif; ?>
                            <?php else: ?>
                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Ended</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            <?php echo date('M d, Y H:i', strtotime($bid['bid_time'])); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="auction.php?id=<?php echo $bid['auction_id']; ?>" class="text-blue-600 hover:text-blue-800">View Auction</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Won Auctions Section -->
<div id="won-auctions-content" class="tab-content hidden">
    <?php if (empty($won_auctions)): ?>
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <p class="text-gray-600 mb-4">You haven't won any auctions yet.</p>
        <a href="browse.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Browse Auctions</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($won_auctions as $auction): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 overflow-hidden">
                <img src="<?php echo $auction['image_url']; ?>" alt="<?php echo $auction['title']; ?>" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo $auction['title']; ?></h3>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Won</span>
                </div>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo substr($auction['description'], 0, 100) . (strlen($auction['description']) > 100 ? '...' : ''); ?></p>
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <p class="text-sm text-gray-500">Final Price:</p>
                        <p class="text-xl font-bold text-green-600">Rs <?php echo number_format($auction['winning_bid'], 2); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Auction Ended:</p>
                        <p class="text-sm text-gray-600"><?php echo date('M d, Y', strtotime($auction['end_date'])); ?></p>
                    </div>
                </div>
                <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="block w-full bg-blue-600 text-white text-center py-2 rounded-md hover:bg-blue-700 transition">View Details</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
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
