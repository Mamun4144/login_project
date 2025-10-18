<?php
session_start();
if(!isset($_SESSION['UserName'])){ header("Location: login.html"); exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Entry New Bill</title>
<style>
body {font-family: Arial; background:#f0f2f5; padding:20px;}
form {background:#fff; padding:20px; border-radius:6px; max-width:500px; margin:auto;}
label {display:block; margin-top:10px;}
input, select, textarea {width:100%; padding:8px; margin-top:5px; border-radius:4px; border:1px solid #ccc;}
button {margin-top:15px; padding:10px 20px; background:#007bff; color:#fff; border:none; border-radius:4px; cursor:pointer;}
button:hover {background:#0056b3;}
</style>
</head>
<body>

<h2>Entry New Bill</h2>

<form action="submit_bill.php" method="POST">
    <label>Bill Type</label>
    <select name="bill_type" required>
        <option value="Holiday_Bill">Holiday Bill</option>
        <option value="Extra_Bill">Extra Bill</option>
        <option value="Internet_Bill">Internet Bill</option>
    </select>

    <label>Bill Details</label>
    <textarea name="bill_details" required></textarea>

    <label>From Date</label>
    <input type="date" name="from_date" required>

    <label>To Date</label>
    <input type="date" name="to_date" required>

    <label>Amount</label>
    <input type="number" name="amount" step="0.01" required>

    <button type="submit">Submit Bill</button>
</form>

</body>
</html>
