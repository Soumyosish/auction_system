<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';
require_once 'email.php';
// Check if user is already logged in
if(is_logged_in()) {
    redirect('index.php');
    exit;
}

$error = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; // Don't sanitize password
    $confirm_password = $_POST['confirm_password']; // Don't sanitize password
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);

    // Attempt to register
    $result = register_user($username, $email, $password, $confirm_password, $first_name, $last_name);

    if ($result['success']) {
        // Prepare verification email
        $verification_token = $result['verification_token'];
        $verification_link = "http://localhost/auction_system/verify_email.php?token=$verification_token";
        $subject = "Verify Your Email - BidPulse";
        $message = "
            <html>
            <head>
                <title>Email Verification</title>
            </head>
            <body>
                <h1>Hi $first_name,</h1>
                <p>Thank you for registering on BidPulse. Please verify your email address by clicking the button below:</p>
                <a href='$verification_link' style='display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Verify Email</a>
                <p>If you did not register for this account, please ignore this email.</p>
            </body>
            </html>
        ";

        // Send verification email
        if (send_email($email, $subject, $message)) {
            $_SESSION['registration_success'] = "Registration successful! Please check your email to verify your account.";
            redirect('login.php');
            exit;
        } else {
            $error = "Registration successful, but the verification email could not be sent.";
        }
    } else {
        // Registration failed, display error message
        $error = implode('<br>', $result['errors']);
    }
}


include 'php/header.php';
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 my-8">
    <h1 class="text-2xl font-bold text-center mb-6">Create an Account</h1>

    <?php if(!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <form action="register.php" method="POST" id="registerForm">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="first_name" class="block text-gray-700 font-medium mb-2">First Name</label>
                <input type="text" id="first_name" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                <p id="first_name-error" class="text-red-500 text-sm mt-1 hidden"></p>
            </div>
            <div>
                <label for="last_name" class="block text-gray-700 font-medium mb-2">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                <p id="last_name-error" class="text-red-500 text-sm mt-1 hidden"></p>
            </div>
        </div>

        <!-- <div class="mb-4">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
            <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            <p id="username-error" class="text-red-500 text-sm mt-1 hidden"></p>
        </div> -->
        <div class="mb-4">
    <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
    <input type="text" id="username" name="username" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
           oninput="validateUsername(this)"
           required>
    <p id="username-error" class="text-red-500 text-sm mt-1 hidden">Username cannot contain single quotes (')</p>
</div>

<script>
function validateUsername(input) {
    const errorElement = document.getElementById('username-error');
    const alphanumericRegex = /^[a-zA-Z0-9]*$/; // Regular expression for alphanumeric characters only

    if (!alphanumericRegex.test(input.value)) {
        errorElement.textContent = "Username can only contain letters and numbers";
        errorElement.classList.remove('hidden');
        input.setCustomValidity("Username can only contain letters and numbers");
    } else {
        errorElement.classList.add('hidden');
        input.setCustomValidity("");
    }
}
</script>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            <p id="email-error" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <p id="password-error" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <div class="mb-6">
            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <p id="confirm_password-error" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Register</button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">Already have an account? <a href="login.php" class="text-blue-600 hover:text-blue-800">Login</a></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    registerForm.addEventListener('submit', function(e) {
        const validationRules = {
            first_name: {
                required: true,
                label: 'First Name'
            },
            last_name: {
                required: true,
                label: 'Last Name'
            },
            username: {
                required: true,
                minLength: 3,
                label: 'Username'
            },
            email: {
                required: true,
                pattern: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$',
                patternMessage: 'Please enter a valid email address',
                label: 'Email'
            },
            password: {
                required: true,
                minLength: 6,
                label: 'Password'
            },
            confirm_password: {
                required: true,
                label: 'Confirm Password'
            }
        };

        if (!validateForm('registerForm', validationRules)) {
            e.preventDefault();
            return;
        }

        // Check if passwords match
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const confirmPasswordError = document.getElementById('confirm_password-error');

        if (password !== confirmPassword) {
            confirmPasswordError.textContent = 'Passwords do not match';
            confirmPasswordError.classList.remove('hidden');
            document.getElementById('confirm_password').classList.add('border-red-500');
            e.preventDefault();
        }
    });
});
</script>

<?php   
include 'php/footer.php'; ?>



