<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';

// Check if user is logged in
if(!is_logged_in()) {
    redirect('login.php');
    exit;
}

$error = '';
$success = '';

// Get user data
$user = get_user_by_id($_SESSION['user_id']);

// Check if profile update form was submitted
if(isset($_POST['update_profile'])) {
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);

    // Update profile
    $result = update_user_profile($_SESSION['user_id'], $first_name, $last_name, $email);

    if($result['success']) {
        $success = $result['message'];

        // Refresh user data
        $user = get_user_by_id($_SESSION['user_id']);
    } else {
        $error = implode('<br>', $result['errors']);
    }
}

// Check if password change form was submitted
if(isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Change password
    $result = change_password($_SESSION['user_id'], $current_password, $new_password, $confirm_password);

    if($result['success']) {
        $success = $result['message'];
    } else {
        $error = implode('<br>', $result['errors']);
    }
}

include 'php/header.php';
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-8 rounded-lg mb-8 shadow-lg">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-center">My Profile</h1>
        <p class="text-xl mt-2 text-center text-blue-100">Manage your personal information and settings</p>
    </div>
</div>

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

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <!-- User Info Card -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-8 transform hover:scale-105 transition duration-300">
            <div class="flex flex-col items-center">
                <!-- <div class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-blue-500 shadow-md">
                    <img src="<?php echo $user['profile_image'] ? $user['profile_image'] : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?d=mp&s=200'; ?>" alt="Profile Image" class="w-full h-full object-cover">
                </div> -->
                <h2 class="text-2xl font-bold text-gray-800"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
                <p class="text-blue-600 font-medium mb-4">@<?php echo $user['username']; ?></p>
                <div class="w-full border-t border-gray-200 pt-4 mt-2 space-y-3">
                    <p class="flex justify-between py-2 hover:bg-gray-50 px-2 rounded">
                        <span class="text-gray-600"><i class="far fa-calendar-alt mr-2"></i>Member Since:</span>
                        <span class="font-medium text-gray-800"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                    </p>
                    <p class="flex justify-between py-2 hover:bg-gray-50 px-2 rounded">
                        <span class="text-gray-600"><i class="far fa-envelope mr-2"></i>Email:</span>
                        <span class="font-medium text-gray-800"><?php echo $user['email']; ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Settings -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8 hover:shadow-xl transition duration-300">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2"><i class="fas fa-user-edit mr-2 text-blue-600"></i>Update Profile Information</h2>
            <form action="profile.php" method="POST" id="profileForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first_name" class="block text-gray-700 font-medium mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" value="<?php echo $user['first_name']; ?>" required>
                        <p id="first_name-error" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                    <div>
                        <label for="last_name" class="block text-gray-700 font-medium mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $user['last_name']; ?>" required>
                        <p id="last_name-error" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $user['email']; ?>" required>
                    <p id="email-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" name="update_profile" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Update Profile</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2"><i class="fas fa-lock mr-2 text-blue-600"></i>Change Password</h2>
            <form action="profile.php" method="POST" id="passwordForm">
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p id="current_password-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700 font-medium mb-2">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p id="new_password-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p id="confirm_password-error" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" name="change_password" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <a href="my_auctions.php" class="bg-white rounded-xl shadow-lg p-8 text-center transform hover:scale-105 hover:bg-blue-50 transition duration-300">
        <i class="fas fa-list text-4xl text-blue-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">My Auctions</h3>
        <p class="text-gray-600">View and manage your auction listings</p>
    </a>
    <a href="my_bids.php" class="bg-white rounded-xl shadow-lg p-8 text-center transform hover:scale-105 hover:bg-blue-50 transition duration-300">
        <i class="fas fa-gavel text-4xl text-blue-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">My Bids</h3>
        <p class="text-gray-600">Track your active and won bids</p>
    </a>
    <a href="create_auction.php" class="bg-white rounded-xl shadow-lg p-8 text-center transform hover:scale-105 hover:bg-blue-50 transition duration-300">
        <i class="fas fa-plus-circle text-4xl text-blue-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Create Auction</h3>
        <p class="text-gray-600">Start a new auction listing</p>
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile form validation
    const profileForm = document.getElementById('profileForm');

    profileForm.addEventListener('submit', function(e) {
        const validationRules = {
            first_name: {
                required: true,
                label: 'First Name'
            },
            last_name: {
                required: true,
                label: 'Last Name'
            },
            email: {
                required: true,
                pattern: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                patternMessage: 'Please enter a valid email address',
                label: 'Email'
            }
        };

        if (!validateForm('profileForm', validationRules)) {
            e.preventDefault();
        }
    });

    // Password form validation
    const passwordForm = document.getElementById('passwordForm');

    passwordForm.addEventListener('submit', function(e) {
        const validationRules = {
            current_password: {
                required: true,
                label: 'Current Password'
            },
            new_password: {
                required: true,
                minLength: 6,
                label: 'New Password'
            },
            confirm_password: {
                required: true,
                label: 'Confirm Password'
            }
        };

        if (!validateForm('passwordForm', validationRules)) {
            e.preventDefault();
            return;
        }

        // Check if passwords match
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const confirmPasswordError = document.getElementById('confirm_password-error');

        if (newPassword !== confirmPassword) {
            confirmPasswordError.textContent = 'Passwords do not match';
            confirmPasswordError.classList.remove('hidden');
            document.getElementById('confirm_password').classList.add('border-red-500');
            e.preventDefault();
        }
    });
});
</script>

<?php include 'php/footer.php'; ?>
