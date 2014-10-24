<?php
	session_start();
	require 'config.php';
	
	if(!isset($_SESSION['id'])){
		header("Location: login.php");
	}
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT events.id, events.name, events.date_start, events.date_end, events.time, events.country, events.city, events.cover
							FROM favorites 
							JOIN events ON favorites.eventID = events.id 
							WHERE favorites.userID=?
							ORDER BY events.date_start ASC");
		
	$stmt->execute(array($_SESSION['id']));
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | My Calendar </title>
	<link href="style.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="cstheme.css?v=130" />
	<script src="js/jquery-2.1.1.js" type="text/javascript"></script>
    <script src="js/daypilot/daypilot-all.min.js" type="text/javascript"></script>

</head>
<body>
	<audio id="audio" src="audio/button-16.wav"></audio>
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
		
		<div id="main">

	        <div id="content">
	            <div>
					<div class="space">
						<a href="javascript:

									dp.startDate = dp.startDate.addMonths(-1); 
									dp.update(); 
									document.getElementById('month').innerHTML = mmonth[dp.startDate.getMonth()]; 
									document.getElementById('year').innerHTML = dp.startDate.getYear(); audio.play();">Previous</a>
						|
						<a href="javascript:

									dp.startDate = dp.startDate.addMonths(1); 
									dp.update(); 
									document.getElementById('month').innerHTML = mmonth[dp.startDate.getMonth()];
									document.getElementById('year').innerHTML = dp.startDate.getYear(); audio.play();">Next</a>
						| <span id="month"></span> <span id="year"></span>
					</div>

					<div id="dp"></div>

					<script type="text/javascript">
					var audio = document.getElementById("audio");
						var dp = new DayPilot.Month("dp");
						dp.weekStarts = 1;
						
						var mmonth = new Array();
											mmonth[0] = 'January';
											mmonth[1] = 'February';
											mmonth[2] = 'March';
											mmonth[3] = 'April';
											mmonth[4] = 'May';
											mmonth[5] = 'June';
											mmonth[6] = 'July';
											mmonth[7] = 'August';
											mmonth[8] = 'September';
											mmonth[9] = 'October';
											mmonth[10] = 'November';
											mmonth[11] = 'December';
											
						var today = new Date();
						var dd = today.getDate();
						var mm = today.getMonth()+1;
						var yyyy = today.getFullYear();
						
						document.getElementById('month').innerHTML = mmonth[mm-1];
						document.getElementById('year').innerHTML = yyyy;
						
						if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} var today = yyyy+'-'+mm+'-'+dd;
						
						dp.startDate = today;
						
						dp.theme = "cstheme";
						
					
						function showMyEvents(ev){
							for (var i = 0; i < ev.length; i++) {
								var e = new DayPilot.Event({
									start: new DayPilot.Date(ev[i]["date_start"]+"T00:00:00"),
									end: new DayPilot.Date(ev[i]["date_start"]+"T00:00:00"),
									id: ev[i]['id'],
									text: ev[i]['name']+' Start'
								});
								dp.events.add(e);
							}
						}
						
						dp.onEventClicked = function(args) {
							$('html, body').animate({
								scrollTop: $("#"+args.e.id()).offset().top
							}, 800);
							audio.play();
						}
						
						dp.init();

					</script>
                </div>
	        </div>
        </div>
		
		<?php 
			if($stmt->rowCount() < 1){ ?>
				<div class="event"><h3>You don't have any events in your calendar.</h3></div>
		<?php
			} else{ 
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				?><script type="text/javascript"> showMyEvents(<?php echo json_encode($res);?>);</script><?php
				//print_r($res);
				foreach($res as $value){ ?>
					<div id="<?php echo $value['id']?>" class="myevent" style="cursor: pointer;" onclick="window.location='event.php?eventid=<?php echo $value['id']?>';">
						<article class="main">
							<div class="eventname"><h1><?php echo $value['name'];?></h1></div>
							<div class="date"><b>When </b><?php echo date_format(date_create($value['date_start']), 'j F Y').' - '.date_format(date_create($value['date_end']), 'j F Y');?></div>
							<div class="location"><b>Where </b><?php echo $value['city'].", ".$value['country'];?></div>
						</article>
						<article class="cov">
							<div class="coverwrap"><img class="cover" src="images/covers/<?php echo $value['cover'];?>.jpg"></div>
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
