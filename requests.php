<?php
	session_start();
	require 'config.php';
	
	if(!isset($_SESSION['id'])){
		header("Location: index.php");
		exit();
	}
	if($_SESSION['status'] != 'admin' && $_SESSION['status'] != 'mod'){
		header("Location: index.php");
		exit();
	}
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT id, first_name, last_name, email, request, date FROM requests ORDER BY date ASC");
	
	$stmt->execute();


	$dbh = null;
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Requests </title>
	<link href="style.css" rel="stylesheet">
	<script type="text/javascript">
		function setStatus(title, cl, user){
				var cl2;
				
				if(cl == "status1") { cl2="status2"}
				if(cl == "status2") { cl2="status1"}
				
				var xmlhttp;
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				  xmlhttp.responseText = "Done";
				xmlhttp.onreadystatechange=function()
				  {
				  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
					document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
					}
				  }
				xmlhttp.open("GET","add-remove.php?title="+title+"&status="+cl+"&user="+user,true);
				
				xmlhttp.send();
				
				document.getElementById(user).className=cl2;
				
			}
			
		function deleteLi(user){
				var xmlhttp;
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				  xmlhttp.responseText = "Done";
				xmlhttp.onreadystatechange=function()
				  {
				  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
					document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
					}
				  }
				xmlhttp.open("GET","add-remove.php?&user="+user,true);
				
				xmlhttp.send();
				
				var elem = document.getElementById('li'+user);
				elem.parentNode.removeChild(elem);
		}
	</script>
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
		
		<?php 
				if($stmt->rowCount() < 1){ ?>
				<div class="event"><h3>No requests.</h3></div>
		<?php
			} else{ 
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $value){ ?>
					<div id="<?php echo $value['id']?>" class="myevent" >
						<article class="main">
							<div ><h5>From: <?php echo $value['first_name'];?> <?php echo $value['last_name'];?></h5></div>
							<div ><h5>Email: <?php echo $value['email'];?></h5></div>
							<div ><h5>Date: <?php echo $value['date'];?></h5></div>
							<div ><h5>Request: <?php echo $value['request'];?></h5></div>
						</article>
						
					</div>
		<?php }}?>
		
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>
