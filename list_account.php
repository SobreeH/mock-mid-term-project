<?php
include 'db.php';
// show accounts

if ($conn->select_db("Long_Do_Bank")) {
    $sql = "SELECT * FROM accounts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Account ID: " . $row["acc_id"]. " - Deposit: " . $row["deposit"]. " - Withdraw: " . $row["withdraw"]. " - Customer ID: " . $row["cus_id"]. "<br>";
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Error selecting database: " . $conn->error;
}

?>