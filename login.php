<?php 

// 1. Database connection info
$servername = "localhost";
$username = "root";
$password = "";
$database = "admin";

// 2. Connect to MySQL
$conn =new mysqli($servername, $username, $password, $database);


// 3. Check connection

if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
      } else {
        echo"database connected Successfully";

      }


      // 4. Get data from the form
      $fullname = $_POST["FullName"];
      $username = $_POST["UserName"];
      $pass= password_hash( $_POST["Password"], PASSWORD_DEFAULT );
      $branch = $_POST["BranchName"];
      $mobil = $_POST["Mobile"];
      $designation = $_POST["Designation"];

      // 5. Insert data into table

      $sql = "INSERT INTO Login  (FullName, UserName, Password, BranchName, Mobile, Designation) VALUES ('$fullname', '$username', '$pass', '$branch', '$mobil', '$designation' )";

      if ($conn->query($sql) === TRUE){  echo"Registration 
        
        Successful";}  else { echo "Error". $conn->error; }

// 6. Close connection

$conn->close();
?>

