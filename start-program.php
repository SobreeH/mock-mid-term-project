<?php
include 'db.php';

// Customer table
$createTableCustomer = "CREATE TABLE IF NOT EXISTS customers (
    cus_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL
)";

// Accounts table
$createTableAccount = "CREATE TABLE IF NOT EXISTS accounts ( 
    acc_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cus_id INT UNSIGNED NOT NULL,
    acc_type VARCHAR(20),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cus_id) REFERENCES customers(cus_id) ON DELETE CASCADE
)";

// Transactions table
$createTableTransaction = "CREATE TABLE IF NOT EXISTS transactions (
    transaction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    deposit INT,
    withdraw INT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Junction table for many-to-many (accounts and transactions)
$createTableAccountTransaction = "CREATE TABLE IF NOT EXISTS account_transaction (
    acc_id INT UNSIGNED,
    transaction_id INT UNSIGNED,
    PRIMARY KEY (acc_id, transaction_id),
    FOREIGN KEY (acc_id) REFERENCES accounts(acc_id) ON DELETE CASCADE,
    FOREIGN KEY (transaction_id) REFERENCES transactions(transaction_id) ON DELETE CASCADE
)";

// Create tables
if ($conn->query($createTableCustomer) === TRUE) {
    echo "Table customer created successfully<br>";
} else {
    echo "Error creating customer table: " . $conn->error . "<br>";
}

if ($conn->query($createTableAccount) === TRUE) {
    echo "Table accounts created successfully<br>";
} else {
    echo "Error creating accounts table: " . $conn->error . "<br>";
}

if ($conn->query($createTableTransaction) === TRUE) {
    echo "Table transactions created successfully<br>";
} else {
    echo "Error creating transactions table: " . $conn->error . "<br>";
}

if ($conn->query($createTableAccountTransaction) === TRUE) {
    echo "Table account_transaction created successfully<br>";
} else {
    echo "Error creating account_transaction table: " . $conn->error . "<br>";
}


$conn->close();

header("Location:home.html");

?>
