<?php 
	session_start();
	require 'config.php';
	
	function check_user_login($user,$pass){
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("SELECT id, username, email, status FROM users WHERE username=? AND  password=? LIMIT 1");
		
		$stmt->execute(array($user, MD5($pass)));
		
		if($stmt->rowCount() > 0){
			$res = $stmt->fetch(PDO::FETCH_ASSOC);
			return $res;
		} else{
			return false;
		}
	}
	$msg="";
	if(isset($_POST["submit"])){
		$row = check_user_login($_POST['username'], $_POST['password']);

		if($row){
			$_SESSION['username'] = $row['username'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['status'] = $row['status'];
			$_SESSION['email'] = $row['email'];
			
			header("Location: index.php");
			exit();
		} else{
			$msg = "Invalid username or password!";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Login </title>
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
		<section id="login">
				<?php if(!isset($_SESSION['username'])) { ?>
                <h1 class="section_title">Login</h1>
				<div class="reg_log">
					<form action="login.php" method="post">
						<label for="username">Username</label>
						<input type="text" name="username" required>
						
						<label for="password">Password</label>
						<input type="password" name="password" required>
					
						<button name="submit" id="login_button">Login</button>
					</form>
				<div>
				<?php } else { ?>
				<h1> You are already logged in! </h1>
				<?php } ?>
        </section>
		<div class="msg"> <?php echo $msg; ?> </div>
		</div>
		
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>
