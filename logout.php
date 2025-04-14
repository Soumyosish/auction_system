<?php
require_once 'php/config.php';
require_once 'php/auth_functions.php';

// Logout the user
logout_user();

// Redirect to homepage
redirect('index.php');
?>
