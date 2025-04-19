<?php
session_start();
require_once '../../php/config.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendError('Unauthorized access', 401);
}

try {
    // Get total products count
    $sql = "SELECT COUNT(*) as total FROM products";
    $result = $conn->query($sql);
    $total_products = $result->fetch_assoc()['total'];
    
    // Get active auctions count
    $sql = "SELECT COUNT(*) as total FROM auctions WHERE end_date > NOW()";
    $result = $conn->query($sql);
    $active_auctions = $result->fetch_assoc()['total'];
    
    // Get total bids count
    $sql = "SELECT COUNT(*) as total FROM bids";
    $result = $conn->query($sql);
    $total_bids = $result->fetch_assoc()['total'];
    
    // Get registered users count
    $sql = "SELECT COUNT(*) as total FROM users WHERE user_type = 'user'";
    $result = $conn->query($sql);
    $total_users = $result->fetch_assoc()['total'];
    
    // Prepare response data
    $data = [
        'total_products' => $total_products,
        'active_auctions' => $active_auctions,
        'total_bids' => $total_bids,
        'total_users' => $total_users
    ];
    
    sendSuccess($data);
} catch (Exception $e) {
    sendError('Error fetching dashboard stats: ' . $e->getMessage());
}

$conn->close();
?>