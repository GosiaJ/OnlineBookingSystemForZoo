<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();

try
	{
		if(!require("calendar_Classes.php"))
			throw new Exception("Błąd odczytu pliku");
	}
	catch(Exception $e)
	{
		echo $e;
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
	 <h2> zapoznaj się z wydarzeniami, które palnujemy w naszym Zoo</h2>
</div>
<div class="main">
 <!-- KALENDARZ Z WYDARZENIAMI! admin ma możliwość dodawania nowych wydarzeń.-->
	<?php

		if($_SESSION['LogAs'] == 'Admin')
		{
			$but = '<form action="#" method="post">';
			$but .= '<input type="submit" name="submit" value="Dodaj wydarzenie"><br/><br/>';
			$but .= '</form>';

			if(!isset($_POST['submit']))
			{
				echo $but;
			}
			else
			{
				$dodajWydarzenie = new Form;
				$dodajWydarzenie->showForm();
			}
		}
		

		$form = new ChooseMonth();
		$newMon = $form->form();
		$newYear = $form->takeYear();

		$calendar = new Calendar($newMon,$newYear);
		$calendar->show();

	?>

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