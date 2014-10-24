<?php 
	session_start();
	require 'config.php';
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT events.id, events.name, events.date_start, events.date_end, events.place, places.lat, places.lng FROM events JOIN places ON events.place = places.placeid");
	
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Home</title>
	<link href="style.css" rel="stylesheet">
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKWo9VUJQ2jd-5sRn6CSvMZNfkkTZ_Aww"></script>
	<script type="text/javascript">
		
		function initialize() {
			var mapOptions = {
			  center: new google.maps.LatLng(23.667960, 1.173115),
			  zoom: 2
			};
			var map = new google.maps.Map(document.getElementById("map"),
				mapOptions);
			 
			var a = <?php echo json_encode($res); ?>;
			var infowindow = new google.maps.InfoWindow();
			var markers = [];
			
			for (var i = 0; i < a.length; i++) {

				var content;
				var latLng = new google.maps.LatLng(parseFloat(a[i]["lat"]), parseFloat(a[i]["lng"]));
				var date_start = new Date(a[i]["date_start"]);
				var date_end = new Date(a[i]["date_end"]);
				
				if (markers.indexOf(a[i]["place"]) > -1){
					markers[markers.indexOf(a[i]["place"]) + 1] += '<div class="markerinfo"><u>'+a[i]["name"]+'</u><br>'+date_start.toDateString()+' - '+date_end.toDateString()+'<br><a href="event.php?eventid='+a[i]["id"]+'">Go to event page</a></div>';
					content = markers[markers.indexOf(a[i]["place"]) + 1];
				} else{
				
				markers.push(a[i]["place"]);
				content = '<div class="markerinfo"><u>'+a[i]["name"]+'</u><br>'+date_start.toDateString()+' - '+date_end.toDateString()+'<br><a href="event.php?eventid='+a[i]["id"]+'">Go to event page</a></div>';
				markers.push(content);
				}
				
				
				var marker = new google.maps.Marker({
				  position: latLng,
				  map: map
				});
				
				var content1 = '<div style="max-height:200px">'+content+'</div>';
				
				google.maps.event.addListener(marker, 'click', (function(marker, content1) {
					return function() {
						
						infowindow.setContent(content1);
						infowindow.open(map, marker);
						
					}
				})(marker, content1));
			}
			
			
		}
		
		google.maps.event.addDomListener(window, 'load', initialize);
	
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
			<div id="map" ></div>
		</div>
		

		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>