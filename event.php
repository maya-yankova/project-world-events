<?php 
	session_start();
	require 'config.php';
	
	if(!isset($_GET['eventid'])){
		header("Location: index.php");
	} 
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT events.id, events.name, events.date_start, events.date_end, events.time, events.country, events.city, places.placename, places.lat, places.lng, events.cover, events.info, events.video 
							FROM events JOIN places 
							ON events.place = places.placeid
							WHERE events.id=?");
		
	$stmt->execute(array($_GET['eventid']));
	$res = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$eventid = $res['id'];
	$eventname = $res['name'];
	$eventdate_start = date_format(date_create($res['date_start']), 'j F Y');
	$eventdate_end = date_format(date_create($res['date_end']), 'j F Y');
	$eventtime = date_format(date_create($res['time']), 'g:i a');
	$eventcountry = $res['country'];
	$eventcity = $res['city'];
	$eventplace = $res['placename'].",";
	$eventlat = $res['lat'];
	$eventlng = $res['lng'];
	$eventcover = $res['cover'];
	$eventinfo = $res['info'];
	$eventvideo = $res['video'];
	
	$msg = "add";
	if(isset($_SESSION['username'])){
	$stmt1 = $conn->prepare("SELECT * FROM favorites WHERE userID=? AND eventID=?");
		
	$stmt1->execute(array($_SESSION['id'], $_GET['eventid']));
		
	if($stmt1->rowCount() > 0){
		$msg = "remove";
	} 
	}
	

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | <?php echo $eventname; ?></title>
	<link href="style.css" rel="stylesheet">
	<script type="text/javascript" src="balloon/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="balloon/jquery.balloon.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKWo9VUJQ2jd-5sRn6CSvMZNfkkTZ_Aww"></script>
    <script type="text/javascript">
	
		function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(parseFloat("<?php echo $eventlat;?>"), parseFloat("<?php echo $eventlng;?>")),
          zoom: 15
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
      
		var marker = new google.maps.Marker({
			position: map.getCenter(),
			map: map,
		});
	  }
      google.maps.event.addDomListener(window, 'load', initialize);
	  
	  
	function change(status){
		var status2;
		var stat;
		if(status == "add") { status2 = "remove"; stat="Added!"}
		if(status == "remove") { status2 = "add"; stat="Removed!"}
		
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
		xmlhttp.open("GET","add-remove.php?eventid=<?php echo $_GET['eventid'];?>&action="+status,true);
		
		xmlhttp.send();
		
		document.getElementById("fav").alt=status2;
		document.getElementById("fav").src="images/"+status2+".jpg";
		
		$(function() {
		  $('#fav').showBalloon({ contents: stat, css: {
														 minWidth: "20px",
														 padding: "5px",
														 borderRadius: "6px",
														 border: "solid 1px #777",
														 boxShadow: "4px 4px 4px #555",
														 color: "#666",
														 backgroundColor: "#efefef",
														 opacity: "0.85",
														 zIndex: "32767",
														 textAlign: "left"
														} });
		
		});
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
		
		<div class="event">
			<article class="main">
				<div class="eventname"><h1><?php echo $eventname?></h1></div>
				<div class="coverwrap"><img class="cover" src="images/covers/<?php echo $eventcover;?>.jpg"></div>
				<div class="date"><b>When </b><?php echo $eventdate_start.", ".$eventtime." - ".$eventdate_end;?></div>
				<div class="location"><b>Where </b><?php echo $eventplace." ".$eventcity.", ".$eventcountry;?></div>
				<div class="info"><b>More information:</b><br><br><?php echo $eventinfo?></div>
				<?php 
					if(isset($_SESSION['username'])){
				?>
				<img id="fav" onclick="change(this.alt)" src="images/<?php echo $msg;?>.jpg" alt="<?php echo $msg;?>">
				<div id="myDiv"></div>
				<?php }	?>
				
			</article>
			
			<article>
			<div id="map-canvas"></div>
			<?php if($eventvideo != ""){?>
			<div id="youtube">
				<iframe width="450" height="253" src="//<?php echo $eventvideo?>" frameborder="0" allowfullscreen></iframe>
			</div>
			<?php }?>
			</article>
			
			
		</div>
		
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>