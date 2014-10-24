<?php
	session_start();
	require 'config.php';
	
	if(!isset($_SESSION['id'])){
		header("Location: index.php");
		exit();
	}
	
	if(isset($_POST['change'])){
			  
			$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
			$stmt = $conn->prepare("UPDATE users SET username=?, password=?, email=? WHERE id=".$_SESSION['id']);
			
			$stmt->execute(array($_POST['newusername'], MD5($_POST['newpassword']), $_POST['newemail']));
			
			$_SESSION['username'] = $_POST['newusername'];
			
			$_SESSION['email'] = $_POST['newemail'];
			
			header("Location: index.php");
	}
	
	if(isset($_POST['delete'])){
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("DELETE FROM users WHERE id=".$_SESSION['id']);
			
		$stmt->execute();
		
		session_unset();
		session_destroy();
		
		header("Location: index.php");
	}

	$dbh = null;
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Profile </title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	
	<div id="wrapper">
		<header>
			<div id="log">
				<canvas id="myCanvas" width="350" height="50" >
					Your browser does not support the HTML5 canvas tag.</canvas>
				<?php 
					if(isset($_SESSION['username'])){
				?>
				<nav class="sec">
					<h1><a href="profile.php"><?php echo $_SESSION['username']; ?></a></h1>
					<?php if($_SESSION['status'] == 'admin' || $_SESSION['status'] == 'mod'){ ?><h1><a href="addevent.php">Add Event</a></h1><?php } ?>
					<?php if($_SESSION['status'] == 'admin' || $_SESSION['status'] == 'mod'){ ?><h1><a href="requests.php">Requests</a></h1><?php } ?>
					<?php if($_SESSION['status'] == 'admin'){ ?><h1><a href="users.php">Users</a></h1><?php } ?>
					<h1><a href="logout.php">Logout</a></h1>
				</nav> 
				<?php }
					else{
				?>
				<nav class="sec">
					<h1><a href="login.php">Login</a></h1>
					<h1><a href="register.php">Register</a></h1>
				</nav>
				<?php }?>
			</div>
			<div id="navigation">
				<nav class="main">
					<a href="index.php"><img src="images/world-calendar.jpg" alt="world-calendar" ></a>
					<a href="mycalendar.php"><img src="images/my-calendar.jpg" alt="my-calendar" ></a>
					<a href="up_events.php"><img src="images/events.jpg" alt="events" ></a>
					<a href="contacts.php"><img src="images/contacts.jpg" alt="contacts" ></a>
				</nav>
			</div>
		</header>
		
		<script type="text/javascript">

			var c = document.getElementById("myCanvas");
			var ctx = c.getContext("2d");
			ctx.font = "60px Rage Italic";
			ctx.fillStyle = "#931717";
			ctx.fillText("World Events",0,45);

		</script>
		
		<div class="event">
			<div class="request">
					<form id="edit" action="profile.php" method="post" >
						<div><label for="newusername">Username:</label><br>
						<input type="text" name="newusername" value="<?php echo $_SESSION['username'];?>"> </div>
						
						<div><label for="newpassword">Password:</label><br>
						<input type="password" name="newpassword" value="" required> </div>
						
						<div><label for="newemail">Email:</label><br>
						<input type="email" name="newemail" value="<?php echo $_SESSION['email'];?>"> </div>
						
						<div><button name="change" id="change">Change</button><button name="delete" id="delete">Delete Profile</button></div>
					</form> 
					
				</div>
		</div>
		
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>
