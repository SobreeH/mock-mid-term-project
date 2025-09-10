<?php
include("db.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $surname = $_POST["surname"];

    $cus_stmt = $conn->prepare("INSERT INTO customers (name,surname) VALUES (?,?)");
    $cus_stmt->bind_param("ss", $name,$surname);
    
    if ($cus_stmt->execute()) {
        echo"Created new customer successful";
    } else {
        echo "Error" . $stmt->error;
    }
    header("Location:new-account.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
</head>
<body>
    <h2>Create New Customer</h2>
    <form action="new-customer.php" method="POST">
        <!-- name -->
        <label for="name">Customer name: </label>
        <input type="text" name="name" placeholder="Sobree" required><br><br>

        <!-- surname -->
        <label for="surname">Customer surname: </label>
        <input type="text" name="surname" placeholder="Hajihama" required><br><br>

        <button type="submit">Create Customer</button>
    </form>
</body>
</html>

