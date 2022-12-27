<?php
	session_start();
	
	//create database connection
	include("connect_db.php");
	
	//create blank variable
	$getsessionID = "";
	$message = "";
	$Name = "Web Project";
	
	
	//call session data
	if(isset($_SESSION['librarypanel'])){	
		//get session id from browser and update variable
		$getsessionID = $_SESSION['librarypanel'];
	}
	
	//set the validity mode for session data
	$validity = mysqli_real_escape_string($con,"valid");
	
	//verify session id
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity' ORDER BY `id` DESC LIMIT 1"))> 0){
	
		echo "<script>window.open('index.php','_self')</script>";
	
	} else {
			
				//get login form data
				
				if(isset($_POST['login'])){
					//get user name and password
					$user_name = mysqli_real_escape_string($con, $_POST["user_name"]);
					$user_pass = mysqli_real_escape_string($con, $_POST["pass"]);
					
					//match the username and password from database
					if(mysqli_num_rows(mysqli_query($con, "select * from users where username='$user_name' AND password='$user_pass'"))> 0){

						//get user ID and Privilage
						$new_query="select * from users where username='$user_name' AND password='$user_pass'";				
						if($rows=mysqli_fetch_array(mysqli_query($con, $new_query), MYSQLI_ASSOC)){  
							$userid = $rows['id'];
							$privilege = $rows['adminprivilege'];
						}
						
						//create unique session id
						$ipaddress = $_SERVER['REMOTE_ADDR'];
						$sessionID = time();
						//$sessionID = hash('sha256', $user_name + $_SERVER['REMOTE_ADDR'] + time());
						$issuetime = time();
						$expirytime = "0";
						$validity = "valid";
						$browser = $_SERVER['HTTP_USER_AGENT'];
						$user_ip = getenv('REMOTE_ADDR');
						$geo = "";
						$country = "Bangladesh";
						$city = "Dhaka";
						$location = "";		  
						//save session id, IP Address, Login Information to Database
						 mysqli_query($con, "
						 Insert Into `sessions` (`session_id`, `user_id`, `issued`, `expiry_time`, `ipaddress`,`browser` ,`location`, `validity`) Values
						  (
							'$sessionID',
							'$userid',
							'$issuetime',
							'$expirytime',
							'$ipaddress',
							'$browser',
							'$location',
							'$validity'
						  )
						  ");	  

							$_SESSION['librarypanel'] = $sessionID;
							$_SESSION['username'] = $user_name;
							$_SESSION['userid']= $userid;
							$_SESSION['privilege']= $privilege;
							
							setcookie("sessionid", $sessionID, time() + 31536000, '/');
							setcookie("username", $user_name, time() + 31536000, '/');
							setcookie("userid", $userid, time() + 31536000, '/');
							setcookie("privilege", $privilege, time() + 31536000, '/');
							
							echo "<script>window.open('index.php','_self')</script>";
							//echo mysql_error();
							
					} else {
						$message = "User Name or Password is Incorrect";
						//echo "<script>alert('User Name or Password is Incorrect')</script>";
						//echo mysql_error();
					}
				}
		
			?>
			
			<html>
			<head>
				<title>Login - Facebook project</title>
				<style type="text/css" >

				body {
				  background-color: rgb(36, 37, 38);
				  color: white;
				  font-family: Trebuchet MS;
				  background-image: url(assets/1019517.jpg);
				}
				
				.inputBox{
				  padding: 11px;
				  width: 250px;
				  font-size: 18px;
				  color: gray;
				  border-radius: 7px;
				  border: 1px solid gray;
				  background: white;
				  margin-bottom: 7px;
				}


				/* CSS */
				.button-15 {
				  background-image: linear-gradient(#42A1EC, #0070C9);
				  border: 1px solid #0077CC;
				  border-radius: 8px;
				  box-sizing: border-box;
				  color: #FFFFFF;
				  cursor: pointer;
				  direction: ltr;
				  display: block;
				  font-family: "SF Pro Text","SF Pro Icons","AOS Icons","Helvetica Neue",Helvetica,Arial,sans-serif;
				  font-size: 19px;
				  font-weight: 400;
				  letter-spacing: 0.08em;
				  line-height: 1.47059;
				  min-width: 30px;
				  overflow: visible;
				  padding: 4px 15px;
				  text-align: center;
				  vertical-align: baseline;
				  user-select: none;
				  -webkit-user-select: none;
				  touch-action: manipulation;
				  white-space: nowrap;
				  width: 250px;
				}

				.button-15:disabled {
				  cursor: default;
				  opacity: .3;
				}

				.button-15:hover {
				  background-image: linear-gradient(#51A9EE, #147BCD);
				  border-color: #1482D0;
				  text-decoration: none;
				}

				.button-15:active {
				  background-image: linear-gradient(#3D94D9, #0067B9);
				  border-color: #006DBC;
				  outline: none;
				}

				.button-15:focus {
				  box-shadow: rgba(131, 192, 253, 0.5) 0 0 0 3px;
				  outline: none;
				}
				
				.container {
					background-color: #0000007a;
					/* text-align: center; */
					width: 359px;
					padding: 30px;
					border-radius: 11px;
					box-shadow: 0px 0px 21px 18px #ffffff21;
				}

			</style>
			</head>
			<body>
				<div style="height: 50px;"></div>
				<center>
				<div class="container">
				</br>
				<h1><?php echo $Name;?></h1>
				<h2>Login</h2>
				</br>
				<span><?php echo $message; ?></span>
				<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input class="inputBox" type="text" placeholder="Username" name="user_name">
					</br>
					<input class="inputBox" type="password" placeholder="Password" name="pass">
					</br>
					<input class="button-15" type="submit" value="Login" name="login">
				</form>
				Forgot Password? | Create Account
					</br>
				</div>
				</center>	
			</body>
			</html>
	<?php } ?>