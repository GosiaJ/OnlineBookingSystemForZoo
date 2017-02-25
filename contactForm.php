<?php
session_start();
//require("klasy.php");

$validated = 1;
if(isset($_POST['nameForm']))
{
	//reciever
	$receiver = 'm.janeczek93@gmail.com';

	//Form data
	$senderName = $_POST['nameForm'];
	$senderMail = $_POST['mailForm'];
	$senderMsg = $_POST['textForm'];

	//check if name is alphanumeric
	if(!ctype_alnum($senderName))
	{
		$validated = 0;
		$_SESSION['nameErr'] = "* Podano niedozwolone dane osobowe";
	}
	else
	{
		$validated = 1;
	}

	//check if e-mail addres is ok
	if(!filter_var($senderMail, FILTER_VALIDATE_EMAIL))
	{
		$validated = 0;
		$_SESSION['mailErr'] = "* Podano błędny adres e-mail";
	}
	else
	{
		$validated = 1;
	}

	//check if textarea is full
	if(empty($senderMsg))
	{
		$validated = 0;
		$_SESSION['msgErr'] = "* Nie wpisano trści woadmości";
	}
	else
	{
		$validated = 1;
	}

	//correct validation
	if($validated = 1)
	{
		$message = "Treść wiadomości: <br/>".$_POST['textForm']."<br/> Wysłał:". $_POST['nameForm']."<br/>e-mail:". $_POST['mailForm']; 
	    // zmienna $header zawiera przede wszystkim adres zwrotny 
	    $header = "From:". $_POST['nameForm']." <". $_POST['mailForm'].">"; 
	    // funkcja mail() za pomocą której wiadomość zostanie wysłana 
	    if(!mail($receiver,"Wiadomosc ze strony WWW", $message, $header))
	    {
	    	echo '<div align="center"><strong>Nie udało się wysłać wiadomości, prosimy spróbować ponowanie w innym terminie!</strong></div>';
	    }
	    else
	    {
	    // wyświetlenie komunikatu w przypadku powodzenia 
	    echo '<div align="center"><strong>Wiadomość została wysłana poprawnie!</strong></div>'; 
	    }
	} 
	// lub w przypadku nie wypełnienia formularza do końca 
	else 
	{
		echo '<span style="color: #FF0000; text-align: center;">Wypełnij wszystkie pola formularza!</span>'; 
	}
}




?>

<!DOCTYPE html>
<html lang="pl">
<head>

<title>System Internetowej Rezerwacji Usług</title>

<!-- Style CSS -->
<link href="style.css" rel="stylesheet" type="text/css" media="all"/>

</head>

<body>
<div class="agileheader">
	<?php
		echo '<h1> Witaj, ' . $_SESSION['LogAs'] . '</h1>';
	?>
	 <h2>prosimy o wypełnienie formularza w celu skontaktowania się z nami, odpowiemy ASAP!</h2>
</div>

<div class="main">
	<form action="#" method="post">
	<input type="text" name="nameForm" placeholder="Imię i nazwisko" required=""/> <br/><br/>
	<?php
		if(isset($_SESSION['nameErr']))
		{
			echo '<span style="color:red;">'.$_SESSION['nameErr'].'</span>';
			unset($_SESSION['nameErr']);
		}
	?>
	<input type="text" name="mailForm" placeholder="Adres e-mail" required=""/> <br/><br/>
	<?php
		if(isset($_SESSION['mailErr']))
		{
			echo '<span style="color:red;">'.$_SESSION['mailErr'].'</span>';
			unset($_SESSION['mailErr']);
		}
	?>
	<textarea name="textForm" cols="30" rows="10" placeholder="Treść wiadomości..." required=""></textarea><br/><br/>
	<?php
		if(isset($_SESSION['msgErr']))
		{
			echo '<span style="color:red;">'.$_SESSION['msgErr'].'</span>';
			unset($_SESSION['msgErr']);
		}
	?>
	<input type="submit" name="zaRej" value="Wyślij"/> <br/><br/>
	</form>
</div>

<div class="logOut">

	<form action="main.php">
	<input type="submit" name="zaRej" value="Powrót"/> <br/><br/>
	</form>

	<form action="logout.php">
	<input type="submit" name="zaRej" value="Wyloguj się"/> <br/><br/>
	</form>

</div>


</body>

</html> 