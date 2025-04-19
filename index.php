<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Get featured auctions (limit to 8)
$featured_auctions = get_active_auctions(8);

// Get categories
$categories = get_all_categories();

include 'php/header.php';
?>

<!-- Hero Section - Update the flex and text classes -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-8 md:py-16 mb-8 md:mb-12 rounded-xl shadow-xl">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
            <div class="w-full md:w-1/2 space-y-4 md:space-y-6 text-center md:text-left">
                <h1 class="text-3xl md:text-5xl font-bold leading-tight">Discover & Bid on <span class="text-blue-200">Extraordinary</span> Items</h1>
                <p class="text-lg md:text-xl text-blue-100 leading-relaxed">Experience the thrill of online auctions with BidHub - where unique treasures await your winning bid.</p>
                <div class="flex flex-col sm:flex-row gap-4 sm:space-x-6 pt-4 justify-center md:justify-start">
                    <a href="browse.php" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-blue-50 transform hover:scale-105 transition duration-300 shadow-lg">Browse Auctions</a>
                    <?php if (!is_logged_in()): ?>
                    <a href="register.php" class="bg-blue-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-800 transform hover:scale-105 transition duration-300 shadow-lg">Join Now</a>
                    <?php else: ?>
                    <a href="create_auction.php" class="bg-blue-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-800 transform hover:scale-105 transition duration-300 shadow-lg">Sell Item</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="w-full md:w-1/2">
                <img src="https://www.kapoorwatch.com/blogs/wp-content/uploads/Banner1470x680-6.webp" alt="Auction Banner" 
                     class="rounded-xl shadow-2xl transform hover:scale-105 transition duration-500 w-full">
            </div>
        </div>
    </div>
</section>

<!-- Search Section - Update form layout -->
<section class="mb-8 md:mb-16 px-4">
    <div class="bg-white rounded-xl shadow-lg p-4 md:p-8 transform hover:shadow-xl transition duration-300">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6 text-gray-800">Find Your Next Treasure</h2>
        <form action="browse.php" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-grow">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="search" placeholder="What are you looking for?" 
                           class="w-full pl-12 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="relative">
                    <i class="fas fa-tag absolute left-4 top-3.5 text-gray-400"></i>
                    <select name="category" class="w-full pl-12 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                        <option value="0">All Categories</option>
                        <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-3.5 text-gray-400"></i>
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition duration-200 font-medium">
                Search Now
            </button>
        </form>
    </div>
</section>

<!-- Featured Auctions Section - Update grid -->
<section class="mb-8 md:mb-16 px-4">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 md:mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Featured Auctions</h2>
        <a href="browse.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
            View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>

    <?php if (!empty($featured_auctions)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-8">
        <?php foreach($featured_auctions as $auction): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
            <div class="h-56 overflow-hidden relative">
                <img src="<?php echo $auction['image_url']; ?>" alt="<?php echo $auction['title']; ?>" 
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-3 right-3 bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">
                    <?php echo $auction['category_name']; ?>
                </span>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo $auction['title']; ?></h3>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?php echo $auction['category_name']; ?></span>
                </div>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo substr($auction['description'], 0, 100) . (strlen($auction['description']) > 100 ? '...' : ''); ?></p>
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <p class="text-sm text-gray-500">Current Bid:</p>
                        <p class="text-xl font-bold text-green-600">Rs <?php echo number_format($auction['current_price'], 2); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Ends in:</p>
                        <p class="countdown text-sm" data-end="<?php echo $auction['end_date']; ?>">Loading...</p>
                    </div>
                </div>
                <a href="auction.php?id=<?php echo $auction['auction_id']; ?>" class="block w-full bg-blue-600 text-white text-center py-2 rounded-md hover:bg-blue-700 transition">View Auction</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- Categories Section - Update grid -->
<section class="mb-8 md:mb-12 px-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-6">Browse by Category</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach($categories as $category): ?>
        <a href="browse.php?category=<?php echo $category['category_id']; ?>" class="bg-white rounded-lg shadow-md p-4 text-center hover:bg-blue-50 transition">
            <div class="text-blue-600 text-3xl mb-2">
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
            <h3 class="font-semibold text-gray-800"><?php echo $category['name']; ?></h3>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- How It Works Section - Update grid -->
<section class="mb-8 md:mb-12 px-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-6">How It Works</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="inline-block p-4 bg-blue-100 text-blue-600 rounded-full mb-4">
                <i class="fas fa-search text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Find</h3>
            <p class="text-gray-600">Browse through thousands of items across various categories to find what interests you.</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="inline-block p-4 bg-blue-100 text-blue-600 rounded-full mb-4">
                <i class="fas fa-gavel text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Bid</h3>
            <p class="text-gray-600">Place bids on items you like and stay updated on your bidding status.</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="inline-block p-4 bg-blue-100 text-blue-600 rounded-full mb-4">
                <i class="fas fa-trophy text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Win</h3>
            <p class="text-gray-600">Win auctions by placing the highest bid before the auction ends.</p>
        </div>
    </div>
</section>

<!-- Testimonials Section - Update grid -->
<section class="mb-8 md:mb-12 px-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-6">What Our Users Say</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <!-- <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-full h-full object-cover">
                </div> -->
                <div>
                    <h3 class="font-bold">Ashok Kumar</h3>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <p class="text-gray-600">"I've been using BidHub for a year now and have found some amazing items at great prices. The bidding process is transparent and secure."</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <!-- <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-full h-full object-cover">
                </div> -->
                <div>
                    <h3 class="font-bold">Sarah</h3>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <p class="text-gray-600">"BidHub has been a game-changer for my collecting hobby. I've won several rare items, and the platform makes it easy to track my bidding history."</p>
        </div>
    </div>
</section>

<!-- Call to Action - Update padding and spacing -->
<section class="bg-blue-600 text-white py-8 md:py-12 rounded-lg mb-8 px-4">
    <div class="container mx-auto text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-3 md:mb-4">Ready to Start Bidding?</h2>
        <p class="text-lg md:text-xl mb-4 md:mb-6">Join thousands of users who buy and sell on BidHub every day.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:space-x-4">
            <a href="register.php" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">Create Account</a>
            <a href="login.php" class="bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Login</a>
        </div>
    </div>
</section>

<?php include 'php/footer.php'; ?>
