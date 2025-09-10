<?php
// Include the database connection file.
include 'db.php';

// SQL query to retrieve all accounts and their respective transactions.
// The query joins the necessary tables and orders the results by account ID and transaction date.
$sql = "
    SELECT
        a.acc_id,
        c.name,
        c.surname,
        t.transaction_date,
        t.deposit,
        t.withdraw
    FROM accounts AS a
    JOIN customers AS c ON a.cus_id = c.cus_id
    LEFT JOIN account_transaction AS at ON a.acc_id = at.acc_id
    LEFT JOIN transactions AS t ON at.transaction_id = t.transaction_id
    ORDER BY a.acc_id ASC, t.transaction_date ASC
";

$result = $conn->query($sql);

$accounts_data = [];
$current_acc_id = null;
$customer_name = "";
$balance = 0;
$order = 1;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($current_acc_id !== $row['acc_id']) {
            // New account, reset variables and store previous account's data
            if ($current_acc_id !== null) {
                $accounts_data[] = [
                    'acc_id' => $current_acc_id,
                    'name' => $customer_name,
                    'transactions' => $temp_transactions
                ];
            }
            // Start new account data
            $current_acc_id = $row['acc_id'];
            $customer_name = htmlspecialchars($row['name'] . ' ' . $row['surname']);
            $temp_transactions = [];
            $balance = 0;
            $order = 1;
        }

        $deposit = $row['deposit'] ?? 0;
        $withdraw = $row['withdraw'] ?? 0;
        $balance += ($deposit - $withdraw);

        $temp_transactions[] = [
            'order' => $order++,
            'date' => htmlspecialchars($row['transaction_date']),
            'deposit' => htmlspecialchars($deposit),
            'withdraw' => htmlspecialchars($withdraw),
            'balance' => $balance
        ];
    }
    // Add the last account's data
    if ($current_acc_id !== null) {
        $accounts_data[] = [
            'acc_id' => $current_acc_id,
            'name' => $customer_name,
            'transactions' => $temp_transactions
        ];
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Accounts Statement</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .statement-header {
            font-weight: bold;
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
        }
        th, td {
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }
        .separator {
            border-bottom: 1px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .account-header {
            font-weight: bold;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="statement-header">
            **********************************************************************************
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Deposit</th>
                    <th>Withdraw</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accounts_data)): ?>
                    <?php foreach ($accounts_data as $account): ?>
                        <tr class="account-header">
                            <td colspan="7"></td>
                        </tr>
                        <?php foreach ($account['transactions'] as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['order']; ?></td>
                            <td><?php echo htmlspecialchars($account['acc_id']); ?></td>
                            <td><?php echo htmlspecialchars($account['name']); ?></td>
                            <td><?php echo $transaction['date']; ?></td>
                            <td class="text-right"><?php echo number_format($transaction['deposit'], 2); ?></td>
                            <td class="text-right"><?php echo number_format($transaction['withdraw'], 2); ?></td>
                            <td class="text-right"><?php echo number_format($transaction['balance'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="7" class="separator"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No accounts or transactions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="statement-header">
            **********************************************************************************
        </div>
    </div>
</body>
</html>
