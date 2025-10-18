<?php
session_start();
if(!isset($_SESSION['UserName'])){
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost","root","","admin");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
$conn->set_charset("utf8mb4");

$userName = $_SESSION['UserName'];
$bill_id = $_GET['bill_id'] ?? '';

if(!$bill_id){
    echo "Invalid Bill ID";
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $bill_type = $_POST['bill_type'];
    $bill_details = $_POST['bill_details'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $amount = floatval($_POST['amount']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE Bills 
        SET bill_type=?, bill_details=?, from_date=?, to_date=?, amount=?, status=?
        WHERE bill_id=? AND userName=?
    ");
    $stmt->bind_param("ssssdsss",$bill_type,$bill_details,$from_date,$to_date,$amount,$status,$bill_id,$userName);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Bill updated successfully'); window.location='my_bills.php';</script>";
    exit;
}

// Fetch existing bill
$stmt = $conn->prepare("SELECT bill_type, bill_details, from_date, to_date, amount, status FROM Bills WHERE bill_id=? AND userName=?");
$stmt->bind_param("ss",$bill_id,$userName);
$stmt->execute();
$result = $stmt->get_result();
$bill = $result->fetch_assoc();
$stmt->close();

if(!$bill){
    echo "Bill not found or you don't have permission";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Bill <?php echo htmlspecialchars($bill_id); ?></title>
<style>
body {font-family: Arial; background:#f0f2f5; padding:20px;}
form {background:#fff; padding:20px; border-radius:6px; max-width:500px; margin:auto;}
label {display:block; margin-top:10px;}
input, select, textarea {width:100%; padding:8px; margin-top:5px; border-radius:4px; border:1px solid #ccc;}
button {margin-top:15px; padding:10px 20px; background:#28a745; color:#fff; border:none; border-radius:4px; cursor:pointer;}
button:hover {background:#218838;}
</style>
</head>
<body>

<h2>Update Bill - <?php echo htmlspecialchars($bill_id); ?></h2>

<form method="POST">
    <label>Bill Type</label>
    <select name="bill_type" required>
        <option value="Holiday_Bill" <?php if($bill['bill_type']=='Holiday_Bill') echo 'selected'; ?>>Holiday Bill</option>
        <option value="Extra_Bill" <?php if($bill['bill_type']=='Extra_Bill') echo 'selected'; ?>>Extra Bill</option>
        <option value="Internet_Bill" <?php if($bill['bill_type']=='Internet_Bill') echo 'selected'; ?>>Internet Bill</option>
    </select>

    <label>Bill Details</label>
    <textarea name="bill_details" required><?php echo htmlspecialchars($bill['bill_details']); ?></textarea>

    <label>From Date</label>
    <input type="date" name="from_date" value="<?php echo $bill['from_date']; ?>" required>

    <label>To Date</label>
    <input type="date" name="to_date" value="<?php echo $bill['to_date']; ?>" required>

    <label>Amount</label>
    <input type="number" step="0.01" name="amount" value="<?php echo $bill['amount']; ?>" required>

    <label>Status</label>
    <select name="status">
        <option value="Submitted" <?php if($bill['status']=='Submitted') echo 'selected'; ?>>Submitted</option>
        <option value="Approved" <?php if($bill['status']=='Approved') echo 'selected'; ?>>Approved</option>
        <option value="Rejected" <?php if($bill['status']=='Rejected') echo 'selected'; ?>>Rejected</option>
    </select>

    <button type="submit">Update Bill</button>
</form>

</body>
</html>
