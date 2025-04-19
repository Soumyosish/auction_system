<?php
$host = 'localhost';
$dbname = 'auction_system';
$username = 'root';
$password = '';

$db = new mysqli($host, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>