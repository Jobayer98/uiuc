<div class="pageHeader">
	<div class="pageHeaderCont">
	<h1><?php echo $rows['name'];?></h1>
	<?php if($rows['type'] == 'private'){echo'Private';}else{echo'Public';}?> Group . 
	<a href="members.php?id=<?php echo $id; ?>">
	<?php 
		$MembersRowCounter = mysqli_num_rows( mysqli_query($con, "SELECT * FROM `group_members` WHERE `groupID`=$id"));
		if($MembersRowCounter){
			echo $MembersRowCounter." Members";
		}else{
			 echo "No Members!";
		}
	?>
	</a>
	</div>
</div>