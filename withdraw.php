<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acc_id  = $_POST["acc_id"];
    $withdraw = $_POST["withdraw"];

    // Insert withdraw into transactions table
    $withdrawstmt = $conn->prepare("INSERT INTO transactions (withdraw) VALUES (?)");
    $withdrawstmt->bind_param("i", $withdraw);
    $withdrawstmt->execute();
    $transaction_id = $conn->insert_id;
    $withdrawstmt->close();

    // Link account and transaction
    $linkstmt = $conn->prepare("INSERT INTO account_transaction (acc_id, transaction_id) VALUES (?, ?)");
    $linkstmt->bind_param("ii", $acc_id, $transaction_id);
    $linkstmt->execute();
    $linkstmt->close();

    echo "Withdraw of $withdraw successfully made to account ID $acc_id.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw</title>
</head>
<body>
    <h1>Withdraw Money</h1>
    <form action="withdraw.php" method="post">
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

        <label for="withdraw">Amount:</label>
        <input type="number" name="withdraw" required placeholder="100" min="1">
        <input type="submit" value="Withdraw">
    </form>
        <button><a href="home.html">CANCEL</a></button>

</body>
</html>
