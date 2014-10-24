<?php
session_start();
require 'config.php';

if(isset($_GET['eventid']) && isset($_GET['action']) && isset($_SESSION['id'])){
$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);

	if($_GET['action'] == 'add'){
		$stmt2 = $conn->prepare("INSERT INTO favorites (userID, eventID) VALUES (?, ?)");
			
		$stmt2->execute(array($_SESSION['id'], $_GET['eventid']));
	} 
	if($_GET['action'] == 'remove'){
	
		$stmt3 = $conn->prepare("DELETE FROM favorites WHERE userID=? AND eventID=?");
			
		$stmt3->execute(array($_SESSION['id'], $_GET['eventid']));
		
		
	}
} else{
		if(isset($_GET['title']) && isset($_GET['status']) && isset($_GET['user']) && isset($_SESSION['id']) && $_SESSION['status'] == 'admin'){
			$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
			$stmt4 = $conn->prepare("UPDATE users SET status=? WHERE id=".$_GET['user']);
			
			if($_GET['title'] == 'mod' && $_GET['status'] == 'status1'){$stmt4->execute(array('mod'));}
			if($_GET['title'] == 'mod' && $_GET['status'] == 'status2'){$stmt4->execute(array('user'));}
			
		} else { if(isset($_GET['user']) && isset($_SESSION['id']) && $_SESSION['status'] == 'admin'){
						$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
						$stmt = $conn->prepare("DELETE FROM users WHERE id=".$_GET['user']);
							
						$stmt->execute();
				}else{

				header("Location: index.php");}
		}
}
?>