<?php
session_start();
if(!isset($_SESSION['UserName'])){ header("Location: login.html"); exit; }

$conn = new mysqli("localhost","root","","admin");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
$conn->set_charset("utf8mb4");

$userName = $_SESSION['UserName'];

$billId   = $_GET['bill_id']   ?? '';
$billType = $_GET['bill_type'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate   = $_GET['to_date']   ?? '';

$query = "SELECT bill_id, bill_type, bill_details, from_date, to_date, amount, status 
          FROM Bills 
          WHERE userName = ?";
$params = [$userName];
$types  = "s";

if($billId !== ''){
    $query .= " AND bill_id = ?";
    $params[] = $billId;
    $types .= "s";
}

if($billType !== ''){
    $query .= " AND bill_type = ?";
    $params[] = $billType;
    $types .= "s";
}

if($fromDate !== '' && $toDate !== ''){
    $query .= " AND from_date >= ? AND to_date <= ?";
    $params[] = $fromDate;
    $params[] = $toDate;
    $types .= "ss";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bills</title>
<style>
body {font-family: Arial, sans-serif;}
table {border-collapse:collapse;width:100%;}
th,td {border:1px solid #ccc;padding:6px;text-align:center;}
th {background:#007bff;color:#fff;}
.filter-box {margin-bottom:15px;padding:10px;background:#f9f9f9;border:1px solid #ccc;border-radius:5px;}
.filter-box h4 {margin:0 0 8px 0;color:#333;}
.filter-group {display:inline-block;margin-right:10px;}
.filter-group label {display:block;font-size:13px;color:#333;margin-bottom:3px;}
.filter-group input,.filter-group select {padding:4px;min-width:140px;}
button {padding:5px 10px;cursor:pointer;border:none;border-radius:4px;}
.filter-btn {background:#007bff;color:#fff;}
.reset-btn {background:#6c757d;color:#fff;}
.update {background:#28a745;color:#fff;border:none;padding:4px 8px;cursor:pointer;}
.print {background:#17a2b8;color:#fff;border:none;padding:4px 8px;cursor:pointer;}
</style>
</head>
<body>

<h3>My Submitted Bills</h3>

<!-- 🧭 Filter Section -->
<div class="filter-box">
    <h4>🔍 Filter Bills</h4>
    <form method="GET">
        <div class="filter-group">
            <label for="bill_id">Bill ID</label>
            <input type="text" name="bill_id" id="bill_id" placeholder="Enter Bill ID" value="<?php echo htmlspecialchars($billId); ?>">
        </div>

        <div class="filter-group">
            <label for="bill_type">Bill Type</label>
            <select name="bill_type" id="bill_type">
                <option value="">Select Type</option>
                <option value="Internet_Bill" <?php if($billType=='Internet_Bill') echo 'selected'; ?>>Internet_Bill</option>
                <option value="Conveyance_Bill" <?php if($billType=='Conveyance_Bill') echo 'selected'; ?>>Conveyance_Bill</option>
                <option value="Fixed_Bill" <?php if($billType=='Fixed_Bill') echo 'selected'; ?>>Fixed_Bill</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="from_date">From Date</label>
            <input type="date" name="from_date" id="from_date" value="<?php echo htmlspecialchars($fromDate); ?>">
        </div>

        <div class="filter-group">
            <label for="to_date">To Date</label>
            <input type="date" name="to_date" id="to_date" value="<?php echo htmlspecialchars($toDate); ?>">
        </div>

        <button type="submit" class="filter-btn">Filter</button>
        <button type="button" class="reset-btn" onclick="window.location='my_bills.php'">Reset</button>
    </form>
</div>

<table>
<tr>
<th>Bill ID</th>
<th>Type</th>
<th>Details</th>
<th>From</th>
<th>To</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php if($res->num_rows > 0): ?>
<?php while($row = $res->fetch_assoc()): ?>
<tr>
<td><?php echo $row['bill_id']; ?></td>
<td><?php echo $row['bill_type']; ?></td>
<td><?php echo $row['bill_details']; ?></td>
<td><?php echo $row['from_date']; ?></td>
<td><?php echo $row['to_date']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['status']; ?></td>
<td>
    <button class="update" onclick="window.location='update_bill.php?bill_id=<?php echo $row['bill_id']; ?>'">Update</button>
    <button class="print" onclick="printRow(this)">Print</button>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="8">No bills found.</td></tr>
<?php endif; ?>
</table>

<script>
function printRow(btn){
    const row = btn.closest('tr').outerHTML;
    const table = `
    <table border="1" style="border-collapse:collapse;width:100%;text-align:center">
        <tr><th>Bill ID</th><th>Type</th><th>Details</th><th>From</th><th>To</th><th>Amount</th><th>Status</th></tr>
        ${row}
    </table>`;
    const w = window.open('', '_blank');
    w.document.write(table);
    w.document.close();
    w.print();
}
</script>

</body>
</html>
