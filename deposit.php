<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->select_db("Long_Do_Bank")) {
        $acc_id  = $_POST['acc_id'];
        $deposit = $_POST['deposit'];
        

        $stmt = $conn->prepare("INSERT INTO accounts (deposit) VALUES (?)");
            $stmt->bind_param("ii", $deposit, $acc_id);
        if ($stmt->execute()) {
            echo "Deposit successful<br>";
            $stmt->close();
    } else {
            echo "Error depositing money: " . $stmt->error;
}
    }
    $stmt->close();


}

?>