<?php
// public/includes/db.php
// ------------------------------------------
// Database connection for FrostGear website
// ------------------------------------------

$host = 'localhost';
$username = 'root';
$password = '';          // change this ONLY if your MySQL has a password
$dbname = 'frostgear_db'; // your new database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// If connection succeeds, $conn can be used in all pages that include this file
?>
