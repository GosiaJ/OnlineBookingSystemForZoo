<?php
session_start();

require_once("connect.php");
mysqli_report(MYSQLI_REPORT_STRICT);

if(isset($_POST['mailLog']))
{
	$email = $_POST['mailLog'];
	$pass = $_POST['pasLog'];

	try
	{
		//echo $email;
		$connection = new mysqli($host, $db_user, $db_pas, $db_name);
		if($connection->connect_errno != 0)
		{
			throw new Exception(mysqli_connest_errno());
		}
		if($LoginQuery = @$connection->query(sprintf("SELECT * FROM users WHERE email = '$email'",mysqli_real_escape_string($connection,$email))))
		{
			if($LoginQuery->num_rows > 0)
			{
				//echo "ja";
				$row = $LoginQuery->fetch_assoc();
				if(password_verify($pass, $row['pas']))
				{
					$_SESSION['LogAs'] = $row['name'];
					$_SESSION['LogMail'] = $row['email'];
					$LoginQuery->free_result();
					header('Location: main.php');
				}
				else
				{
					$_SESSION['LogErr'] = "Niepoprawne hasło!";
				}
			}
			else
			{
				$_SESSION['LogErr'] = "Niepoprawny login lub hasło!";
			}
			$connection->close();
		}
	}
	catch(Exception $ex)
	{
		echo "Info develop: " . $ex;
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>

<title>System Internetowej Rezerwacji Usług</title>

<style>
body{
	text-align: center;
}
.agileheader{
	margin: 50px, 20px, 10px, 20px;
}

.agileheader h1{
	color: grey;
    text-align: center;
}
.logowanie{

}
.logowanie h1{
	color: grey;
    text-align: center;
    font-size: 25px;
}
form{
	display: inline-block;
    text-align: center;
}
img{
	height: 30px;
	width: 30px;
	margin: 2px;
	vertical-align: middle;
}
input[type=text], input[type=password]{
	border: none;
	height: 40px;
	width: 240px;
    border-bottom: 2px solid #555;
}

input[type=text]:focus, input[type=password]:focus {
    border: 3px solid #666;
}
input[type=submit], input[type=button]{
	width: 120px;
	height: 40px;
	 background-color: #555;
	 border: none;
     color: white;
     cursor: pointer;
}
.rejestracja h3{
	color: grey;
    text-align: center;
}

</style>

</head>

<body>
<div class="agileheader">
	<h1>System Internetowej Rezerwacji Usług</h1>
</div>

<div class="logowanie">
	<h1>Zaloguj się</h1>
	<form action="#" method="POST">
	<img src="icons/avatar.png"/><input type="text" name="mailLog" placeholder="Adres e-mail"/> <br/><br/>
	<img src="icons/key.png"/><input type="password" name="pasLog" placeholder="Hasło" /> <br/><br/>
	<?php
		if(isset($_SESSION['LogErr']))
		{
			echo '<span style="color:red;">* '.$_SESSION['LogErr'].'</span><br/>';
			unset($_SESSION['LogErr']);
		}
	?>
	<input type="Submit" name="zaLog" value="Zaloguj"/> <br/><br/>
	</form>
</div>
<div class="rejestracja">
	<h3>Jeżeli nie posiadasz konta:</h3>
	<form action="rej.php">
	<input type="submit" name="zaRej" value="Zarejestruj się"/> <br/><br/>
	</form>
</div>


</body>

</html> 