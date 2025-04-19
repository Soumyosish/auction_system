<?php
session_start();
require_once '../../php/config.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendError('Unauthorized access', 401);
}

try {
    // Get recent bids
    $sql = "SELECT b.id, b.user_id, u.username, b.auction_id, p.name as product_name, 
            b.amount, b.created_at
            FROM bids b
            JOIN users u ON b.user_id = u.id
            JOIN auctions a ON b.auction_id = a.id
            JOIN products p ON a.product_id = p.id
            ORDER BY b.created_at DESC
            LIMIT 10";
    
    $result = $conn->query($sql);
    
    $bids = [];
    while ($row = $result->fetch_assoc()) {
        $bids[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'username' => $row['username'],
            'auction_id' => $row['auction_id'],
            'product_name' => $row['product_name'],
            'amount' => $row['amount'],
            'created_at' => formatDate($row['created_at'])
        ];
    }
    
    sendSuccess($bids);
} catch (Exception $e) {
    sendError('Error fetching recent bids: ' . $e->getMessage());
}

$conn->close();
?>