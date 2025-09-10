<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acc_id  = $_POST["acc_id"];
    $deposit = $_POST["deposit"];

    // Insert deposit into transactions table
    $depositstmt = $conn->prepare("INSERT INTO transactions (deposit) VALUES (?)");
    $depositstmt->bind_param("i", $deposit);
    $depositstmt->execute();
    $transaction_id = $conn->insert_id;
    $depositstmt->close();

    // Link account and transaction
    $linkstmt = $conn->prepare("INSERT INTO account_transaction (acc_id, transaction_id) VALUES (?, ?)");
    $linkstmt->bind_param("ii", $acc_id, $transaction_id);
    $linkstmt->execute();
    $linkstmt->close();

    echo "Deposit of $deposit successfully made to account ID $acc_id.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deposit</title>
</head>
<body>
    <h1>Deposit Money</h1>
    <form action="deposit.php" method="post">
        <?php
        // SQL query to fetch account IDs
        $sel_account = "SELECT acc_id FROM accounts";
        $result = $conn->query($sel_account);

        if ($result && $result->num_rows > 0) {
            echo "<label for='acc_id'>Select Account:</label>";
            echo "<select name='acc_id' id='acc_id'>";

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['acc_id'] . "'>" . $row['acc_id'] . "</option>";
            }

            echo "</select><br><br>";
        } else {
            echo "No accounts found.<br>";
        }
        ?>

        <label for="deposit">Amount:</label>
        <input type="number" name="deposit" required placeholder="100" min="1">
        <input type="submit" value="Deposit">
    </form>
        <button><a href="home.html">CANCEL</a></button>

</body>
</html>
