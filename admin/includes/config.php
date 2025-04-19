<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "auction_system";

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to generate a random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Function to redirect to a URL
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to display error message as JSON
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit();
}

// Function to display success message as JSON
function sendSuccess($data, $message = 'Success') {
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit();
}

// Function to upload an image
function uploadImage($file, $directory = '../uploads/') {
    // Check if directory exists, if not create it
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    
    $target_dir = $directory;
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is an actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['error' => 'File is not an image.'];
    }
    
    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return ['error' => 'File is too large. Maximum size is 5MB.'];
    }
    
    // Allow only certain file formats
    $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($file_extension, $allowed_extensions)) {
        return ['error' => 'Only JPG, JPEG, PNG & GIF files are allowed.'];
    }
    
    // Try to upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => $new_filename, 'path' => $target_file];
    } else {
        return ['error' => 'There was an error uploading your file.'];
    }
}

// Function to format date
function formatDate($date) {
    return date('M d, Y h:i A', strtotime($date));
}

// Function to check if auction has ended
function hasAuctionEnded($end_date) {
    return strtotime($end_date) < time();
}
?>