<?php 
/*$conn = mysqli_connect("localhost","root","","skyfo9g2_esports_club");*/
date_default_timezone_set("Asia/Kolkata");

$servername = "localhost";
$username = "db_username";
$password = "db_password";
$db = "db_name";

/*$servername = "localhost";
$username = "battleof_faces";
$password = "battleof_faces";
$db = "battleof_faces";*/

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully";
mysqli_set_charset($conn,"utf8");
?>