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
	<link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
	<link rel="stylesheet" href="css/fontawesome-free-6.2.0-web/css/all.min.css" />
	<title>Your Groups</title>
	<script src="js/aurna-lightbox.js"></script>
	<script src="js/discussion.js"></script>
	<script src="js/managecontent.js"></script>
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
		<li><a class="active" href="logout.php">Logout</a></li>
	</div>
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

<h1>Group posts timeline</h1>
<?php
	mysqli_set_charset($con,"utf8");
	$groupsql        = "SELECT id FROM `groups`";
	$groupresult		= mysqli_query($con, $groupsql);
	while($grouprow=mysqli_fetch_array($groupresult)){

		mysqli_set_charset($con,"utf8");
		$groupid    		= $grouprow['id'];
		$sql        = "SELECT * FROM `group_posts` WHERE `groupID`=$groupid ORDER BY id DESC LIMIT 5";
		$result		= mysqli_query($con, $sql);
		if(!$result){
			echo mysqli_error($con);
		}
		else{
			while($rows=mysqli_fetch_array($result)){
				?>
				<div style="padding: 19px;background: #282e33; margin-bottom: 9px;" class="postContainer" id="PostCont<?php echo $rows['id'];?>">
				<?php 
					// Print User Image And Details
				?>
				<a style="text-decoration: none;" href="profile.php?id=<?php echo $rows['userID'];?>">
				<?php
					$OwnerID = $rows['userID'];
					if($row = $conn->query("SELECT name, institute, image FROM users WHERE id='$OwnerID'")->fetch_assoc()) {
						?>
						<img style="height: 37px; border-radius: 50px; position: absolute;" src="uploads/<?php echo $row['image'];?>"/>
						<span style="font-size:18px; font-weight: bold; margin-bottom: 4px; margin-left: 42px;"><?php echo $row['name']; ?></span> updated a status</br>
						
						<?php
						$OwnerInstitute = $row['institute'];
						if($row1 = $conn->query("SELECT name FROM institutes WHERE id='$OwnerInstitute'")->fetch_assoc()) {
						?>
						
						<small style="margin-left: 42px;"> <?php echo $row1['name']; ?></small>
						
						<?php
						}
					}	
				?>
				</a>
				
				<?php
				//For Post Owner
				if($OwnerID == $_COOKIE['userid']){?>
				<small id="mithun" style="float: right;">
					<i onclick="deletePost(<?php echo $rows['id'];?>)"  data-title="Delete This Post" class="fa-solid fa-trash"></i>
					&nbsp;
					<i onclick="editPost(<?php echo $rows['id'];?>)" data-title="Edit This Post" class="fa-solid fa-pen-to-square"></i>
				</small>
				<?php } ?>
				
				<div style="height:5px;"></div>
				
				<div id="postcontent<?php echo $rows['id'];?>">
				<?php echo base64_decode($rows['content']); ?>
				</div>
				
				<div style="height:5px;"></div>
				
				<div style="padding: 11px;background: #363e44;border-radius: 8px;margin-top: 7px;"> 
					<button onclick="likePost(<?php echo $rows['id'];?>)" class="button-10" id="likecont<?php echo $rows['id'];?>">
						<?php
						$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `likes` WHERE `postID`='".$rows['id']."' AND `userID`='".$_COOKIE['userid']."'"));
						if($row >= 1){
							echo '<i class="fa-solid fa-thumbs-down"></i> Unlike';
						} else if($row == 0){
							echo '<i class="fa-solid fa-thumbs-up"></i> Like';
						}
						?>
					</button>
					<button class="button-10" onclick="commentArea<?php echo $rows['id'];?>.style.display = 'inherit'; loadComments('<?php echo $rows['id'];?>');">
						<i class="fa-solid fa-comment"></i> Comment
					</button>
					<button id="copyBtn<?php echo $rows['id'];?>" class="button-10" onclick="CopyPostLink('<?php echo $rows['id'];?>', location.href)">
						<i class="fa-solid fa-copy"></i> Copy Link
					</button>&nbsp;
					<span id="likeCounter<?php echo $rows['id'];?>">
					<?php 
						$PostLikeCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `likes` WHERE `postID`='".$rows['id']."'"));
						if($PostLikeCount  == 0){
							echo 'Be the first one to Like';
						} else {
							echo $PostLikeCount.' Likes';
						}
					?></span>
					&nbsp;.&nbsp;
					<span id="commentCounter<?php echo $rows['id'];?>">
					<?php 
						$PostCommentCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `comments` WHERE `postID`='".$rows['id']."'"));
						if($PostCommentCount  == 0){
							echo 'No Comments';
						} else {
							echo $PostCommentCount.' Comments';
						}
					?></span>
				</div>
				
				<div style="height:5px;"></div>

				<div id="commentArea<?php echo $rows['id'];?>" style="display:none">
					<span style="font-size:20px; font-weight: bold; margin-bottom: 4px;">Comments</span>
					<hr>

					<?php					
						//Print Post Comments
						$Commentresult		= mysqli_query($con, "SELECT * FROM `comments` WHERE `postID`=".$rows['id']." ORDER BY id DESC");
						if(!$Commentresult){
							echo mysqli_error($con);
						} else{
							if(mysqli_num_rows(mysqli_query($con, "SELECT * FROM `comments` WHERE `postID`=".$rows['id'])) <= 0){
								echo "Be the first one to comment.";
							};
							?>
						<input onkeypress="return DoCommentListener(event, <?php echo $rows['id'];?>, this.value)" placeholder="Write a Comment" style="width: 98%; height: 40px; background: rgb(71, 81, 89); color: white; border-radius: 5px; border: medium none; margin-bottom: 9px; padding: 8px; font-family: sans-serif;overflow: auto;" spellcheck="false" id="commentBox<?php echo $rows['id'];?>"/>
							
							
						<div id="commentContainer<?php echo $rows['id'];?>">
							
							
					<?php
						while($rows=mysqli_fetch_array($Commentresult)){

					?>
					
					<div class="commentsConta">
					<?php 
						// Print Details
					?>
					<a style="text-decoration: none;" href="profile.php?id=<?php echo $rows['userID'];?>">
					<?php
						$CommentOwnerID = $rows['userID'];
						if($row = $conn->query("SELECT name, institute, image FROM users WHERE id='$CommentOwnerID'")->fetch_assoc()) {
							?>
							<span style="font-size:14px; font-weight: bold; margin-bottom: 3px; position: absolute;"><?php echo $row['name']; ?></span></br>
							<?php
							$CommentOwnerInstitute = $row['institute'];
							if($row1 = $conn->query("SELECT name FROM institutes WHERE id='$CommentOwnerInstitute'")->fetch_assoc()) {
							?>
							<small style="font-size: 11px; border-bottom: 1px dotted white; position: absolute;"> <?php echo $row1['name']; ?></small>
							<?php
							}
						}	
					?>
					</a>
					</br>
					<?php echo $rows['comment']; ?> </br>
					</div>
					
					<?php
						}
					}
					?>
					</div>
				</div>
			</div>
				
		<?php }
		}
	}
	?>

	
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