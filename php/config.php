<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'auction_system');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

// Set the character set to ensure correct encoding
mysqli_set_charset($conn, "utf8");

// Session configuration
session_start();

// Site base URL - update this according to your server setup
define('BASE_URL', 'http://localhost/auction_system');

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if($conn) {
        $data = mysqli_real_escape_string($conn, $data);
    }
    return $data;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Function to redirect
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Function to display error message
function display_error($message) {
    return "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>
              <span class='block sm:inline'>$message</span>
            </div>";
}

// Function to display success message
function display_success($message) {
    return "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>
              <span class='block sm:inline'>$message</span>
            </div>";
}

?>
