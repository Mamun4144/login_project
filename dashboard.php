<?php
session_start();

// check login
if (!isset($_SESSION['UserName'])) {
    header("Location: login.html");
    exit;
}

$fullName = $_SESSION['FullName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f2f5; display:flex; justify-content:center; align-items:center; height:100vh; }
    .card { background:#fff; padding:40px 50px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1); text-align:center; }
    h1 { margin-bottom:20px; }
    a { display:inline-block; margin-top:20px; padding:10px 20px; background:#007bff; color:#fff; border-radius:6px; text-decoration:none; }
    a:hover { background:#0056b3; }
</style>
</head>
<body>
    <div class="card">
        <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
        <p>You are logged in successfully.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
