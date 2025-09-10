<?php
//withdraw money
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->select_db("Long_Do_Bank")) {
        $acc_id   = $_POST['acc_id'];
        $withdraw = $_POST['withdraw'];
        $stmt = $conn->prepare("INSERT INTO accounts (withdraw,acc_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $withdraw, $acc_id);
        if ($stmt->execute()) {
            echo "Withdraw successful<br>";
            $stmt->close();
    } else {
            echo "Error withdrawing money: " . $stmt->error;
}
    }
    $stmt->close();

     $stmt = $conn->prepare("SELECT sum(deposit-withdraw) FROM accounts WHERE acc_id = ?");
    $stmt->bind_param("i", $acc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "Updated balance:<br>";
    while ($row = $result->fetch_assoc()) {
        echo $row['sum(deposit-withdraw)'] . "<br>";
    }
}
