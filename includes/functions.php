<?php
// Include config file
require_once "config.php";

// Function to sanitize user input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
}

// Function to redirect to another page
function redirect($url) {
    header("location: $url");
    exit;
}

// Function to get all categories
function get_categories() {
    global $conn;
    $categories = [];
    $sql = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }

    return $categories;
}

// Function to get auction items
function get_items($limit = 10, $category_id = null, $search = null) {
    global $conn;
    $items = [];

    $sql = "SELECT i.*, c.name as category_name, u.username as seller_name
            FROM items i
            JOIN categories c ON i.category_id = c.id
            JOIN users u ON i.seller_id = u.id
            WHERE 1=1";

    if($category_id) {
        $sql .= " AND i.category_id = " . (int)$category_id;
    }

    if($search) {
        $search = sanitize_input($search);
        $sql .= " AND (i.title LIKE '%$search%' OR i.description LIKE '%$search%')";
    }

    $sql .= " ORDER BY i.end_time ASC LIMIT " . (int)$limit;

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }

    return $items;
}

// Function to get a specific item
function get_item($item_id) {
    global $conn;

    $sql = "SELECT i.*, c.name as category_name, u.username as seller_name
            FROM items i
            JOIN categories c ON i.category_id = c.id
            JOIN users u ON i.seller_id = u.id
            WHERE i.id = " . (int)$item_id;

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

// Function to get bids for an item
function get_item_bids($item_id) {
    global $conn;
    $bids = [];

    $sql = "SELECT b.*, u.username
            FROM bids b
            JOIN users u ON b.user_id = u.id
            WHERE b.item_id = " . (int)$item_id .
            " ORDER BY b.bid_amount DESC";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $bids[] = $row;
        }
    }

    return $bids;
}

// Function to get user's bidding history
function get_user_bids($user_id) {
    global $conn;
    $bids = [];

    $sql = "SELECT b.*, i.title as item_title, i.current_price, i.end_time
            FROM bids b
            JOIN items i ON b.item_id = i.id
            WHERE b.user_id = " . (int)$user_id .
            " ORDER BY b.bid_time DESC";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $bids[] = $row;
        }
    }

    return $bids;
}

// Function to place a bid
function place_bid($item_id, $user_id, $bid_amount) {
    global $conn;

    // Get the item
    $item = get_item($item_id);

    if(!$item) {
        return ["success" => false, "message" => "Item not found"];
    }

    // Check if auction has ended
    if(strtotime($item["end_time"]) < time()) {
        return ["success" => false, "message" => "Auction has ended"];
    }

    // Check if bid amount is greater than current price
    if($bid_amount <= $item["current_price"]) {
        return ["success" => false, "message" => "Bid amount must be greater than current price"];
    }

    // Check if user is the seller
    if($item["seller_id"] == $user_id) {
        return ["success" => false, "message" => "You cannot bid on your own item"];
    }

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert the bid
        $sql = "INSERT INTO bids (item_id, user_id, bid_amount) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iid", $item_id, $user_id, $bid_amount);

        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error placing bid");
        }

        // Update the item's current price
        $sql = "UPDATE items SET current_price = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "di", $bid_amount, $item_id);

        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error updating item price");
        }

        // Commit the transaction
        mysqli_commit($conn);

        return ["success" => true, "message" => "Bid placed successfully"];
    } catch (Exception $e) {
        // Rollback the transaction
        mysqli_rollback($conn);
        return ["success" => false, "message" => $e->getMessage()];
    }
}

// Function to format currency
function format_currency($amount) {
    return "$" . number_format($amount, 2);
}

// Function to calculate time remaining
function time_remaining($end_time) {
    // Ensure the end_time is properly formatted for strtotime
    $end_timestamp = strtotime($end_time);
    $remaining = $end_timestamp - time();

    if($remaining <= 0) {
        return "Auction ended";
    }

    $days = floor($remaining / 86400);
    $hours = floor(($remaining % 86400) / 3600);
    $minutes = floor(($remaining % 3600) / 60);
    $seconds = $remaining % 60;

    if($days > 0) {
        return "$days days, $hours hours";
    } elseif($hours > 0) {
        return "$hours hours, $minutes minutes";
    } elseif($minutes > 0) {
        return "$minutes minutes, $seconds seconds";
    } else {
        return "$seconds seconds";
    }
}
?>
