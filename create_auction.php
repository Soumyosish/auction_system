<?php
require_once 'php/config.php';
require_once 'php/auction_functions.php';

// Check if user is logged in
if(!is_logged_in()) {
    $_SESSION['error'] = "You must be logged in to create an auction";
    redirect('login.php');
    exit;
}

// Get all categories
$categories = get_all_categories();

$error = '';
$success = '';

// Check if form was submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $image_url = sanitize_input($_POST['image_url']);
    $category_id = intval($_POST['category_id']);
    $starting_price = floatval($_POST['starting_price']);
    $duration_days = intval($_POST['duration_days']);

    // Create auction
    $result = create_auction($title, $description, $image_url, $_SESSION['user_id'], $category_id, $starting_price, $duration_days);

    if($result['success']) {
        // Auction created successfully, redirect to auction page
        $success = $result['message'];
        $auction_id = $result['auction_id'];

        // Either show success message or redirect to auction page
        redirect("auction.php?id=$auction_id");
        exit;
    } else {
        // Auction creation failed, display error message
        $error = implode('<br>', $result['errors']);
    }
}

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-10 rounded-xl mb-8 shadow-lg">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-2">Create Your Auction</h1>
        <p class="text-xl text-blue-100">List your item and start receiving bids</p>
    </div>
</div>

<?php if(!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline"><?php echo $error; ?></span>
</div>
<?php endif; ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 transform hover:shadow-2xl transition duration-300">
        <form action="create_auction.php" method="POST" id="auctionForm" class="space-y-6">
            <div class="mb-6">
                <label for="title" class="block text-gray-700 text-lg font-semibold mb-2">Item Title</label>
                <input type="text" id="title" name="title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Enter a descriptive title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                <p id="title-error" class="text-red-500 text-sm mt-1 hidden"></p>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-lg font-semibold mb-2">Item Description</label>
                <textarea id="description" name="description" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Provide detailed information about your item..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <p id="description-error" class="text-red-500 text-sm mt-1 hidden"></p>
            </div>

            <div class="mb-6">
                <label for="image_url" class="block text-gray-700 text-lg font-semibold mb-2">Image URL</label>
                <div class="relative">
                    <i class="fas fa-link absolute left-3 top-3 text-gray-400"></i>
                    <input type="url" id="image_url" name="image_url" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="https://example.com/image.jpg" value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : ''; ?>" required>
                </div>
                <p class="text-sm text-gray-500 mt-2 italic"><i class="fas fa-info-circle mr-1"></i>Enter a direct URL to an image of your item</p>
                <p id="image_url-error" class="text-red-500 text-sm mt-1 hidden"></p>

                <div id="image-preview-container" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2 font-medium">Preview:</p>
                    <img id="image-preview" src="" alt="Item preview" class="max-w-full h-64 object-contain border border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category_id" class="block text-gray-700 text-lg font-semibold mb-2">Category</label>
                    <div class="relative">
                        <i class="fas fa-tag absolute left-3 top-3 text-gray-400"></i>
                        <select id="category_id" name="category_id" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none" required>
                            <option value="">Select a category</option>
                            <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['category_id']) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label for="duration_days" class="block text-gray-700 text-lg font-semibold mb-2">Duration</label>
                    <div class="relative">
                        <i class="fas fa-clock absolute left-3 top-3 text-gray-400"></i>
                        <select id="duration_days" name="duration_days" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none" required>
                            <option value="">Select duration</option>
                            <option value="1" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 1) ? 'selected' : ''; ?>>1 day</option>
                            <option value="3" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 3) ? 'selected' : ''; ?>>3 days</option>
                            <option value="5" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 5) ? 'selected' : ''; ?>>5 days</option>
                            <option value="7" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 7) ? 'selected' : ''; ?>>7 days</option>
                            <option value="10" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 10) ? 'selected' : ''; ?>>10 days</option>
                            <option value="14" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 14) ? 'selected' : ''; ?>>14 days</option>
                            <option value="30" <?php echo (isset($_POST['duration_days']) && $_POST['duration_days'] == 30) ? 'selected' : ''; ?>>30 days</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <label for="starting_price" class="block text-gray-700 text-lg font-semibold mb-2">Starting Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-600 font-medium">Rs  </span>
                    <input type="number" id="starting_price" name="starting_price" step="0.01" min="0.01" class="w-full pl-8 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="0.00" value="<?php echo isset($_POST['starting_price']) ? htmlspecialchars($_POST['starting_price']) : ''; ?>" required>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="index.php" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition duration-200 font-medium">
                    <i class="fas fa-gavel mr-2"></i>Create Auction
                </button>
            </div>
        </form>
    </div>

    <!-- Auction Tips -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 mb-8 shadow-md">
        <h2 class="text-2xl font-bold text-blue-800 mb-6 flex items-center">
            <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>Tips for Success
        </h2>
        <ul class="space-y-4 text-blue-900">
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2"></i>
                <span>Use a clear, descriptive title that includes key information about your item</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2"></i>
                <span>Provide a detailed description including condition, dimensions, and any defects</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2"></i>
                <span>Use high-quality images that clearly show your item from multiple angles</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2"></i>
                <span>Set a reasonable starting price to attract initial bids</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2"></i>
                <span>Choose the appropriate category to help buyers find your item</span>
            </li>
        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const auctionForm = document.getElementById('auctionForm');
    const imageUrlInput = document.getElementById('image_url');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');

    // Image URL preview
    imageUrlInput.addEventListener('input', function() {
        const imageUrl = this.value.trim();
        if(imageUrl && isValidURL(imageUrl)) {
            imagePreview.src = imageUrl;
            imagePreviewContainer.classList.remove('hidden');

            // Handle image load error
            imagePreview.onerror = function() {
                imagePreviewContainer.classList.add('hidden');
                document.getElementById('image_url-error').textContent = 'Invalid image URL or image not accessible';
                document.getElementById('image_url-error').classList.remove('hidden');
                imageUrlInput.classList.add('border-red-500');
            };

            // Handle image load success
            imagePreview.onload = function() {
                document.getElementById('image_url-error').classList.add('hidden');
                imageUrlInput.classList.remove('border-red-500');
            };
        } else {
            imagePreviewContainer.classList.add('hidden');
        }
    });

    // Form validation
    auctionForm.addEventListener('submit', function(e) {
        const validationRules = {
            title: {
                required: true,
                minLength: 3,
                label: 'Title'
            },
            description: {
                required: true,
                minLength: 10,
                label: 'Description'
            },
            image_url: {
                required: true,
                pattern: '^https?://.+\\..+',
                patternMessage: 'Please enter a valid URL',
                label: 'Image URL'
            },
            category_id: {
                required: true,
                label: 'Category'
            },
            duration_days: {
                required: true,
                label: 'Auction Duration'
            },
            starting_price: {
                required: true,
                label: 'Starting Price'
            }
        };

        if (!validateForm('auctionForm', validationRules)) {
            e.preventDefault();
        }
    });

    // Helper function to validate URL
    function isValidURL(url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    }
});
</script>

<?php include 'php/footer.php'; ?>
