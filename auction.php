<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Check if auction ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('browse.php');
    exit;
}

$auction_id = intval($_GET['id']);
$auction = get_auction_by_id($auction_id);

// Check if auction exists
if(!$auction) {
    $_SESSION['error'] = "Auction not found";
    redirect('browse.php');
    exit;
}

// Get bids for this auction
$bids = get_auction_bids($auction_id);

// Check if bid form was submitted
$bid_result = null;
$error = '';
$success = '';

if(isset($_POST['place_bid']) && is_logged_in()) {
    $bid_amount = floatval($_POST['bid_amount']);

    // Place bid
    $bid_result = place_bid($auction_id, $_SESSION['user_id'], $bid_amount);

    if($bid_result['success']) {
        $success = $bid_result['message'];

        // Refresh auction and bids data after successful bid
        $auction = get_auction_by_id($auction_id);
        $bids = get_auction_bids($auction_id);
    } else {
        $error = implode('<br>', $bid_result['errors']);
    }
}

include 'php/header.php';
?>

<div class="mb-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-4">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="index.php" class="text-gray-600 hover:text-blue-600">Home</a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <a href="browse.php" class="text-gray-600 hover:text-blue-600">Browse Auctions</a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <a href="browse.php?category=<?php echo $auction['category_id']; ?>" class="text-gray-600 hover:text-blue-600"><?php echo $auction['category_name']; ?></a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li>
                <span class="text-blue-600"><?php echo $auction['title']; ?></span>
            </li>
        </ol>
    </nav>

    <?php if(!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $success; ?></span>
    </div>
    <?php endif; ?>

    <!-- Auction Details -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <img src="<?php echo $auction['image_url']; ?>" alt="<?php echo $auction['title']; ?>" class="w-full h-96 object-cover">
            </div>
            <div class="md:w-1/2 p-6">
                <div class="flex justify-between items-start">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo $auction['title']; ?></h1>
                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full"><?php echo $auction['category_name']; ?></span>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-sm text-gray-600 mr-4">
                        <i class="fas fa-user mr-1"></i> Seller: <?php echo $auction['seller_username']; ?>
                    </span>
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i> Posted: <?php echo date('M d, Y', strtotime($auction['created_at'])); ?>
                    </span>
                </div>

                <div class="border-t border-b border-gray-200 py-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Starting Price:</span>
                        <span class="font-semibold">Rs <?php echo number_format($auction['starting_price'], 2); ?></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Current Bid:</span>
                        <span class="font-bold text-xl text-green-600">Rs <?php echo number_format($auction['current_price'], 2); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Auction Ends:</span>
                        <div class="text-right">
                            <span class="block text-sm text-gray-500"><?php echo date('M d, Y H:i', strtotime($auction['end_date'])); ?></span>
                            <span class="countdown font-bold" data-end="<?php echo $auction['end_date']; ?>">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Description</h2>
                    <p class="text-gray-700"><?php echo nl2br($auction['description']); ?></p>
                </div>

                <?php if($auction['status'] == 'active' && strtotime($auction['end_date']) > time()): ?>
                    <?php if(is_logged_in()): ?>
                        <?php if($_SESSION['user_id'] != $auction['seller_id']): ?>
                            <form action="auction.php?id=<?php echo $auction_id; ?>" method="POST" id="bidForm">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                                    <div class="flex-grow">
                                        <label for="bid_amount" class="block text-gray-700 text-sm font-medium mb-1">Your Bid (min Rs <?php echo number_format($auction['current_price'] + 0.01, 2); ?>)</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600">Rs </span>
                                            <input type="number" id="bid_amount" name="bid_amount" step="0.01" min="<?php echo $auction['current_price'] + 0.01; ?>" class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        </div>
                                        <p id="bid_amount-error" class="text-red-500 text-sm mt-1 hidden"></p>
                                    </div>
                                    <div class="self-end">
                                        <button type="submit" name="place_bid" class="w-full md:w-auto bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Place Bid</button>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                                <p>This is your auction. You cannot bid on your own items.</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                            <p>Please <a href="login.php" class="underline">login</a> to place a bid on this auction.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <p>This auction has ended and is no longer accepting bids.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bid History Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-2xl font-bold mb-4">Bid History</h2>

    <?php if(empty($bids)): ?>
    <p class="text-gray-600">No bids have been placed on this auction yet. Be the first to bid!</p>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidder</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bid Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($bids as $bid): ?>
                <tr class="<?php echo (is_logged_in() && $bid['bidder_id'] == $_SESSION['user_id']) ? 'bg-blue-50' : ''; ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php echo $bid['bidder_username']; ?>
                        <?php if(is_logged_in() && $bid['bidder_id'] == $_SESSION['user_id']): ?>
                        <span class="inline-block ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">You</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold">Rs <?php echo number_format($bid['bid_amount'], 2); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?php echo date('M d, Y H:i:s', strtotime($bid['bid_time'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Similar Auctions Section -->
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Similar Auctions</h2>

    <?php
    // Get similar auctions (same category, excluding current auction)
    $similar_auctions = get_active_auctions(4, $auction['category_id']);
    $similar_auctions = array_filter($similar_auctions, function($item) use ($auction_id) {
        return $item['auction_id'] != $auction_id;
    });

    if(empty($similar_auctions)):
    ?>
    <p class="text-gray-600 mb-4">No similar auctions found.</p>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach(array_slice($similar_auctions, 0, 4) as $similar): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden auction-card">
            <div class="h-48 overflow-hidden">
                <img src="<?php echo $similar['image_url']; ?>" alt="<?php echo $similar['title']; ?>" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo $similar['title']; ?></h3>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?php echo $similar['category_name']; ?></span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <p class="text-sm text-gray-500">Current Bid:</p>
                        <p class="text-xl font-bold text-green-600">Rs <?php echo number_format($similar['current_price'], 2); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Ends in:</p>
                        <p class="countdown text-sm" data-end="<?php echo $similar['end_date']; ?>">Loading...</p>
                    </div>
                </div>
                <a href="auction.php?id=<?php echo $similar['auction_id']; ?>" class="block w-full bg-blue-600 text-white text-center py-2 rounded-md hover:bg-blue-700 transition">View Auction</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidForm = document.getElementById('bidForm');

    if (bidForm) {
        bidForm.addEventListener('submit', function(e) {
            const validationRules = {
                bid_amount: {
                    required: true,
                    label: 'Bid Amount'
                }
            };

            if (!validateForm('bidForm', validationRules)) {
                e.preventDefault();
                return;
            }

            // Check if bid amount is greater than current price
            const bidAmount = parseFloat(document.getElementById('bid_amount').value);
            const currentPrice = <?php echo $auction['current_price']; ?>;
            const bidAmountError = document.getElementById('bid_amount-error');

            if (bidAmount <= currentPrice) {
                bidAmountError.textContent = 'Bid amount must be greater than current price';
                bidAmountError.classList.remove('hidden');
                document.getElementById('bid_amount').classList.add('border-red-500');
                e.preventDefault();
            }
        });
    }
});
</script>

<?php include 'php/footer.php'; ?>
