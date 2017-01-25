<?php
// API to talk with the mobile App
require_once "lib/connectDB.php";

// Check if key is set
if(isset($_GET["key"]))
{
	$key = $_GET["key"];
	
	$UserTiedToKey = keyValid($key);

	// Hard-Core security stuff no touch!
	if($UserTiedToKey)
	{
		// ***************************** Echo settings for the app *************//
		if(isset($_GET["status"]))
		{
			// Ask for the users currently logged to be at home
			$sth = $database -> prepare("SELECT UserID, UserName, UserActive, UserAtHome, UserLastLogin FROM table_of_users WHERE UserID = $UserTiedToKey");
			$sth -> execute();
			$user = $sth->fetch(PDO::FETCH_ASSOC);
			
			// Wakeup
			$alarm = shell_exec('/usr/bin/python /var/www/lib/python/setAlarm.py --time');
			$alarm = preg_replace( "/\r|\n/", "", $alarm);
			
			if($alarm != "--:--")
			{
				$setHour = intval (substr($alarm, 0, 2));
				$setMin = intval (substr($alarm, 3));
				
				// Edit out the 45min wakeup time
				if($setMin < 15){$setMin = $setMin + 45;}
				elseif($setMin >= 15){
					$setMin = $setMin+45-60;
					if ($setHour < 23) $setHour = $setHour + 1;
					else $setHour = 0;
				}
				
				// 9:0 --> 09:00
				$wakeupTime = str_pad($setHour, 2, "0", STR_PAD_LEFT).":".str_pad($setMin, 2, "0", STR_PAD_LEFT);
				
				$wakeupInfo = "Herätys asetettu $wakeupTime, valaistus alkaa kirkastumaan $alarm.";
			}
			
			// Ask for the list of presets
			$sth = $database -> prepare("SELECT * FROM table_of_presets");
			$sth -> execute();
			$presets = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			$xml = "<settings>\r\n";
			
			$xml .= "	<server>\r\n";
			$xml .= "		<ServerQuery>".$_SERVER['REQUEST_TIME']."</ServerQuery>\r\n";
			$xml .= "		<ServerAddress>".$_SERVER['SERVER_ADDR']."</ServerAddress>\r\n";
			$xml .= "		<ServerName>".$_SERVER['SERVER_NAME']."</ServerName>\r\n";
			$xml .= "	</server>\r\n";
			
			$xml .= "	<alarm>\r\n";
			$xml .= "		<alarmSet>$wakeupTime</alarmSet>\r\n";
			$xml .= "		<alarmStart>$alarm</alarmStart>\r\n";
			$xml .= "	</alarm>\r\n";
			
			$xml .= "	<user>\r\n";
			$xml .= "		<userId>".$user["UserID"]."</userId>\r\n";
			$xml .= "		<userName>".$user["UserName"]."</userName>\r\n";
			$xml .= "		<userActive>".$user["UserActive"]."</userActive>\r\n";
			$xml .= "		<UserAtHome>".$user["UserAtHome"]."</UserAtHome>\r\n";
			$xml .= "		<UserLastLogin>".$user["UserLastLogin"]."</UserLastLogin>\r\n";
			$xml .= "		<UserIp>".$_SERVER['REMOTE_ADDR']."</UserIp>\r\n";
			$xml .= "	</user>\r\n";
			
			$xml .= "	<presets>\r\n";
			
			foreach($presets as $preset)
			{
				$xml .= "		<preset>[".$preset["preset_ID"]."]".$preset["preset_Name"]."</preset>\r\n";
			}
			$xml .= "	</presets>\r\n";
			
			$xml .= "</settings>";
			
			
			echo $xml;
		}
		
		// ***************************** Set wakeup ****************************//
		if(isset($_GET["setAlarm"]))
		{
			if(isset($_GET["time"]))
			{
				// Set alarm
				$str = preg_replace('/[^0-9.]+/', '', $_GET["time"]);
				
				if(strlen($str) === 3)
				{
					echo $str;
					$minute = substr($str, 1);
					$hour = "0".substr($str, 0, 1);
				}
				elseif (strlen($str) === 4)
				{
					$minute = substr($str, 2);
					$hour = substr($str, 0, 2);
				}
				
				require "lib/cron.php";
				$wakeupTime = $hour.":".$minute;
				setWakeup ($wakeupTime);
				
				echo "Alarm is set to: ".$wakeupTime;
			}
			else
			{
				// Remove wakeup
				if(isset($_GET["clear"]))
				{
					$command = "/usr/bin/python /var/www/lib/python/setAlarm.py --remove";
					echo shell_exec($command);
				}
				else{
					//No time inputted
					echo "no time given";
				}
			}
		}
		
		if(!(empty($_GET["userStatus"])))
		{
			$userStatus = $_GET["userStatus"];
			
			// Ask for the users currently logged to be at home
			$sth = $database -> prepare("SELECT UserName, UserID FROM table_of_users WHERE UserAtHome = 1");
			$sth -> execute();
			$usersAtHome = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			switch ($userStatus)
			{
				case "home":
					
					// If nobody is home
					if(count($usersAtHome) === 0)
					{
						
						// Put some lights on
						$command = "/usr/bin/python /var/www/lib/python/autolight.py --enable";
						shell_exec($command);
						
						// Restore wakeup
						shell_exec ("crontab /tmp/crontab.txt");
						$cronjob = shell_exec ("crontab -l");
						
						echo "Lights ON and wakeup restored";
					}
					else
					{	
						echo "Logged in";
					}
					
					$ustatus = "1";
					break;
					
				case "away":		
					// If other people are not around, kill the lights
					if(count($usersAtHome) <= 1)
					{
						// Remove wakeup
						shell_exec ("crontab -l > /tmp/crontab.txt");
						shell_exec ("crontab -r");
						
						// Kill the lights
						$command = "python /var/www/lib/python/off.py";
						shell_exec ($command);
						
						echo "Lights OFF and wakeup removed";
						
						// Set the plant lights
						//$command = "python /var/www/lib/python/setDevice.py 4 FF00FF 000000";
						//shell_exec ($command);
					}
					else
					{
						echo "Logged out";
					}
					$ustatus = "0";
					break;
					
				default:
					break;
			}
			
			if($ustatus === "1" || $ustatus === "0")
			{
				$sth = $database -> prepare("UPDATE table_of_users SET UserAtHome = :ustatus WHERE UserID = :uid");
				$sth -> bindValue(":uid", $UserTiedToKey);
				$sth -> bindValue(":ustatus", $ustatus);
				$sth -> execute();
			}
		}
		
		// ************************ Light control presets ***********************//
		if(isset($_GET["mode"]))
		{
			$ustatus = "";
			
			switch($_GET["mode"])
			{
				// Turn lights off
				case "off":
					$command = "/usr/bin/python /var/www/lib/python/autolight.py --disable";
					shell_exec($command);
					$command = "/usr/bin/python /var/www/lib/python/off.py";
					shell_exec($command);
					echo "Lights turned off";
					break;
				
				// Automagic light control (ALC)
				case "auto":
					$command = "/usr/bin/python /var/www/lib/python/autolight.py --enable";
					shell_exec($command);
					echo "Automatic Light Control set";
					$ustatus = "1";
					break;

				// Set preset from database
				case "preset":
					$preset = $_GET["preset"];
					if(empty($preset))
					{
						echo "Preset ID missing";
					}
					else
					{
						$command = "/usr/bin/python /var/www/lib/python/autolight.py --disable";
						shell_exec($command);
						
						$command = "/usr/bin/python /var/www/lib/python/setPresetFromDB.py $preset";
						shell_exec($command);
						
						// Hae presetin nimi tietokannasta
						$sth = $database -> prepare("SELECT preset_Name FROM table_of_presets WHERE preset_ID = :id");
						$sth -> bindValue(":id", $preset);
						$sth -> execute();

						$preset = $sth->fetch(PDO::FETCH_ASSOC);
						echo "Set to: ".$preset["preset_Name"];
						$ustatus = "1";
					}
					break;
			}
			
			if($ustatus === "1" || $ustatus === "0")
			{
				$sth = $database -> prepare("UPDATE table_of_users SET UserAtHome = :ustatus WHERE UserID = :uid");
				$sth -> bindValue(":uid", $UserTiedToKey);
				$sth -> bindValue(":ustatus", $ustatus);
				$sth -> execute();
			}
		}
	}
	else
	{
		echo "API-key not valid.";
	}
}



