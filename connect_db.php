<?php

$con1 = mysqli_connect('localhost', 'root', '');
$con = mysqli_connect("localhost", "root", "", "uiuc");
mysqli_set_charset($con, 'utf8');

mysqli_connect('localhost', 'root', '') or die(mysqli_error());
mysqli_select_db($con, 'uiuc') or die(mysqli_error());

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uiuc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
?>

