<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Get all categories
$categories = get_all_categories();

// Get filter parameters
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Get active auctions based on filters
if(!empty($search)) {
    $auctions = search_auctions($search, $category_id);
} else {
    $auctions = get_active_auctions(0, $category_id);
}

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-10 rounded-xl mb-8 shadow-lg">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-2">Browse Auctions</h1>
        <p class="text-xl text-blue-100">Discover unique items and place your bids</p>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="bg-white rounded-xl shadow-lg p-8 mb-8 transform hover:shadow-xl transition duration-300">
    <form action="browse.php" method="GET" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-6">
        <div class="flex-grow">
            <label for="search" class="block text-gray-700 font-semibold mb-2">Search Items</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" id="search" name="search" placeholder="What are you looking for?" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </div>
        <div class="md:w-1/4">
            <label for="category" class="block text-gray-700 font-semibold mb-2">Category</label>
            <div class="relative">
                <i class="fas fa-tag absolute left-3 top-3 text-gray-400"></i>
                <select id="category" name="category" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none transition duration-200">
                    <option value="0">All Categories</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>" <?php echo ($category_id == $category['category_id']) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400"></i>
            </div>
        </div>
        <div class="self-end">
            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition duration-200 font-medium">
                <i class="fas fa-filter mr-2"></i>Filter Results
            </button>
        </div>
    </form>
</div>

<!-- Auctions Grid -->
<section class="mb-12">
    <?php if (empty($auctions)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fas fa-search text-6xl text-blue-200 mb-4"></i>
        <p class="text-gray-600 text-lg mb-6">No auctions found matching your criteria.</p>
        <a href="browse.php" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 inline-flex items-center">
            <i class="fas fa-redo mr-2"></i>View All Auctions
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php foreach($auctions as $auction): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="h-56 overflow-hidden relative">
                <img src="<?php echo $auction['image_url']; ?>" alt="<?php echo $auction['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-3 right-3 bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium"><?php echo $auction['category_name']; ?></span>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2 truncate"><?php echo $auction['title']; ?></h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo $auction['description']; ?></p>
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Current Bid</p>
                        <p class="text-2xl font-bold text-green-600">Rs <?php echo number_format($auction['current_price'], 2); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Time Left</p>
                        <p class="countdown text-sm font-medium text-blue-600" data-end="<?php echo $auction['end_date']; ?>">Loading...</p>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <span class="text-sm text-gray-500 flex items-center">
                        <i class="fas fa-user-circle text-gray-400 mr-2"></i><?php echo $auction['seller_username']; ?>
                    </span>
                    <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">View Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- Categories Section -->
<section class="mb-12">
    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Browse by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php foreach($categories as $category): ?>
        <a href="browse.php?category=<?php echo $category['category_id']; ?>" 
           class="bg-white rounded-xl shadow-md p-6 text-center transform hover:scale-105 hover:shadow-xl transition duration-300 <?php echo ($category_id == $category['category_id']) ? 'ring-2 ring-blue-500' : ''; ?>">
            <div class="text-blue-600 text-4xl mb-4 transform group-hover:scale-110 transition duration-300">
                <?php
                // Icon for category (using Font Awesome)
                $icons = [
                    'Electronics' => 'fas fa-laptop',
                    'Fashion' => 'fas fa-tshirt',
                    'Home & Garden' => 'fas fa-home',
                    'Sports' => 'fas fa-futbol',
                    'Art' => 'fas fa-paint-brush',
                    'Collectibles' => 'fas fa-gem',
                    'Vehicles' => 'fas fa-car',
                    'Jewelry' => 'fas fa-ring'
                ];
                $icon = isset($icons[$category['name']]) ? $icons[$category['name']] : 'fas fa-tag';
                echo "<i class=\"$icon\"></i>";
                ?>
            </div>
            <h3 class="font-bold text-gray-800 text-lg"><?php echo $category['name']; ?></h3>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'php/footer.php'; ?>
