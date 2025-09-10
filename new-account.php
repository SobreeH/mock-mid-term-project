<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->select_db("Long_Do_Bank")) {
        $name    = $_POST['name'];
        $surname = $_POST['surname'];
        $acc_id  = $_POST['acc_id'];
        $deposit = $_POST['deposit'];

        // Insert customer
        $stmt1 = $conn->prepare("INSERT INTO customer (name, surname) VALUES (?, ?)");
        $stmt1->bind_param("ss", $name, $surname);

        if ($stmt1->execute()) {
            echo "Customer record created successfully<br>";

            // Get last inserted customer ID
            $cus_id = $conn->insert_id;
            $stmt1->close();

            // Insert account linked to this customer
            $stmt2 = $conn->prepare("INSERT INTO accounts (acc_id, deposit, cus_id) VALUES (?, ?, ?)");
            $stmt2->bind_param("iii", $acc_id, $deposit, $cus_id);

            if ($stmt2->execute()) {
                echo "Account record created successfully<br>";
                $stmt2->close();

                // Redirect to homepage
                header("Location: home.html");
                exit();
            } else {
                echo "Error inserting account: " . $stmt2->error;
            }
        } else {
            echo "Error inserting customer: " . $stmt1->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add account</title>
</head>
<body>
    <h2>Create New Account</h2>
    <form action="new-account.php" method="POST">
        <!-- name -->
        <label for="name">Customer name: </label>
        <input type="text" name="name" placeholder="Sobree" required><br><br>

        <!-- surname -->
        <label for="surname">Customer surname: </label>
        <input type="text" name="surname" placeholder="Hajihama" required><br><br>

        <!-- account id -->
        <label for="acc_id">Customer account: </label>
        <input type="number" name="acc_id" placeholder="1234567890" required><br><br>

        <!-- deposit -->
        <label for="deposit">Initial Deposit: </label>
        <input type="number" name="deposit" placeholder="500" required><br><br>

        <button type="submit">Create Account</button>
    </form>
</body>
</html>
