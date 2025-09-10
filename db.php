<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "Long_Do_Bank";

// Step 1: Connect without specifying DB
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating database: " . $conn->error . "<br>";
}
$conn->close();

// Step 3: Reconnect, this time with DB selected
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection again
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
