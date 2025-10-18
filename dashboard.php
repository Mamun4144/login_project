<?php
session_start();

// check login
if (!isset($_SESSION['UserName'])) {
    header("Location: login.html");
    exit;
}

$fullName = $_SESSION['FullName'];
$currentPage = basename($_SERVER['PHP_SELF']); // active page highlight
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    min-height: 100vh;
    background: #f0f2f5;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #007bff;
    color: white;
    display: flex;
    flex-direction: column;
    padding: 20px;
    transition: width 0.3s;
    position: relative;
}

.sidebar h2 {
    margin-bottom: 30px;
    text-align: center;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.sidebar a {
    color: white;
    text-decoration: none;
    padding: 12px;
    margin: 5px 0;
    border-radius: 6px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar a:hover {
    background-color: #0056b3;
}

.sidebar a.active {
    background-color: #004080;
    font-weight: bold;
}

.menu-item {
    display: flex;
    flex-direction: column;
}

.menu-item > a i {
    min-width: 20px;
}

.submenu {
    display: none;
    flex-direction: column;
    margin-left: 20px;
}

.menu-item.active .submenu {
    display: flex;
}

.menu-item:hover .submenu {
    display: flex;
}

/* Main content */
.main-content {
    flex: 1;
    padding: 40px;
}

.welcome {
    font-size: 24px;
    margin-bottom: 20px;
}

.logout {
    margin-top: auto;
    text-align: center;
}

.logout a {
    background: #ff4d4d;
    padding: 10px 20px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    display: inline-block;
}

.logout a:hover {
    background: #cc0000;
}

/* Responsive */
@media(max-width:768px){
    .sidebar { width: 200px; }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa fa-cubes"></i> Dashboard</h2>

    <div class="menu-item <?php echo ($currentPage=='new_bill.php' || $currentPage=='my_bills.php') ? 'active' : ''; ?>">
        <a href="#"><i class="fa fa-file-invoice"></i> Bills <i class="fa fa-caret-down" style="margin-left:auto;"></i></a>
        <div class="submenu">
            <a href="new_bill.php" class="<?php echo ($currentPage=='new_bill.php')?'active':''; ?>"><i class="fa fa-plus-circle"></i> Entry New Bill</a>
            <a href="my_bills.php" class="<?php echo ($currentPage=='my_bills.php')?'active':''; ?>"><i class="fa fa-eye"></i> Check My Submitted Bills</a>
        </div>
    </div>

    <div class="logout">
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="welcome"><i class="fa fa-user"></i> Welcome, <?php echo htmlspecialchars($fullName); ?>!</div>
    <p>Select an option from the sidebar to continue.</p>
</div>

</body>
</html>
