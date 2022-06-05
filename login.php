<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			$query = "select * from users where user_name = '$user_name' limit 1";
			$result = mysqli_query($con, $query);


			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password && $user_data['access'] == 1)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo "<div class='error'>Falscher Benutzername oder Passwort!</div>";
		}else
		{
			echo "<div class='error'>Falscher Benutzername oder Passwort!</div>";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link href="img/logo.png" rel="icon">
	<link rel="stylesheet" href="css/login.css">
</head>
<body>

	<style type="text/css">
	
	#text{

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
		top: 50%;
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
	<div id="box">
		
		<form method="post">

		<div class="galaxy">Galaxy<span class="v">V</span> Support-Protokoll</div>

			<input class="text1" id="text" placeholder="Benutzername" type="text" name="user_name"><br><br>
			<input class="text2" id="text" type="password" placeholder="Passwort" name="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>

		</form>
	<button class="registerbtn" onclick="window.location.href = 'signup.php';">Registrieren</button>
	</div>
</body>
</html>