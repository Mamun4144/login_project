<?php
session_start(); // session start

$servername = "localhost";
$db_user = "root";
$db_pass = "";
$database = "admin";

// DB connection
$conn = new mysqli($servername, $db_user, $db_pass, $database);
if ($conn->connect_error) die("Connection failed");

// set charset
$conn->set_charset("utf8mb4");

// POST check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['UserName']) ? trim($_POST['UserName']) : '';
    $password = isset($_POST['Password']) ? $_POST['Password'] : '';

    if ($username === '' || $password === '') {
        die("User Name and Password required.");
    }

    // prepared statement
    $stmt = $conn->prepare("SELECT Password, FullName FROM Login WHERE UserName = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows === 1){
        $stmt->bind_result($hashPassword, $fullName);
        $stmt->fetch();

        if (password_verify($password, $hashPassword)) {
            // login successful
            $_SESSION['UserName'] = $username;
            $_SESSION['FullName'] = $fullName;

            header("Location: dashboard.php");
            exit;
        } else {
            echo "❌ Wrong password!";
        }
    } else {
        echo "❌ User not found!";
    }
    $stmt->close();
} else {
    echo "Invalid request!";
}

$conn->close();
?>
