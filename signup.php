<?php 
session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];
		$email = $_POST['email'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			$user_id = random_num(20);
			$query = "insert into users (user_id,user_name,password,email) values ('$user_id','$user_name','$password', '$email')";

			mysqli_query($con, $query);

			header("Location: login.php");
			die;
		}else
		{
			echo "Please enter some valid information!";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Registrieren</title>
	<link href="img/logo.png" rel="icon">
	<link rel="stylesheet" href="css/signup.css">
</head>
<body>

	<style type="text/css">
	
	#text{

		margin-top: 2%;
position: absolute;
height: 25px;
border-radius: 5px;
padding: 4px;
border: solid thin #aaa;
width: 40%;
background-color: white;
border: none;
}

#button{

position: absolute;
top: 70%;
padding: 10px;
width: 100px;
color: grey;
background-color: white;
border-radius: 7px;
border: none;
}

#button:hover {
background-color: rgb(209, 209, 209);
}

	#box{

		position: absolute;
		background: rgb(255,0,121);
background: radial-gradient(circle, rgba(255,0,121,1) 0%, rgba(0,0,0,1) 100%);
		top: 30%;
		left: 30%;
		margin: auto;
		width: 40%;
		height: 30%;
		padding: 20px;
		border-radius: 20px;
		box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
	}

	</style>

<img class="logobig" src="img/logo.png" alt="">
  <div class="galaxy">Galaxy<span class="v">V</span> Support-Protokoll</div>
	<div id="box">
		
		<form method="post">
			<div style="font-family: 'Montserrat', sans-serif ;font-size: 20px;margin: 10px; top: 10%;color: white;">Registration</div>

			<input class="box1" id="text" type="text" name="user_name" placeholder="Benutzername"><br><br>
			<input class="box2" id="text" type="email" name="email" placeholder="Email Adresse"><br><br>
			<input class="box3" id="text" type="password" name="password" placeholder="Passwort"><br><br>

			<input id="button" type="submit" value="Registrieren"><br><br>


		</form>
		<button class="registerbtn" onclick="window.location.href = 'login.php';">Bereits Registriert?</button>
	</div>
</body>
</html>