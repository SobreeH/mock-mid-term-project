<?php
include("db.php");
// Hides account form
$showAccountForm = false;

// initialize customer id
$customer_id = null;

// selects the customer-id, search via name
if (isset($_POST["check"])) {
    $sel_cus = $_POST["customer"];
    $stmtid = $conn->prepare("SELECT cus_id FROM customers WHERE name = ?");
    $stmtid->bind_param("s", $sel_cus);
    $stmtid->execute();
    $stmtid->bind_result($customer_id);
    // then show form
    if ($stmtid->fetch()) {
        $showAccountForm = true;
    }
    $stmtid->close();
}
// insert into account
if (isset($_POST["create_account"])) {
    if (!empty($_POST["customerid"])) {
        $customerid = $_POST["customerid"];
        $accounttype = $_POST["account_type"];
        $initial_amount = $_POST["deposit"];

        $accstmt = $conn->prepare("INSERT INTO accounts (cus_id, acc_type) VALUES (?, ?)");
        $accstmt->bind_param("is", $customerid, $accounttype);
        $accstmt->execute();
        echo "<p>Account created successfully!</p>";

        // Get the newly inserted account ID
        $account_id = $conn->insert_id;

        // Insert deposit transaction
        $depositstmt = $conn->prepare("INSERT INTO transactions (deposit) VALUES (?)");
        $depositstmt->bind_param("i", $initial_amount); 
        $depositstmt->execute();
        $transaction_id = $conn->insert_id;
        $depositstmt->close();

        // Link account and transaction
        $linkstmt = $conn->prepare("INSERT INTO account_transaction (acc_id, transaction_id) VALUES (?, ?)");
        $linkstmt->bind_param("ii", $account_id, $transaction_id);
        $linkstmt->execute();
        $linkstmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Account</title>
</head>
<body>
    <h2>Create New Account</h2>
    <form action="new-account.php" method="post">
        <?php
        $sqlcus = "SELECT name FROM customers";
        $cus_result = $conn->query($sqlcus);
        if ($cus_result && $cus_result->num_rows > 0) {
            echo "<label for='customer'>Current customer: </label>";
            echo "<select name='customer' id='customer'>";
            while ($row = $cus_result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row["name"]) . "'>" . htmlspecialchars($row["name"]) . "</option>";
            }
            echo "</select><br><br>";
        } else {
            echo "No customer found.<br>";
        }
        ?>
        <input type="submit" name="check" value="Check Customer">
    </form>
        <!-- special hidden form for existing customers only -->
    <?php if ($showAccountForm): ?>
        <form action="new-account.php" method="post">
            <input type="hidden" name="customerid" value="<?php echo $customer_id; ?>">
            <label for="account_type">Choose an account type:</label>
            <select id="account_type" name="account_type">
                <option value="simple">Simple Savings Account</option>
                <option value="compound">Compound Savings Account</option>
            </select>
            <label for="deposit"> Initial Deposit amount: </label>
            <input type="number" name="deposit">
            <input type="submit" name="create_account" value="Submit">
        </form>
    <?php endif; ?>

    <br>
    <button><a href="new-customer.php">New Customer</a></button>
    <br><br>
    <button><a href="home.html">CANCEL</a></button>
</body>
</html>
