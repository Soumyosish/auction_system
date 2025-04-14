<?php
require_once 'config.php';

// Function to get all active auctions
function get_active_auctions($limit = 0, $category_id = 0, $search = '') {
    global $conn;

    $query = "SELECT a.*, c.name as category_name, u.username as seller_username
              FROM auctions a
              INNER JOIN categories c ON a.category_id = c.category_id
              INNER JOIN users u ON a.seller_id = u.user_id
              WHERE a.status = 'active' AND a.end_date > NOW()";

    // Add category filter
    if($category_id > 0) {
        $query .= " AND a.category_id = " . intval($category_id);
    }

    // Add search filter
    if(!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " AND (a.title LIKE '%$search%' OR a.description LIKE '%$search%')";
    }

    $query .= " ORDER BY a.end_date ASC";

    // Add limit
    if($limit > 0) {
        $query .= " LIMIT " . intval($limit);
    }

    $result = mysqli_query($conn, $query);
    $auctions = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $auctions[] = $row;
        }
    }

    return $auctions;
}

// Function to get auction by ID
function get_auction_by_id($auction_id) {
    global $conn;

    $query = "SELECT a.*, c.name as category_name, u.username as seller_username, u.email as seller_email
              FROM auctions a
              INNER JOIN categories c ON a.category_id = c.category_id
              INNER JOIN users u ON a.seller_id = u.user_id
              WHERE a.auction_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $auction_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

// Function to get bids for an auction
function get_auction_bids($auction_id) {
    global $conn;

    $query = "SELECT b.*, u.username as bidder_username
              FROM bids b
              INNER JOIN users u ON b.bidder_id = u.user_id
              WHERE b.auction_id = ?
              ORDER BY b.bid_amount DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $auction_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bids = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $bids[] = $row;
        }
    }

    return $bids;
}

// Function to place a bid
function place_bid($auction_id, $bidder_id, $bid_amount) {
    global $conn;
    $errors = [];

    // Get auction details
    $auction = get_auction_by_id($auction_id);

    if(!$auction) {
        $errors[] = "Auction not found";
        return ["success" => false, "errors" => $errors];
    }

    // Check if auction is active
    if($auction['status'] != 'active') {
        $errors[] = "This auction is not active";
        return ["success" => false, "errors" => $errors];
    }

    // Check if auction has ended
    if(strtotime($auction['end_date']) < time()) {
        $errors[] = "This auction has ended";

        // Update auction status
        $update_query = "UPDATE auctions SET status = 'ended' WHERE auction_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $auction_id);
        mysqli_stmt_execute($stmt);

        return ["success" => false, "errors" => $errors];
    }

    // Check if bidder is the seller
    if($auction['seller_id'] == $bidder_id) {
        $errors[] = "You cannot bid on your own auction";
        return ["success" => false, "errors" => $errors];
    }

    // Check if bid amount is greater than current price
    if($bid_amount <= $auction['current_price']) {
        $errors[] = "Bid amount must be greater than current price (" . $auction['current_price'] . ")";
        return ["success" => false, "errors" => $errors];
    }

    // If no errors, proceed with bidding
    if(empty($errors)) {
        // Begin transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert bid into database
            $insert_query = "INSERT INTO bids (auction_id, bidder_id, bid_amount) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "iid", $auction_id, $bidder_id, $bid_amount);
            mysqli_stmt_execute($stmt);

            // Update current price in auctions table
            $update_query = "UPDATE auctions SET current_price = ? WHERE auction_id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "di", $bid_amount, $auction_id);
            mysqli_stmt_execute($stmt);

            // Commit transaction
            mysqli_commit($conn);

            return ["success" => true, "message" => "Bid placed successfully"];
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $errors[] = "Bid failed: " . $e->getMessage();
        }
    }

    return ["success" => false, "errors" => $errors];
}

