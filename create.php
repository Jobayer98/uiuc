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
		
		
		

	
	//get requests for data
	if(isset($_GET['data'])){
		
		
			//search for institute and institute data and display
			if($_GET['data'] == 'post'){
				
				mysqli_set_charset($con,"utf8");
				$groupID    	= mysqli_real_escape_string($con, $_GET['group_id']);
				$userID 	= $_COOKIE['userid'];
				?>
				<!DOCTYPE html>
				<html>
				<head>
					<style>
					.bodyMain {
					  background-color: rgb(36, 37, 38);
					  font-family: Trebuchet MS;
					  color: white;
					  margin-right: 40px;
					  margin-left: 40px;
					  margin-top: 37px;
					}
					</style>
					<link rel="stylesheet" type="text/css" href="css/client.css"/>
					<link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
					<link rel="stylesheet" href="css/fontawesome-free-6.2.0-web/css/all.min.css" />
					<script src="js/tinymce_6.2.0/tinymce/js/tinymce/tinymce.min.js" ></script>
					<script src="js/createpost.js"></script>
					<script>
					tinymce.init({
							selector 	: '#editor',
							plugins 	: 'image',
							toolbar		: 'image',
							width		: '100%',

							images_upload_url : 'ImageUploader.php',
							automatic_uploads : false,

							images_upload_handler : function(blobInfo, success, failure) {
								var xhr, formData;

								xhr = new XMLHttpRequest();
								xhr.withCredentials = false;
								xhr.open('POST', 'ImageUploader.php');

								xhr.onload = function() {
									var json;

									if (xhr.status != 200) {
										failure('HTTP Error: ' + xhr.status);
										return;
									}

									json = JSON.parse(xhr.responseText);

									if (!json || typeof json.file_path != 'string') {
										failure('Invalid JSON: ' + xhr.responseText);
										return;
									}

									success(json.file_path);
								};

								formData = new FormData();
								formData.append('file', blobInfo.blob(), blobInfo.filename());

								xhr.send(formData);
							},
						});
					</script>
				</head>
				<body>
				<div class="bodyMain">
					<span style="font-size: 25px">Create Post</span>&nbsp;
					<div style="float: right;">
						<span id="notific">Write Your Thoughts!</span>
						&nbsp; &nbsp;
						<button class="button-10" onclick="PostNow(<?php echo $groupID; ?>)"><i class="fa-solid fa-floppy-disk"></i> Post Now</button>
						<button class="button-10" onclick="Cancel()"><i class="fa-solid fa-times"></i> Cancel</button>
					</div>
					</br>
					</br>					
					<textarea width="90%" id="editor"></textarea>
				</div>
				</body>
				</html>
				<?php
			}
			
			
		
			
			
				
	}
		
}else{ echo "<script>window.open('login.php','_self')</script>"; } ?>

