<?php
	session_start();
	
	if(isset($_COOKIE['sessionid'])){
		//create database connection
		include("connect_db.php");
		
		//get session ID
		$getsessionID = $_COOKIE['sessionid'];
		
		//get user ID
		$getuserID = $_COOKIE['userid'];
		
		//set the validity mode for session data
		$validity = "invalid";
		
		//time
		$time = time();
		
		
		//save session id, IP Address, Login Information to Database
		mysqli_query($con, "UPDATE `sessions` SET expiry_time='$time', validity='$validity' WHERE session_id='$getsessionID' AND user_id='$getuserID' ORDER BY `id` DESC LIMIT 1");
		
		 if(mysqli_num_rows(mysqli_query($con, "select * from `sessions` where	session_id='$getsessionID' AND user_id='$getuserID' AND validity='$validity'"))> 0){
			 echo "<script>window.open('login.php?status=loggedout','_self')</script>";
		 }
			 
			 if (isset($_COOKIE['sessionid'])) {
				
				unset($_COOKIE['sessionid']);
				unset($_COOKIE['username']);
				unset($_COOKIE['userid']);
				unset($_COOKIE['privilege']);
				
				setcookie('sessionid', null, -1, '/');
				setcookie('username', null, -1, '/');
				setcookie('userid', null, -1, '/');
				setcookie('privilege', null, -1, '/');
				
				return true;
			} else {
				return false;
			}
				session_destroy();
	}
			
	echo "<script>window.open('login.php','_self')</script>";

?>