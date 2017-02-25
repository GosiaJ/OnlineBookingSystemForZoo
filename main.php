<?php
session_start();
//setcookie("LogAs", 1, time()+3600);
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
	 <h2> w Systemie Internetowej Rezerwacji Usług dla Zoo</h2>
</div>
<!-- 
main box z polami wyboru:
rezerwacja terminów wycieczek i przewodników
spis wydarzeń - możliwość edycji przez admina
plan zoo - również do pobrania w wersji .jpg i możliwośc jego edycji przez admina
-->
<div class="main">
	<div class="rezerwacje">
		<img src="icons/rezerwacja.png"/>
		<h2>Rezerwacje</h2>
	</div>
	<a href="events.php" style="decoration:none;">
	<div class="wydarzenia">
		<img src="icons/calendar.png"/>
		<h2>Wydarzenia</h2>
	</div>
	</a>
	<div class="planZoo">
		<img src="icons/map.png"/>
		<h2>Plan ZOO</h2>
	</div>
	<div class="clear"></div>
</div>
<!-- 
formularz kontaktowy
newsletter
 -->
<div class="smallMain">
	<a href="contactForm.php" style="decoration:none;">
	 	<div class="contactForm">
	 		<img src="icons/speech-bubble.png"/>
			<h4>Masz pytania?</h4>
			<h3>Skontaktuj się z nami!</h3>
	 	</div>
 	</a>
 	<a href="newsletter.php" style="decoration:none;">
	 	<div class="newsletter">
	 	<img src="icons/newsletter.png"/>
			<h4>Chcesz być na bieżąco?</h4>
			<h3>Dodaj się do newslettera!</h3>
	 	</div>
 	</a>
	<div class="clear"></div>
</div>


<div class="logOut">
	<form action="logout.php">
	
	<input type="submit" name="zaRej" value="Wyloguj się"/> <br/><br/>
	</form>
</div>


</body>

</html> 