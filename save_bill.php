



<?php
session_start();
if(!isset($_SESSION['UserName'])){
    header("Location: login.html");
    exit;
}

$servername = "localhost";
$db_user = "root";
$db_pass = "";
$database = "admin";

$conn = new mysqli($servername, $db_user, $db_pass, $database);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $userName = $_SESSION['UserName'];
    $bill_type = $_POST['bill_type'];
    $bill_details = $_POST['bill_details'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO Bills (userName, bill_type, bill_details, from_date, to_date, amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $userName, $bill_type, $bill_details, $from_date, $to_date, $amount);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect to My Submitted Bills
    header("Location: my_bills.php");
    exit;
}
?>
