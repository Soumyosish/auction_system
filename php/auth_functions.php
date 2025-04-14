<?php
require_once 'config.php';

// Function to register a new user
function register_user($username, $email, $password, $confirm_password, $first_name, $last_name) {
    global $conn;
    $errors = [];

    // Validate username
    if(empty($username)) {
        $errors[] = "Username is required";
    } else if(strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Username must be between 3 and 50 characters";
    } else {
        // Check if username already exists
        $check_query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
            $errors[] = "Username already exists";
        }
    }

    // Validate email
    if(empty($email)) {
        $errors[] = "Email is required";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists";
        }
    }

    // Validate password
    if(empty($password)) {
        $errors[] = "Password is required";
    } else if(strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    // Validate confirm password
    if(empty($confirm_password)) {
        $errors[] = "Confirm password is required";
    } else if($password != $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Validate first name
    if(empty($first_name)) {
        $errors[] = "First name is required";
    }

    // Validate last name
    if(empty($last_name)) {
        $errors[] = "Last name is required";
    }

    // If no errors, proceed with registration
    if(empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $insert_query = "INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $hashed_password, $first_name, $last_name);

        if(mysqli_stmt_execute($stmt)) {
            return ["success" => true, "message" => "Registration successful. You can now log in."];
        } else {
            $errors[] = "Registration failed: " . mysqli_error($conn);
        }
    }

    return ["success" => false, "errors" => $errors];
}

// Function to login a user
function login_user($username, $password) {
    global $conn;
    $errors = [];

    // Validate username
    if(empty($username)) {
        $errors[] = "Username or email is required";
    }

    // Validate password
    if(empty($password)) {
        $errors[] = "Password is required";
    }

    // If no errors, proceed with login
    if(empty($errors)) {
        // Check if username exists (can be username or email)
        $login_query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $login_query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if(password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['is_admin'] = $user['is_admin'];

                return ["success" => true, "message" => "Login successful. Welcome back, " . $user['first_name'] . "!"];
            } else {
                $errors[] = "Invalid password";
            }
        } else {
            $errors[] = "User not found";
        }
    }

    return ["success" => false, "errors" => $errors];
}

// Function to logout a user
function logout_user() {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    return ["success" => true, "message" => "Logout successful"];
}

// Function to get user data by ID
function get_user_by_id($user_id) {
    global $conn;

    $query = "SELECT user_id, username, email, first_name, last_name, profile_image, is_admin, created_at
              FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

// Function to update user profile
function update_user_profile($user_id, $first_name, $last_name, $email) {
    global $conn;
    $errors = [];

    // Validate email
    if(empty($email)) {
        $errors[] = "Email is required";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if email already exists for another user
        $check_query = "SELECT * FROM users WHERE email = ? AND user_id != ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists";
        }
    }

    // Validate first name
    if(empty($first_name)) {
        $errors[] = "First name is required";
    }

    // Validate last name
    if(empty($last_name)) {
        $errors[] = "Last name is required";
    }

    // If no errors, proceed with update
    if(empty($errors)) {
        $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sssi", $first_name, $last_name, $email, $user_id);

        if(mysqli_stmt_execute($stmt)) {
            // Update session variables
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;

            return ["success" => true, "message" => "Profile updated successfully"];
        } else {
            $errors[] = "Profile update failed: " . mysqli_error($conn);
        }
    }

    return ["success" => false, "errors" => $errors];
}

// Function to change password
function change_password($user_id, $current_password, $new_password, $confirm_password) {
    global $conn;
    $errors = [];

    // Get current user data
    $query = "SELECT password FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify current password
        if(!password_verify($current_password, $user['password'])) {
            $errors[] = "Current password is incorrect";
        }

        // Validate new password
        if(empty($new_password)) {
            $errors[] = "New password is required";
        } else if(strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters";
        }

        // Validate confirm password
        if(empty($confirm_password)) {
            $errors[] = "Confirm password is required";
        } else if($new_password != $confirm_password) {
            $errors[] = "New passwords do not match";
        }

        // If no errors, proceed with password change
        if(empty($errors)) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in database
            $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);

            if(mysqli_stmt_execute($stmt)) {
                return ["success" => true, "message" => "Password changed successfully"];
            } else {
                $errors[] = "Password change failed: " . mysqli_error($conn);
            }
        }
    } else {
        $errors[] = "User not found";
    }

    return ["success" => false, "errors" => $errors];
}
?>
