<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';


if(is_logged_in()) {
    redirect('index.php');
    exit;
}

$error = '';
$message = '';


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password']; 

  
    $result = login_user($username, $password);

    if($result['success']) {
      
        redirect('index.php');
        exit;
    } else {
       
        $error = implode('<br>', $result['errors']);
    }
}


if(isset($_SESSION['registration_success'])) {
    $message = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

include 'php/header.php';
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 mt-8">
    <h1 class="text-2xl font-bold text-center mb-6">Login to Your Account</h1>

    <?php if(!empty($message)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $message; ?></span>
    </div>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
    <?php endif; ?>

    <form action="login.php" method="POST" id="loginForm">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username or Email</label>
            <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            <p id="username-error" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <p id="password-error" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Login</button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">Don't have an account? <a href="register.php" class="text-blue-600 hover:text-blue-800">Register</a></p>
        <p class="text-gray-600 mt-2">Forgot your password? <a href="forgot_password.php" class="text-blue-600 hover:text-blue-800">Reset Password</a></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(e) {
        const validationRules = {
            username: {
                required: true,
                label: 'Username or Email'
            },
            password: {
                required: true,
                label: 'Password'
            }
        };

        if (!validateForm('loginForm', validationRules)) {
            e.preventDefault();
        }
    });
});
</script>

<?php include 'php/footer.php'; ?>
