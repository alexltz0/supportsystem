<!DOCTYPE html>
<html>

<head>
	<title>An Datenbank senden...</title>
</head>

<body>
	<center>
		<?php

		$conn = mysqli_connect("localhost", "root", "", "support");
		
		if($conn === false){
			die("ERROR: "
				. mysqli_connect_error());
		}
		
		$user = $_REQUEST['user'];
		$grund = $_REQUEST['grund'];
		$ausgang = $_REQUEST['ausgang'];
    $username = $_REQUEST['name'];
		
		$sql = "INSERT INTO `data` (`user`, `grund`,`ausgang`,`username`) VALUES ('$user',
			'$grund','$ausgang', '$username')";
		
		if(mysqli_query($conn, $sql)){
      header("Location: form.php");
			die;
		} else{
			echo "ERROR: Hush! Sorry $sql. "
				. mysqli_error($conn);
		}
		
		mysqli_close($conn);
		?>
	</center>
</body>

</html>
