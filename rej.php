<?php
session_start();

//zakładamy, ze wszystko jest poprawnie
$poprawnyFormularz = 1;
//początek walidacji

if(isset($_POST['imie']))
{
	//sprawdź czy imie nie jest puste i składa się tylko
	$imie = $_POST['imie'];
	if (!preg_match('/^[A-Za-z]+$/', $imie) ) 
	{
		$poprawnyFormularz = 0;
		$_SESSION['imieErr'] = "Błędne Imię. Imię może składać się tylko ze znaków alfanumerycznych.";
	}
	else
	{
		$poprawnyFormularz = 1;
	}

	//sprawdź nick
	if(isset($_POST['nick']))
	{
		$nick = $_POST['nick'];
		if(ctype_alnum($nick) === false)
		{
			$poprawnyFormularz = 0;
			$_SESSION['nickErr'] = "Błędny nick!";
		}
		else
		{
			$poprawnyFormularz = 1;
		}
	}

	//sprawdź adres e-mail
	if(isset($_POST['email']))
	{
		$email = $_POST['email'];
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
		{
			$poprawnyFormularz = 0;
			$_SESSION['emailErr'] = "Niepoprawny adres e-mail!";
		}
		else
		{
			$poprawnyFormularz = 1;
		}
	}

	//sprawdź nr telefonu
	if(isset($_POST['tel']))
	{
		$tel = $_POST['tel'];
		if(!preg_match('/^[0-9\+]{8,13}$/', $tel))
		{
			$poprawnyFormularz = 0;
			$_SESSION['telErr'] = "Niepoprawny numer telefonu!";
		}
		else
		{
			$poprawnyFormularz = 1;
		}
	}

	//sprawdź pierwsze hasło:
	if(isset($_POST['pas1']))
	{
		$pas1 = $_POST['pas1'];
		if(strlen($pas1) < 8)
		{
			$poprawnyFormularz = 0;
			$_SESSION['pasErr'] = "Hasło musi składać się z min 8 znaków";
		}
		else
		{
			$poprawnyFormularz = 1;
		}
	}
		$haslo_hash = password_hash($pas1, PASSWORD_DEFAULT);

	//sprawdź czy podane hasła są takie same
	if(isset($_POST['pas2']))
	{
		$pas2 = $_POST['pas2'];
		if($pas1 != $pas2)
		{
			$poprawnyFormularz = 0;
			$_SESSION['pas2Err'] = "Podane hasła są różne!";
		}
		else
		{
			$poprawnyFormularz = 1;
		}
	}


	require_once("connect.php");
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$polaczenie = new mysqli($host, $db_user, $db_pas, $db_name);
		if($polaczenie->connect_errno != 0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			//czy email się nie powtarza
			$sprawdzEmail = $polaczenie->query("SELECT id FROM users WHERE email='$email'");
			$sprawdzTel =  $polaczenie->query("SELECT id FROM users WHERE tel='$tel'");
			if(!$sprawdzEmail)
			{
				throw new Exception($sprawdzEmail->error);
			}
			else if(!$sprawdzTel)
			{
				throw new Exception($sprawdzTel->error);
			}
			else
			{
				$emailNum = $sprawdzEmail->num_rows;
				$telNum = $sprawdzTel->num_rows;
				if($emailNum > 0)
				{
					$poprawnyFormularz = 0;
					$_SESSION['emailErr'] = "Podany adres Email istnieje w bazie";
				}
				elseif($telNum > 0)
				{
					$poprawnyFormularz = 0;
					$_SESSION['telErr'] = "Podany telefon istnieje w bazie";
				}
				
				if($poprawnyFormularz === 1)
				{
					if($polaczenie->query("INSERT INTO users VALUES(NULL, '$imie', '$nick', '$email', '$tel', '$haslo_hash')"))
					{
						$poprawnyFormularz = 1;
						header("Location: index.php");
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}
			}
		}
	}
	catch(Exception $exc)
	{
		echo "błąd serwera, przepraszamy za niedogodności i prosimy o rejestrację w innym terminie <br/>";
		echo "Info develop: <br/>".$exc;
	}
	$polaczenie->close();
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
input[type=text], input[type=password], input[type=tel]{
	border: none;
	height: 40px;
	width: 240px;
    border-bottom: 2px solid #555;
}

input[type=text]:focus, input[type=password]:focus, input[type=tel]:focus {
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
	<h1>Rejestracja</h1>
	<h4>Aby uzyskać możliwość rezerwacji terminu proszę o rejestrację</h4>
	<form action="#" method="POST">
	<img src="icons/avatar.png"/><input type="text" name="imie" placeholder="Podaj swóje IMIE" required="" /><br/><br/> 
	<?php
		if(isset($_SESSION['imieErr']))
		{
			echo '<span style="color:red;">* '. $_SESSION['imieErr'] .'</span><br/><br/>';
			unset($_SESSION['imieErr']); 
		}
	?>
	<img src="icons/avatar.png"/><input type="text" name="nick" placeholder="Wymyśl sobie NICK" required="" /> <br/><br/>
	<?php
		if(isset($_SESSION['nickErr']))
		{
			echo '<span style="color:red;">* '. $_SESSION['imieErr'] .'</span><br/><br/>';
			unset($_SESSION['nickErr']); 
		}
	?>
	<img src="icons/envelope.png"/><input type="text" name="email" placeholder="Podaj swój E-MAIL" required="" /> <br/><br/>
	<?php
		if(isset($_SESSION['emailErr']))
		{
			echo '<span style="color:red;">* '. $_SESSION['emailErr'] .'</span><br/><br/>';
			unset($_SESSION['emailErr']); 
		}
	?>
	<img src="icons/circle.png"/><input type="tel" name="tel" placeholder="Podaj swój NUMER TELEFONU" required="" /> <br/><br/>
	<?php
		if(isset($_SESSION['telErr']))
		{
			echo '<span style="color:red;">* '. $_SESSION['telimieErr'] .'</span><br/><br/>';
			unset($_SESSION['telErr']); 
		}
	?>
	<img src="icons/key.png"/><input type="password" name="pas1" placeholder="Podaj swoje HASŁO" required="" /> <br/><br/>
	<?php
		if(isset($_SESSION['pasErr']))
		{
			echo '<span style="color:red;">* '. $_SESSION['pasErr'] .'</span><br/><br/>';
			unset($_SESSION['pasErr']); 
		}
	?>
	<img src="icons/key.png"/><input type="password" name="pas2" placeholder="Powtórz swoje HASŁO" required="" /> <br/><br/>
	<?php
		if(isset($_SESSION['pas2Err']))
		{
			echo '<span style="color:red;">* '. $_SESSION['pas2Err'] .'</span><br/><br/>';
			unset($_SESSION['pas2Err']); 
		}
	?>
	<input type="Submit" name="rej" value="Dopisz mnie!"/> <br/><br/>
	</form>
</div>
<div class="rejestracja">
	<h3>Rozmyśłiłeś się? </h3>
	<form action="index.php">
	<input type="submit" name="zaRej" value="Powrót"/> <br/><br/>
	</form>
</div>




</body>

</html> 