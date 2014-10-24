<?php
	session_start();
	require 'config.php';
	
	$msg="";
	if(isset($_POST['submit'])){
		
			$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
			$stmt = $conn->prepare("INSERT INTO requests (first_name, last_name, email, request, date) VALUES (?, ?, ?, ?, ?)");
			
			$stmt->execute(array($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['request'], date("Y-m-d H:i:s")));
			
			$msg = "Your request has been sent!";
	}

	$dbh = null;
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Contacts </title>
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
					<form action="contacts.php" method="post">
						<label for="first_name">First Name</label>
						<input type="text" name="first_name" required>
						
						<label for="last_name">Last Name</label>
						<input type="text" name="last_name" required>
						
						<label for="email">Email</label>
						<input type="email" name="email" required>
						
						<textarea rows="8" cols="54" name="request" placeholder="Leave your question/request..." required></textarea>
					
						<button name="submit" id="send">Send</button>
					</form>
					<div class="msg"> <?php echo $msg; ?> </div>
				</div>
		</div>
		
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>
