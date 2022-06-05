<?php 


session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);
 

?>

<!DOCTYPE html>
<html>
<head>
	<title>GalaxyV Support-Protokoll</title>
	<link href="img/logo.png" rel="icon">
	<link rel="stylesheet" href="css/index.css">
	<script src="js/script.js"></script>
</head>
<body>
<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<a href="index.php">Startseite</a>
  <a href="form.php">Support Eintragen</a>
  <a href="logout.php">Ausloggen</a>
  <a onclick="alert('Muss noch')">Admin</a>
</div>

<div id="main">
  <button class="openbtn" onclick="openNav()">&#9776;</button>
	<p class="galaxy">Galaxy<span class="v">V</span> Support-Protokoll</p>
</div>
<style>
	.ubersich {
		position: absolute;
		top: 20%;
		left: 15%;
		color: white;
	}
</style>
<div class="ubersich">Hier soll eine Übersicht mit persöhnlichen Ticket die in data gespeichert sind hin und eine Übersicht von allen Tickets (Also nur die Anzahl)

<li>
	Admin Panel (Nur mit rechten über die DB zugängig)
	<ul>
		Benutzer löschen können
	</ul>
	<ul>
		Benutzer freischalten können (access von 0 auf 1 setzen)
	</ul>
	<ul>
		Übersicht über die Tickets (Username von dem der es eingetragen hat, user(Discord Tag), Der grund und der Ausgang )
	</ul>
	<ul>
		Übersicht wer wie viele tickets hat 
	</ul>

</li>
</div>
<div class="hallo">Hallo, <?php echo $user_data['user_name']; ?></div>
<button class="logout" onclick="window.location.href = 'logout.php';">Ausloggen</button>
<button class="eintragen" onclick="window.location.href = 'form.php';">Support Eintragen</button>


</body>
</html>