// Check if key is valid
function keyValid($key)
{
	require "lib/connectDB.php";
	
	// Ask for the user info
	$sth = $database -> prepare("SELECT UserID, UserAPIKey, UserActive FROM table_of_users WHERE UserAPIKey = :key");
	$sth -> bindValue(":key", $key);
	$sth -> execute();

	$user = $sth->fetch(PDO::FETCH_ASSOC);
	
	if(empty($user))
	{
		// Avain väärin
		echo "API-Key not valid";
		exit();
		return false;
	}
	else
	{
		// Käyttäjä löytyy
		if(strcmp($user["UserAPIKey"], $key) === 0)
		{
			if($user["UserActive"] === "1")
			{
				return $user["UserID"];
			}
			else 
			{	// käyttäjä ei ole aktiivinen
				echo "Access denied";
				exit();
			}
		}
		else
		{
			// Tunnus ei kelvollinen (capsit?)
			echo "API-Key not valid";
			exit();
			return false;
		}
	}
}



// Return true or false whether the user is at home
function isUserAtHome($userID)
{
	require "lib/connectDB.php";
	
	// Ask for the user info
	$sth = $database -> prepare("SELECT UserAtHome FROM table_of_users WHERE UserID = :uid");
	$sth -> bindValue(":uid", $userID);
	$sth -> execute();

	$userStatus = $sth->fetch(PDO::FETCH_ASSOC);
	
	if($userStatus["UserAtHome"] === "1")
	{
		return true;
	}
	else 
	{	
		return false;
	}
}
?>