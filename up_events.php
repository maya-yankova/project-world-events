<?php
	session_start();
	require 'config.php';
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT id, name, date_start, date_end, time, country, city, cover FROM events ORDER BY date_start ASC");
	$stmt2 = $conn->prepare("SELECT city, country FROM events GROUP BY city ORDER BY city ASC");
	$stmt3 = $conn->prepare("SELECT country FROM events GROUP BY country ORDER BY country ASC");
		
	$stmt->execute();
	$stmt2->execute();
	$stmt3->execute();
	
	$cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	$countries = $stmt3->fetchAll(PDO::FETCH_ASSOC);
	
	//print_r($countries);
	//print_r($cities);
	
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$show = $res;
	
	$c = array();
	foreach($cities as $event){
		//$c[$event['city']] = $event['country'];
		array_push($c, $event['city'], $event['country']);
	}
	//print_r($c);
	
	if(isset($_GET['submit'])){
		$select_country = $_GET['selectcountry'];
		$select_city = $_GET['selectcity'];
		$select_year = $_GET['selectyear'];
		$select_month = $_GET['selectmonth'];
		$select_day = $_GET['selectday'];
		
		if($select_month != "" && $select_month < 10){
			$temp = $select_month;
			$select_month = "0".$temp;
		}
		if($select_day != "" && $select_day < 10){
			$temp = $select_day;
			$select_day = "0".$temp;
		}
		//echo $select_country." ".$select_city." ".$select_year." ".$select_month." ".$select_day;
		
		$q = "";
		
		if($select_country != "" && $select_city == "" && $select_year == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE country = '".$select_country."' ORDER BY date_start ASC";
			//$show = searchByCountry($select_country);
		}
		if($select_country != "" && $select_city == "" && $select_year != "" && $select_month == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE country = '".$select_country."' AND year(date_start) = ".$select_year." ORDER BY date_start ASC";
			//$show = searchByCountryAndYear($select_country, $select_year);
		}
		if($select_country != "" && $select_city == "" && $select_year != "" && $select_month != "" && $select_day == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE country = '".$select_country."' AND year(date_start) = ".$select_year." AND month(date_start) = ".$select_month." ORDER BY date_start ASC";
			//$show = searchByCountryAndYearAndMonth($select_country, $select_year, $select_month);
		}
		if($select_country != "" && $select_city == "" && $select_year != "" && $select_month != "" && $select_day != ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE country = '".$select_country."' AND year(date_start) = ".$select_year." AND month(date_start) = ".$select_month." AND day(date_start) = ".$select_day." ORDER BY date_start ASC";
			//$show = searchByCountryAndDate($select_country, $select_year, $select_month, $select_day);
		}
		if($select_country != "" && $select_city != "" && $select_year == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE city = '".$select_city."' ORDER BY date_start ASC";
			//$show = searchByCity($select_city);
		}
		if($select_country != "" && $select_city != "" && $select_year != "" && $select_month == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE city = '".$select_city."' AND year(date_start) = ".$select_year." ORDER BY date_start ASC";
			//$show = searchByCityAndYear($select_city, $select_year);
		}
		if($select_country != "" && $select_city != "" && $select_year != "" && $select_month != "" && $select_day == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE city = '".$select_city."' AND year(date_start) = ".$select_year." AND month(date_start) = ".$select_month." ORDER BY date_start ASC";
			//$show = searchByCityAndYearAndMonth($select_city, $select_year, $select_month);
		}
		if($select_country != "" && $select_city != "" && $select_year != "" && $select_month != "" && $select_day != ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE city = '".$select_city."' AND year(date_start) = ".$select_year." AND month(date_start) = ".$select_month." AND day(date_start) = ".$select_day." ORDER BY date_start ASC";
			//$show = searchByCityAndDate($select_city, $select_year, $select_month, $select_day);
		}
		if($select_country == "" && $select_year != "" && $select_month == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE year(date_start) = ".$select_year." ORDER BY date_start ASC";
			//$show = searchByYear($select_year);
		}
		if($select_country == "" && $select_year != "" && $select_month != "" && $select_day == ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE year(date_start) = ".$select_year." AND month(date_start) = ".$select_month." ORDER BY date_start ASC";
			//$show = searchByYearAndMonth($select_year, $select_month);
		}
		if($select_country == "" && $select_year != "" && $select_month != "" && $select_day != ""){
			$q = "SELECT id, name, date_start, date_end, time, country, city, cover FROM events WHERE year(date_start) = ".$select_year." AND month(date_start) = ".$select_month." AND day(date_start) = ".$select_day." ORDER BY date_start ASC";
			//$show = searchByDate($select_year, $select_month, $select_day);
		}
		
		if($q != ""){
			$stmt4 = $conn->prepare($q);
			$stmt4->execute();
			
			if($stmt4->rowCount() < 1){ 
				$show = "";
			} else{
				$show = $stmt4->fetchAll(PDO::FETCH_ASSOC);
				
			}
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> WorldEvents | Upcoming Events </title>
	<link href="style.css" rel="stylesheet">
	<script type="text/javascript">
	
		var assoc = <?php echo json_encode($c); ?>;
		//alert(assoc[1]);
		function onChangeCountry(country){
			var select = document.getElementById('selectcity');
			
			if(select.options.length>1) {
				while(select.options.length>1){
					 select.remove(select.length-1);
				}
			} 
			
			for(var i=1; i < assoc.length; i+=2){
				if(assoc[i] == country){
					var opt = document.createElement('option');
					opt.value = assoc[i-1];
					opt.innerHTML = assoc[i-1];
					select.appendChild(opt);	
				}
			}
			
		}
		
		var yearselected;
		function onChangeYear(year){
			yearselected = year;
			
			var select = document.getElementById('selectmonth');
			var m = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			
			if(select.options.length>1) {
				while(select.options.length>1){
					 select.remove(select.length-1);
				}
			} 
			
			for(var i=1; i <= 12; i++){
				var opt = document.createElement('option');
				opt.value = i;
				opt.innerHTML = m[i-1];
				select.appendChild(opt);	
			}
			
		}
		
		function onChangeMonth(month){
			var select = document.getElementById('selectday');
			var m = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
			
			if(select.options.length>1) {
				while(select.options.length>1){
					 select.remove(select.length-1);
				}
			} 
			
			if(month == 2 && (((yearselected % 4 == 0) && (yearselected % 100 != 0)) || (yearselected % 400 == 0))){
				for(var i=1; i <= 29; i++){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);	
				}
			} else{
					for(var i=1; i <= m[month-1]; i++){
						var opt = document.createElement('option');
						opt.value = i;
						opt.innerHTML = i;
						select.appendChild(opt);	
					}
			}
		}
		
		window.onload=function(){
			var sel = document.getElementById('selectyear');
			var year = new Date().getFullYear();
			var optt = document.createElement('option');
			//alert(year);
			optt.value = year;
			optt.innerHTML = year;
			sel.appendChild(optt);
			
			for(var i=1; i < 3; i++){
				var optt2 = document.createElement('option');
				optt2.value = year+i;
				optt2.innerHTML = year+i;
				sel.appendChild(optt2);	
			}

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
		
		<div id="search">
			<form name="input" action="up_events.php" method="get">
				<select id="selectcountry" name="selectcountry" onchange="onChangeCountry(this.value)">
					<option value="">Country:</option>
					<?php
					foreach($countries as $value){
						?><option value="<?php echo $value['country']; ?>"><?php echo $value['country']; ?></option><?php
					}
					?>
				</select>
				<select id="selectcity" name="selectcity">
					<option value="">City:</option>
				</select>
				
				<select id="selectyear" name="selectyear" onchange="onChangeYear(this.value)">
					<option value="">Year:</option>
					
				</select>
				<select id="selectmonth" name="selectmonth" onchange="onChangeMonth(this.value)">
					<option value="">Month:</option>

				</select>
				<select id="selectday" name="selectday">
					<option value="">Day:</option>
				</select>
				<button name="submit" id="filter">Search</button>
			</form>
		</div>
		
		<?php 
				if($show != ""){
				foreach($show as $value){ ?>
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
		<?php }} else{ ?><div class="event"><h3>No events found!</h3></div><?php } ?>
		
		<footer>
			<!--<div id="left_footer">Проект по MMT</div>
			<div id="right_footer">ФМИ 2014</div>-->
		</footer>
	</div>
</body>
<html>
