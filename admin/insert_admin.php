<?php
// Database connection
$host = 'localhost';
$dbname = 'auction_system';
$username = 'root';
$password = '';

$db = new mysqli($host, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Hash the password
$admin_username = 'admin';
$admin_password = password_hash('admin123', PASSWORD_BCRYPT);

// Check if the username already exists
$query = "SELECT id FROM admins WHERE username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Username exists, update the password
    $update_query = "UPDATE admins SET password = ? WHERE username = ?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bind_param("ss", $admin_password, $admin_username);

    if ($update_stmt->execute()) {
        echo "Admin user password updated successfully!";
    } else {
        echo "Error updating password: " . $update_stmt->error;
    }

    $update_stmt->close();
} else {
    // Username does not exist, insert a new admin user
    $insert_query = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $insert_stmt = $db->prepare($insert_query);
    $insert_stmt->bind_param("ss", $admin_username, $admin_password);

    if ($insert_stmt->execute()) {
        echo "Admin user inserted successfully!";
    } else {
        echo "Error inserting admin user: " . $insert_stmt->error;
    }

    $insert_stmt->close();
}

$stmt->close();
$db->close();
?>