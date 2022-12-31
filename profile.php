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
	<title>My Profile</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
	<div style="float:right">
		<li><a class="active" href="profile.php">Profile</a></li>
		<li><a href="logout.php">Logout</a></li>
	</div>
</ul>
<div id='body'>
</br>
	<?php
		$userid = $_COOKIE['userid'];
		if ($conn->query("SELECT name FROM users WHERE id='$userid'")->num_rows > 0) {
			// output data of each row
			if($row = $conn->query("SELECT name, institute, image FROM users WHERE id='$userid'")->fetch_assoc()) {
				$institute_id = $row['institute'];
				?>
				<div style="margin-left:47px;margin-right: 30%;">
					<div class="typewriter" style="max-width: 100%;">
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
	
	<div class='mainCont' style="display: flex; align-items: center; flex-direction: column; margin-bottom: 50px;">
		<h1>My Profile</h1>
		<img src="http://localhost/fb_group/uploads/<?php echo $row['image']; ?>" width="400px" style="margin-bottom: 20px; border-radius: 20px;">
		<table border="1" style="width: 70%; padding: 20px;">
			<?php
				mysqli_set_charset($con,"utf8");
				$profile_sql = "SELECT name, username, institute, bio FROM `users` where id='$userid'";
				$institute = $conn->query("SELECT name FROM `institutes` where id='$institute_id'")->fetch_assoc();
				$profile_data = $conn->query($profile_sql);

				if ($profile_data->num_rows > 0) {
				  // output data of each row
				  while($row = $profile_data->fetch_assoc()) {
				  	?>
				    <tr class="hoverROw">
						<td style="padding: 20px; font-size: 20px;">Name</td>
						<td style="padding: 20px; font-size: 20px;"><?php echo $row['name']?></td>
					</tr>
					<tr class="hoverROw">
						<td style="padding: 20px; font-size: 20px;">Username</td>
						<td style="padding: 20px; font-size: 20px;"><?php echo $row['username']?></td>
					</tr>
					<tr class="hoverROw">
						<td style="padding: 20px; font-size: 20px;">Institute</td>
						<td style="padding: 20px; font-size: 20px;"><?php echo $institute['name']?></td>
					</tr>
					<tr class="hoverROw">
						<td style="padding: 20px; font-size: 20px;">Bio</td>
						<td style="padding: 20px; font-size: 20px;"><?php echo $row['bio']?></td>
					</tr>
				    <?php
				  }
				} else {
				  echo "No data";
				}
			?>
		</table>

		<!-- Trigger/Open The Modal -->
		<button id="myBtn">Edit Profile</button>
	</div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
  	<form id="editUser">
	    <span class="close">&times;</span>
	    <label for="profileName">Name:</label>
	    <input type="text" name="name" id="profileName" value=""><br><br>
	    <label for="profileUsrName">Username:</label>
	    <input type="text" name="username" id="profileUsrName" value=""><br><br>
	    <label for="profileInstitute">Institute:</label>
	    <select name="institute" id="profileInstitute">
	    	<?php
	    		$ins_sql = "SELECT id, name FROM institutes";
				$ins_result = $conn->query($ins_sql);

				if ($ins_result->num_rows > 0) {
				  // output data of each row
				  while($insrow = $ins_result->fetch_assoc()) {
				    echo '<option value="'.$insrow["id"].'">'.$insrow["name"].'</option>';
				  }
				}
	    	?>
		</select><br><br>
	    <label for="profileUsrBio">Bio:</label>
	    <textarea name="bio" id="profileUsrBio" value=""></textarea><br>
	</form>

	<button id="myBtn" onClick="updateUser()">Update</button><br>
	<div id="updatesuccess" style="color: green;"></div>
  </div>

</div>

<script>
	function updateUser () {
		var uName = document.getElementById("profileName").value;
		var uUserName = document.getElementById("profileUsrName").value;
		var uInstitute = document.getElementById("profileInstitute").value;
		var uBio = document.getElementById("profileUsrBio").value;

		var formData = {
            'name': uName,
            'username': uUserName,
            'institute': uInstitute,
            'bio': uBio,
            'userid' : <?php echo $_COOKIE['userid'] ?>,
        };
        
        jQuery.ajax({
            type: 'POST',
            url: 'http://localhost/fb_group/update.php',
            data: formData,
            dataType: 'json',
            success: function(data) {
                $("#updatesuccess").html("Successfully updated!");
                location.reload();
            }
        });
	}
	// Get the modal
	var modal = document.getElementById("myModal");

	// Get the button that opens the modal
	var btn = document.getElementById("myBtn");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks the button, open the modal 
	btn.onclick = function() {
	  modal.style.display = "block";
	}

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
	  modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	  if (event.target == modal) {
	    modal.style.display = "none";
	  }
	}
</script>

</body>
</html>


<?php 	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>