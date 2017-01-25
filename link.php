<?php
require_once "lib/connectDB.php";
require_once "lib/timeOfDay.php";

$user = $_GET['user'];
$status = $_GET['status'];

$titletext = "Kotona/Poissa";

// Ask for the users currently logged to be at home
$sth = $database -> prepare("SELECT UserName, UserID FROM table_of_users WHERE UserAtHome = 1");
$sth -> execute();
$usersAtHome = $sth->fetchAll(PDO::FETCH_ASSOC);


// Ask for the user info
$sth = $database -> prepare("SELECT UserAtHome FROM table_of_users WHERE UserID = :uid");
$sth -> bindValue(":uid", $user);
$sth -> execute();

$userStatus = $sth->fetch(PDO::FETCH_ASSOC);

if($status === "s")
{
	if($userStatus["UserAtHome"] === "1")
	{
		$status = "away";
	}
	else
	{
		$status = "home";
	}
}

$notification = "";

if($user == "2" || $user == "3")
{
	switch ($status)
	{
		case "away":
			// If user is marked to be at home
			if($userStatus["UserAtHome"] === "1")
			{
				// If other people are not around, kill the lights
				if(count($usersAtHome) === 1)
				{
					// Remove wakeup
					shell_exec ("crontab -l > /tmp/crontab.txt");
					shell_exec ("crontab -r");
					$notification .= "Herätys poistettu";

					// Kill the lights
					$command = "python /var/www/lib/python/off.py";
					shell_exec ($command);
					
					// Set the plant lights
					//$command = "python /var/www/lib/python/setDevice.py 4 FF00FF 000000";
					//shell_exec ($command);
					$notification .= " ja valot sammutettu.";
				}
				else
				{
					$notification = "Valaistustilaa ei muutettu.";
				}
				$ustatus = "0";
				$titletext = "Näkemiin!";
			}
			else
			{
				// User has already signed off
				$notification = "Olet jo kirjautunut ulos.";
				$ustatus = "0";
			}
			
			break;
			
		case "home":
			// If user is marked to be away
			if($userStatus["UserAtHome"] === "0")
			{
				// If nobody is home
				if(count($usersAtHome) === 0)
				{
					// Restore wakeup
					shell_exec ("crontab /tmp/crontab.txt");
					$cronjob = shell_exec ("crontab -l");
					
					$command = "/usr/bin/python /var/www/lib/python/autolight.py --enable";
					shell_exec($command);
					
					// Put some lights on
					//$theme = simulateDay();
					
					//$notification .= "Valaistusteema: ".strtolower($theme);
					//$theme = "<p><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>  Valaistusteema: ".$theme."</p>";
				}
				else
				{	
					//$cronjob = shell_exec ("crontab -l");
					$notification .= " ";
				}
				
				$ustatus = "1";
			}
			else
			{
				// User is already at home
				$notification = "Olet jo kirjautunut sisään.";
				$ustatus = "1";
			}

			break;
	}
	
	if($ustatus === "1" || $ustatus === "0")
	{
		$sth = $database -> prepare("UPDATE table_of_users SET UserAtHome = :ustatus WHERE UserID = :uid");
		$sth -> bindValue(":uid", $user);
		$sth -> bindValue(":ustatus", $ustatus);
		$sth -> execute();
	}
}

	// Katso onko herätys asetettu
	$alert = Isset($cronjob);
	
	// Hajota välilyöntien kohdalta taulukkoon
	$kissa = explode(" ", $cronjob);
	
	$setMinute = str_pad($kissa[0], 2, "0", STR_PAD_LEFT);
	$setHour = $kissa[1];
	
	if($alert)
	{
		//Hertäys asetettu
		$wakeupInfo = "<span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span> Herätys alkaa $setHour:$setMinute";
		$notification .= ", herätys $setHour:$setMinute";
	}
	else
	{
		// Ei herätystä
		if($status == "away")
		{
			$wakeupInfo = "<span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span> Herätys on poistettu käytöstä.";
		}
		else
		{
			$wakeupInfo = "<span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span> Herätystä ei ole asetettu.";
		}
	}

	// Ask for the users currently logged to be at home
	$sth = $database -> prepare("SELECT UserName, UserID FROM table_of_users WHERE UserAtHome = 1");
	$sth -> execute();
	$usersAtHome = $sth->fetchAll(PDO::FETCH_ASSOC);

	if(count($usersAtHome) != 0)
	{
		$athome = "<span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\"></span> Kotona: ";
		foreach($usersAtHome as $userAtHome)
		{
			$athome .= $userAtHome["UserName"]." ";
		}
	}
	else
	{
		$athome = "<span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\"></span> Ketään ei kotona.";
	}

	if($_GET['mode'] != "background")
	{
		$mode = $_GET['mode'];
		echo "	
		<!DOCTYPE html>
		<html>
		<head>
		<title>$titletext | LiveLight Lion</title>";
		include "lib/head.php";
		
		echo "<script src=\"main.js\"></script>
		</head>

	<body>";

	$tab=3; include "lib/navbar.php";
	echo "
	<form method=\"post\" action=\"lib/cron.php\">

	<div class=\"container\">";
	include "lib/pageTitle.php"; 
	echo "
		<div class=\"row clearfix\" style=\"margin-top:20px;\">
			<div class=\"col-md-12 column\">
			<div class=\"panel panel-default box-shadow\">
					<div class=\"panel-heading\">
						<h3 class=\"panel-title\"><span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span> Tila</h3>
					</div>
					<div class=\"panel-body\">
					<p>$wakeupInfo</p>
					<p>$athome</p>
					$theme
					</div>
				</div>
			</div>
		</div>
		<a class=\"btn btn-default btn-lg\" onclick=\"window.close()\" type=\"button\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Sulje</a>

	</div>
	</form>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>
	<script src=\"lib/js/bootstrap.min.js\"></script>
		
	</body>
	</html>";
}
else
{
	echo $notification;
}
?>
