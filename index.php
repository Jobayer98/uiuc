<?php
	session_start();
	
	//create database connection
	include("connect_db.php");
	
	//blank var
	$getsessionID = '';
	
	//call session data
	if(isset($_COOKIE['sessionid'])){
		//get session id from browser and update variable
		$getsessionID = $_COOKIE['sessionid'];
	}
	//set the validity mode for session data
	$validity = "valid";	
	//verify session id
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/client.css"/>
	<title>Your Groups</title>
</head>
<body>
<ul>
	<li style='background: linear-gradient(to left,#2e76ff, #1abfff);'>
		<a href="javascript:void(0);">
		<?php
			$userid = $_COOKIE['userid'];
			if ($conn->query("SELECT username FROM users WHERE id='$userid'")->num_rows > 0) {
				// output data of each row
				if($row = $conn->query("SELECT username FROM users WHERE id='$userid'")->fetch_assoc()) {
					echo "<span>Hello! <strong>".$row['username']."</strong></span><br>";
				}
			} else {
				echo "<b>Something Went Wrong!</b>";
			}
		?>
		</a>
	</li>
	<li><a class="activeMenu" href="#">Your Groups</a></li>
	<li><a href="discover.php">Discover</a></li>
	<li class="dropdown">
		<a href="javascript:void(0)" class="dropbtn">Manager</a>
			<div class="dropdown-content">
			  <a href="myuploads.php">My Uploads</a>
			  <a href="#">My Account</a>
			  <a href="#">Settings</a>
			  <a href="#">Subscription</a>
			</div>
	</li>
	<li style="float:right"><a class="active" href="logout.php">Logout</a></li>
</ul>
<div id='body'>
</br>
		<?php
		$userid = $_COOKIE['userid'];
		if ($conn->query("SELECT name FROM users WHERE id='$userid'")->num_rows > 0) {
			// output data of each row
			if($row = $conn->query("SELECT name FROM users WHERE id='$userid'")->fetch_assoc()) {
				?>
				<div style="margin-left:47px;margin-right: 30%;">
					<div class="typewriter" style="max-width: <?php echo (strlen($row['name'])*32)?>px;">
					  <h1>Welcome, <?php echo $row['name']; ?></h1>
					</div>
				</div>
				
				<?php
			}
		} else {
			echo "<b style='color:red;'>Authentication Error!</b>";
		}
	?>
	
	<hr style="color:white;">
	
	
	
<div class='mainCont'>
<h1>Your Groups</h1>
<table border="1" style="width: 70%; padding: 20px;">
<?php
	mysqli_set_charset($con,"utf8");
	$id    		= mysqli_real_escape_string($con, $_COOKIE['userid']);
	$sql        = "SELECT * FROM `group_members` WHERE `userid`=$id";
	$result		= mysqli_query($con, $sql);
	if(!$result){
		echo mysqli_error($con);
	}
	else{
		while($rows=mysqli_fetch_array($result)){

			$ambid = $rows['groupID'];
			if($row=mysqli_fetch_array(mysqli_query($con, "SELECT id,name,type FROM `groups` Where id=$ambid"), MYSQLI_ASSOC)){
	?>		
				<tr class="hoverROw">
					<td style="padding: 20px; font-size: 20px;"><a style="color: white;" href="group.php?id=<?php echo $row['id']?>"><?php echo $row['name']?></a></td>
					<td style="text-transform: capitalize; padding: 20px; font-size: 20px;"><?php echo $row['type']?> Group</td>
				</tr>
	<?php
			}
		}
	}

?>
</table>
	
</div>
</div>
</body>
<script type="text/javascript">
	function myfun() {
            document.getElementById("body").innerHTML = "The group is public";

        }
</script>
</html>


<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>