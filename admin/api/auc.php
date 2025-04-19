<?php
session_start();
require_once '../../php/config.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendError('Unauthorized access', 401);
}

try {
    // Get recent auctions
    $sql = "SELECT a.id, a.product_id, p.name as product_name, a.start_price, 
            (SELECT MAX(amount) FROM bids WHERE auction_id = a.id) as current_bid,
            a.start_date, a.end_date
            FROM auctions a
            JOIN products p ON a.product_id = p.id
            ORDER BY a.created_at DESC
            LIMIT 10";
    
    $result = $conn->query($sql);
    
    $auctions = [];
    while ($row = $result->fetch_assoc()) {
        // Determine auction status
        $status = 'upcoming';
        if (strtotime($row['start_date']) <= time()) {
            $status = strtotime($row['end_date']) > time() ? 'active' : 'ended';
        }
        
        $auctions[] = [
            'id' => $row['id'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'start_price' => $row['start_price'],
            'current_bid' => $row['current_bid'] ? $row['current_bid'] : $row['start_price'],
            'start_date' => formatDate($row['start_date']),
            'end_date' => formatDate($row['end_date']),
            'status' => $status
        ];
    }
    
    sendSuccess($auctions);
} catch (Exception $e) {
    sendError('Error fetching recent auctions: ' . $e->getMessage());
}

$conn->close();
?>