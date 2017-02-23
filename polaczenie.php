<?php
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
		$sprawdzEmail = $polaczenie->query("SELECT id FROM Users WHERE email='$email'");
		if(!$sprawdzEmail)
		{
			throw new Exception($sprawdzEmail->error);
		}
		else
		{
			$emailNum = $sprawdzEmail->num_row;
			if($emailNum > 0)
			{
				$
			}
		}


		$przeslijDane = $polaczenie->query("INSERT INTO Users VALUES (NULL, $name, $nick, $emali, $tel, $haslo_hash)");
		if(!$przeslijDane)
		{
			throw new Exception($przeslijDane->error);
		}
	}
}
catch(Exception $exc)
{
	echo "błąd serwera, przepraszamy za niedogodności i prosimy o rejestrację w innm terminie";
}

?>