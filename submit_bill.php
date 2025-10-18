<?php
session_start();
if(!isset($_SESSION['UserName'])){ header("Location: login.html"); exit; }

$servername = "localhost";
$db_user = "root";
$db_pass = "";
$database = "admin";

$conn = new mysqli($servername,$db_user,$db_pass,$database);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
$conn->set_charset("utf8mb4");

// Get form data
$userName    = $_SESSION['UserName'];
$bill_type   = $_POST['bill_type'];
$bill_details= $_POST['bill_details'];
$from_date   = $_POST['from_date'];
$to_date     = $_POST['to_date'];
$amount      = floatval($_POST['amount']);

// Generate sequential Bill ID
$result = $conn->query("SELECT bill_id FROM Bills ORDER BY CAST(bill_id AS UNSIGNED) DESC LIMIT 1");
if($row = $result->fetch_assoc()){
    $last_id = intval($row['bill_id']);
    $bill_id = str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);
}else{
    $bill_id = '0001';
}

// Insert into Bills table
$stmt = $conn->prepare("
    INSERT INTO Bills (bill_id, userName, bill_type, bill_details, from_date, to_date, amount) 
    VALUES (?,?,?,?,?,?,?)
");
$stmt->bind_param("ssssssd",$bill_id,$userName,$bill_type,$bill_details,$from_date,$to_date,$amount);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirect with success
echo "<script>alert('Bill submitted successfully! Bill ID: $bill_id'); window.location='dashboard.php';</script>";
?>
