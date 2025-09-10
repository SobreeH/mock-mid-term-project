<?php
include 'db.php';

// sql to create table customer
$createTableCustomer = "CREATE TABLE IF NOT EXISTS customer (
    cus_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL
)";

// sql to create table accounts
$createTableAccount = "CREATE TABLE IF NOT EXISTS accounts (
    acc_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    deposit INT,
    withdraw INT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cus_id INT UNSIGNED,
    FOREIGN KEY (cus_id) REFERENCES customer(cus_id) ON DELETE CASCADE
)";

// create table customer
if ($conn->query($createTableCustomer) === TRUE) {
  echo "Table customer created successfully<br>";
} else {
  echo "Error creating customer table: " . $conn->error . "<br>";
}

// create table accounts
if ($conn->query($createTableAccount) === TRUE) {
  echo "Table accounts created successfully<br>";
} else {
  echo "Error creating accounts table: " . $conn->error . "<br>";
}

$conn->close();
?>