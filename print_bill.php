<?php
session_start();
if(!isset($_SESSION['UserName'])){ header("Location: login.html"); exit; }

if(!isset($_POST['bill_id'])){ die("Bill ID missing"); }

$bill_id = intval($_POST['bill_id']);
$userName = $_SESSION['UserName'];

$servername = "localhost"; $db_user="root"; $db_pass=""; $database="admin";
$conn = new mysqli($servername,$db_user,$db_pass,$database);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
$conn->set_charset("utf8mb4");

$stmt = $conn->prepare("SELECT * FROM Bills WHERE id=? AND userName=?");
$stmt->bind_param("is",$bill_id,$userName);
$stmt->execute();
$result = $stmt->get_result();
$bill = $result->fetch_assoc();
if(!$bill){ die("Bill not found"); }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Print Bill</title>
<style>
body { font-family: Arial,sans-serif; padding:40px; }
h2 { text-align:center; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
table th, table td { padding:12px; border:1px solid #333; text-align:left; }
button { padding:10px 20px; margin-top:20px; cursor:pointer; }
</style>
</head>
<body>
<h2>Bill Details</h2>
<table>
<tr><th>Bill Type</th><td><?= htmlspecialchars($bill['bill_type']) ?></td></tr>
<tr><th>Details</th><td><?= htmlspecialchars($bill['bill_details']) ?></td></tr>
<tr><th>From Date</th><td><?= htmlspecialchars($bill['from_date']) ?></td></tr>
<tr><th>To Date</th><td><?= htmlspecialchars($bill['to_date']) ?></td></tr>
<tr><th>Amount</th><td><?= number_format($bill['amount'],2) ?></td></tr>
<tr><th>Status</th><td><?= htmlspecialchars($bill['status']) ?></td></tr>
<tr><th>Submitted At</th><td><?= htmlspecialchars($bill['created_at']) ?></td></tr>
</table>

<button onclick="window.print()">Print</button>

</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
