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
	$msg = "";
	
	if(isset($_POST['submit'])){
		
		$picid = "";
		if(isset($_FILES['cover'])){
			$uploaddir = './images/covers/'; 
			
			$picid = md5(date('Y-m-d H:i:s:u'));
			$filedir = $uploaddir . $picid.".jpg";   
			
			if (move_uploaded_file($_FILES['cover']['tmp_name'], $filedir)) {  
				//resize the original image
			  $imagepath = $picid; 
              $save = "images/covers/" . $imagepath.".jpg"; //This is the new file you saving 
              $file = "images/covers/" . $imagepath.".jpg"; //This is the original file 
  
              list($width, $height) = getimagesize($file) ;  
  
              $modwidth = 500;  
  
              $diff = $width / $modwidth; 
  
              $modheight = $height / $diff;  
              $tn = imagecreatetruecolor($modwidth, $modheight) ;  
              $image = imagecreatefromjpeg($file) ;  
              imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;  
  
              imagejpeg($tn, $save, 100) ;
			  
			} else{
					$msg = "The image should be less than 2MB!"; 					
			}
		} 
		
		$placeid = "";
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt1 = $conn->prepare("SELECT placeid FROM places WHERE placename=?");
		
		
		$stmt1->execute(array($_POST['place']));
		
		if($stmt1->rowCount() > 0){
			$res = $stmt1->fetch(PDO::FETCH_ASSOC);
			
			$placeid = $res['placeid'];
		} else{ 
			$stmt2 = $conn->prepare("INSERT INTO places (placename, lat, lng) VALUES (?, ?, ?)");
			$stmt2->execute(array($_POST['place'], $_POST['lat'], $_POST['lng']));
			
			$stmt3 = $conn->prepare("SELECT placeid FROM places WHERE lat=? AND lng=?");
			$stmt3->execute(array($_POST['lat'], $_POST['lng']));
			
			$res = $stmt3->fetch(PDO::FETCH_ASSOC);
			
			$placeid = $res['placeid'];
			
		}
		
		
		$stmt4 = $conn->prepare("INSERT INTO events (name, date_start, date_end, time, country, city, place, cover, info, video) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			
		$stmt4->execute(array($_POST['name'], $_POST['date_start'], $_POST['date_end'], $_POST['time_start'], $_POST['country'], $_POST['city'], $placeid, $picid, $_POST['info'], $_POST['video']));
		
		$stmt6 = $conn->prepare("SELECT id FROM events WHERE cover=?");
		$stmt6->execute(array($picid));
		$res1 = $stmt6->fetch(PDO::FETCH_ASSOC);
		
		
		$stmt5 = $conn->prepare("INSERT INTO covers (eventid, coverid) VALUES (?, ?)");
		$stmt5->execute(array($res1['id'],$picid));
	}

	$dbh = null;
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Add </title>
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
					<form action="addevent.php" method="post" enctype="multipart/form-data">
						<label for="name">Event Name</label>
						<input type="text" name="name" required>
						
						<label for="date_start">Date Start (yyyy-mm-dd)</label>
						<input type="text" name="date_start" required>
						
						<label for="time_start">Time Start</label>
						<input type="text" name="time_start" value="00:00:00">
						
						<label for="date_end">Date End (yyyy-mm-dd)</label>
						<input type="text" name="date_end" required>
						
						<label for="country">Country</label>
						<input type="text" name="country" required>
						
						<label for="city">City</label>
						<input type="text" name="city" required>
						
						<label for="place">Place</label>
						<input type="text" name="place" required>
						
						<label for="lat">Latitude</label>
						<input type="text" name="lat" required>
						
						<label for="lng">Longitude </label>
						<input type="text" name="lng" required>
						
						<label for="cover">Cover </label>
						<input type="file" name="cover" id="uploadfile" />
						
						<label for="video">Video</label>
						<input type="text" name="video" >
						
						<textarea rows="8" cols="54" name="info" placeholder="Info..."></textarea>
					
						<button name="submit" id="Add">Add</button>
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
