<?php
header("Content-Type: application/json; charset=UTF-8");
include("connect_db.php");
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	$name = $_POST["name"];
	$username = $_POST["username"];
	$institute = $_POST["institute"];
	$bio = $_POST["bio"];
	$userid = $_POST["userid"];
}

$update_sql = "UPDATE users SET name = '$name', username = '$username', institute = '$institute', bio = '$bio' WHERE id = $userid;";
$conn->query($update_sql);
echo json_encode("Ok");
?>