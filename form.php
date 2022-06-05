<?php 


session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/form.css">
  <title>Support Eintragen</title>
</head>
<body>
<script src="js/script.js"></script>
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


<div class="box"></div>
<center>
         <form action="insert.php" method="post">
             
<p>
               <input class="user" type="text" name="user" id="user" placeholder="User" required/>
            </p>
 
             
<p>
               <input class="grund" type="text" name="grund" id="grund" placeholder="Grund" required/>
            </p>
 
             
<p>
               <input class="ausgang" type="text" name="ausgang" id="ausgang" placeholder="Ausgang" required/>
</p>

<p>
               <input class="name" type="text" name="name" id="name" value="<?php echo $user_data['user_name']; ?>" placeholder="Dein Benutzername" readonly required/>
</p>
 
            <input class="absenden" type="submit" value="Absenden">
         </form>
      </center>
      <div class="galaxy2">Galaxy<span class="v">V</span> Support-Protokoll</div>
      <img class="logobig" src="img/logo.png" alt="">
<button class="logout" onclick="window.location.href = 'logout.php';">Ausloggen</button>
<button class="eintragen" onclick="window.location.href = 'form.php';">Support Eintragen</button>
</body>
</html>