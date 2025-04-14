<?php
require_once 'php/config.php';
include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-blue-600 text-white py-6 rounded-lg mb-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold">About BidPulse</h1>
        <p class="text-lg mt-2">Learn more about our online auction platform</p>
    </div>
</div>

<!-- About Section -->
<div class="bg-white rounded-lg shadow-md p-8 mb-8">
    <div class="flex flex-col md:flex-row">
        <div class="md:w-1/2 mb-6 md:mb-0 md:pr-8">
            <h2 class="text-2xl font-bold mb-4">Our Story</h2>
            <p class="text-gray-700 mb-4">
                BidPulse was founded in 2025 with a simple mission: to create a transparent, secure, and user-friendly platform where people can buy and sell items through online auctions.
            </p>
            <p class="text-gray-700 mb-4">
                Our platform brings together buyers and sellers from around the world, providing a marketplace for unique items, collectibles, electronics, fashion, and much more. We believe in the thrill of the auction process and the joy of finding that perfect item at the right price.
            </p>
            <p class="text-gray-700">
                With BidPulse, we've simplified the auction experience, making it accessible to everyone while maintaining the integrity and excitement of traditional auctions.
            </p>
        </div>
        <div class="md:w-1/2">
            <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2274&q=80" alt="Team working together" class="rounded-lg shadow-md w-full">
        </div>
    </div>
</div>

<!-- Mission & Values -->
<div class="bg-blue-50 rounded-lg p-8 mb-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Our Mission & Values</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="inline-block p-4 bg-blue-100 text-blue-600 rounded-full mb-4">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Trust & Security</h3>
            <p class="text-gray-700">
                We prioritize the security of transactions and user data. Our platform employs advanced security measures to ensure a safe environment for all users.
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="inline-block p-4 bg-blue-100 text-blue-600 rounded-full mb-4">
                <i class="fas fa-balance-scale text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Fairness & Transparency</h3>
            <p class="text-gray-700">
                We believe in transparent auction processes where all users have equal opportunities. Our bidding system is designed to be fair, clear, and straightforward.
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="inline-block p-4 bg-blue-100 text-blue-600 rounded-full mb-4">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Community</h3>
            <p class="text-gray-700">
                We foster a community of passionate buyers and sellers. BidPulse is a place where people connect over shared interests and the excitement of auctions.
            </p>
        </div>
    </div>
</div>

<!-- How It Works -->
<div class="bg-white rounded-lg shadow-md p-8 mb-8">
    <h2 class="text-2xl font-bold mb-6 text-center">How BidPulse Works</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center">
            <div class="relative mb-4">
                <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">1</div>
                <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-blue-200"></div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Create an Account</h3>
            <p class="text-gray-600">Sign up for free and set up your profile to start using BidPulse.</p>
        </div>
        <div class="text-center">
            <div class="relative mb-4">
                <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">2</div>
                <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-blue-200"></div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Browse Auctions</h3>
            <p class="text-gray-600">Explore thousands of items across various categories.</p>
        </div>
        <div class="text-center">
            <div class="relative mb-4">
                <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">3</div>
                <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-blue-200"></div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Place Bids</h3>
            <p class="text-gray-600">Bid on items you're interested in and track your bidding activity.</p>
        </div>
        <div class="text-center">
            <div class="relative mb-4">
                <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">4</div>
            </div>
            <h3 class="text-lg font-semibold mb-2">Win & Pay</h3>
            <p class="text-gray-600">If you're the highest bidder when an auction ends, you win the item!</p>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="bg-white rounded-lg shadow-md p-8 mb-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Meet Our Team</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <!-- <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                <img src="https://randomuser.me/api/portraits/men/76.jpg" alt="Team Member" class="w-full h-full object-cover">
            </div> -->
            <h3 class="text-lg font-semibold">Soumyosish Pal</h3>
            <!-- <p class="text-blue-600">CEO & Founder</p> -->
        </div>
        <div class="text-center">
            <!-- <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Team Member" class="w-full h-full object-cover">
            </div> -->
            <h3 class="text-lg font-semibold">Shreya Tripathi</h3>
            <!-- <p class="text-blue-600">CTO</p> -->
        </div>
        <div class="text-center">
            <!-- <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Team Member" class="w-full h-full object-cover">
            </div> -->
            <h3 class="text-lg font-semibold">Manish</h3>
            <!-- <p class="text-blue-600">Head of Operations</p> -->
        </div>
        <div class="text-center">
            <!-- <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Team Member" class="w-full h-full object-cover">
            </div> -->
            <h3 class="text-lg font-semibold">Tushar Mishra</h3>
            <!-- <p class="text-blue-600">Customer Success</p> -->
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-blue-600 text-white py-12 rounded-lg text-center mb-8">
    <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
    <p class="text-xl mb-6">Join thousands of users who buy and sell on BidPulse every day.</p>
    <?php if (!is_logged_in()): ?>
    <div class="flex justify-center space-x-4">
        <a href="register.php" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">Create Account</a>
        <a href="browse.php" class="bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Browse Auctions</a>
    </div>
    <?php else: ?>
    <div class="flex justify-center space-x-4">
        <a href="browse.php" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">Browse Auctions</a>
        <a href="create_auction.php" class="bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Create Auction</a>
    </div>
    <?php endif; ?>
</div>

<?php include 'php/footer.php'; ?>
