<?php
	session_start();
	require 'config.php';
	
	if(!isset($_SESSION['id']) || $_SESSION['status'] != 'admin'){
		header("Location: index.php");
		exit();
	}
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT id, username, email, status FROM users ORDER BY username ASC");
	
	$stmt->execute();


	$dbh = null;
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Users </title>
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
		<div  class="myevent" >
		<?php 
				if($stmt->rowCount() > 0){
					$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
					?><ol><?php
					foreach($res as $value){ 
					
					$msg1 = "status1";
					
					if($value['status'] == 'mod'){ $msg1 = "status2";}
					
					if($value['status'] != 'admin'){
					?>
					<li id="li<?php echo $value['id'];?>">
						<b><?php echo $value['username']." | ".$value['email']." &ensp;&ensp;"; ?></b>
						<span id="<?php echo $value['id'];?>" title="mod" style="cursor: pointer;" class="<?php echo $msg1;?>" onclick="setStatus(this.title, this.className, this.id)">&lt;Moderator&gt;</span> 
						| 
						<span style="cursor: pointer;" onclick="deleteLi(<?php echo $value['id'];?>)">&lt;Delete&gt;</span>
						<div id="myDiv"></div>
					</li><br>
		<?php }}
					?> </ol> <?php } ?>
		</div>
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>
