<?php
// Create connection
// $conn =  mysqli_connect('127.0.0.1', 'root', '', 'steycode');
$conn =  mysqli_connect('127.0.0.1', 'herrtxid_root', 'Bahubali@1', 'herrtxid_steycode');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