// Function to create a new auction
function create_auction($title, $description, $image_url, $seller_id, $category_id, $starting_price, $duration_days) {
    global $conn;
    $errors = [];

    // Validate title
    if(empty($title)) {
        $errors[] = "Title is required";
    } else if(strlen($title) < 3 || strlen($title) > 100) {
        $errors[] = "Title must be between 3 and 100 characters";
    }

    // Validate description
    if(empty($description)) {
        $errors[] = "Description is required";
    }

    // Validate image URL
    if(empty($image_url)) {
        $errors[] = "Image URL is required";
    } else if(!filter_var($image_url, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid image URL format";
    }

    // Validate category
    if(empty($category_id) || $category_id <= 0) {
        $errors[] = "Category is required";
    } else {
        // Check if category exists
        $check_query = "SELECT * FROM categories WHERE category_id = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "i", $category_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) != 1) {
            $errors[] = "Invalid category";
        }
    }

    // Validate starting price
    if(empty($starting_price) || !is_numeric($starting_price) || $starting_price <= 0) {
        $errors[] = "Starting price must be a positive number";
    }

    // Validate duration
    if(empty($duration_days) || !is_numeric($duration_days) || $duration_days <= 0 || $duration_days > 30) {
        $errors[] = "Duration must be between 1 and 30 days";
    }

    // If no errors, proceed with creating auction
    if(empty($errors)) {
        // Calculate end date
        $start_date = date('Y-m-d H:i:s');
        $end_date = date('Y-m-d H:i:s', strtotime("+$duration_days days"));

        // Insert auction into database
        $insert_query = "INSERT INTO auctions (title, description, image_url, seller_id, category_id, starting_price, current_price, start_date, end_date)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssiiddss", $title, $description, $image_url, $seller_id, $category_id, $starting_price, $starting_price, $start_date, $end_date);

        if(mysqli_stmt_execute($stmt)) {
            return ["success" => true, "message" => "Auction created successfully", "auction_id" => mysqli_insert_id($conn)];
        } else {
            $errors[] = "Auction creation failed: " . mysqli_error($conn);
        }
    }

    return ["success" => false, "errors" => $errors];
}

// Function to get all categories
function get_all_categories() {
    global $conn;

    $query = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($conn, $query);
    $categories = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }

    return $categories;
}

// Function to get auctions by user
function get_user_auctions($user_id) {
    global $conn;

    $query = "SELECT a.*, c.name as category_name
              FROM auctions a
              INNER JOIN categories c ON a.category_id = c.category_id
              WHERE a.seller_id = ?
              ORDER BY a.created_at DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $auctions = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $auctions[] = $row;
        }
    }

    return $auctions;
}

// Function to get bidding history for a user
function get_user_bids($user_id) {
    global $conn;

    $query = "SELECT b.*, a.title as auction_title, a.current_price, a.end_date, a.status
              FROM bids b
              INNER JOIN auctions a ON b.auction_id = a.auction_id
              WHERE b.bidder_id = ?
              ORDER BY b.bid_time DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bids = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $bids[] = $row;
        }
    }

    return $bids;
}

// Function to get won auctions by user
function get_user_won_auctions($user_id) {
    global $conn;

    $query = "SELECT a.*, c.name as category_name, u.username as seller_username,
              (SELECT MAX(bid_amount) FROM bids WHERE auction_id = a.auction_id) as winning_bid
              FROM auctions a
              INNER JOIN categories c ON a.category_id = c.category_id
              INNER JOIN users u ON a.seller_id = u.user_id
              WHERE a.status = 'ended' AND a.end_date < NOW()
              AND (SELECT bidder_id FROM bids WHERE auction_id = a.auction_id ORDER BY bid_amount DESC LIMIT 1) = ?
              ORDER BY a.end_date DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $auctions = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $auctions[] = $row;
        }
    }

    return $auctions;
}

// Function to update auction status
function update_auction_status() {
    global $conn;

    // Update auctions that have ended
    $update_query = "UPDATE auctions SET status = 'ended' WHERE status = 'active' AND end_date < NOW()";
    mysqli_query($conn, $update_query);

    return true;
}

// Function to search auctions
function search_auctions($search_term, $category_id = 0) {
    global $conn;

    $search_term = mysqli_real_escape_string($conn, $search_term);

    $query = "SELECT a.*, c.name as category_name, u.username as seller_username
              FROM auctions a
              INNER JOIN categories c ON a.category_id = c.category_id
              INNER JOIN users u ON a.seller_id = u.user_id
              WHERE a.status = 'active' AND a.end_date > NOW()
              AND (a.title LIKE '%$search_term%' OR a.description LIKE '%$search_term%')";

    // Add category filter
    if($category_id > 0) {
        $query .= " AND a.category_id = " . intval($category_id);
    }

    $query .= " ORDER BY a.end_date ASC";

    $result = mysqli_query($conn, $query);
    $auctions = [];

    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $auctions[] = $row;
        }
    }

    return $auctions;
}
?>
