

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit</title>
</head>
<body>
    <h1>Deposit money</h1>
    <form action="deposit.php" method="post">
    <label for="acc_id" >Account id</label>
    <input type="text" name="acc_id" required placeholder="1234567890">
    <label for="deposit" >Amount</label>
    <input type="number" name="deposit"  required placeholder="100">
    <input type="submit" value="Deposit">
</form>
</body>
</html>