<?php	
	session_start();
	
	/*function timer($str, $start)
	{
		$usec = round(microtime(true) * 1000) - $start;
		
		return "<br>".$str." ".$usec." ms<br>";
	}
	
	$start = round(microtime(true) * 1000);
	*/
	
	if(isset($_SESSION["UserID"]) || $_GET["key"] === "mJqxsmuvrNmWc")
	{
		// Database connection
		require_once "connectDB.php";
		require_once "eventFunctions.php";
	
		$userID = $_SESSION["UserID"];
		
		if(isset($_GET["key"]) ? $_GET["key"] : '' === "mJqxsmuvrNmWc") $userID = 2;
			
		// Ask for the user info
		$sth = $database -> prepare("SELECT UserActive FROM table_of_users WHERE UserID = $userID");
		$sth -> execute();

		$user = $sth->fetch(PDO::FETCH_ASSOC);
		
		if($user["UserActive"] === "1")
		{
			// Auto mode off
			$command = "/usr/bin/python /var/www/lib/python/autolight.py --disable";
			shell_exec($command);
		
			$color1 = strtoupper($_GET["color1"]);
			$color2 = strtoupper($_GET["color2"]);
			$color3 = strtoupper($_GET["color3"]);
			$color4 = strtoupper($_GET["color4"]);
			$color5 = strtoupper($_GET["color5"]);
			$color6 = strtoupper($_GET["color6"]);
			$color7 = strtoupper($_GET["color7"]);
			$color8 = strtoupper($_GET["color8"]);
			
			echo "<script>window.close();</script>";
			
			$args = "-c; ";
			$script = "python /var/www/lib/python/setDevice.py ";
			
			//echo timer("Aloita komennon myllytys", $start);
			$command = "$script 1 $color1$color2 -p $args";
			$command .= "$script 2 $color3$color4 -p $args";
			$command .= "$script 3 $color5$color6 -p $args";
			$command .= "$script 4 $color7$color8 -p $args";
			
			//echo timer("Paketti kasattu", $start);
			
			$command .= "python /var/www/lib/python/sendPacket.py";
			system ($command);			
			//echo timer("paketti lähetetty", $start);
			//echo $command;
			//$command = "python /var/www/lib/python/setPreset.py $color1 $color2 $color3 $color4 $color5 $color6 $color7 $color8";
			
			
			saveSetting(1, $color1);
			saveSetting(2, $color2);
			saveSetting(3, $color3);
			saveSetting(4, $color5);
			saveSetting(5, $color6);
			saveSetting(6, $color7);
			
			//echo timer("tiedot tallennettu", $start);
			
			//echo timer("Skriptin lopetus", $start);
		}
		else
		{
			$login = false;
		}
	}
	else
	{
		$login = false;
	}
	
function saveSetting ($lamp, $color)
{
	require "connectDB.php";
	
		// Write the data to the database
		$sth = $database -> prepare("UPDATE table_of_lamps SET LampColor = ? WHERE LampID = ?");
		$sth -> bindParam(1, $color);
		$sth -> bindParam(2, $lamp);
		$sth -> execute();
}
?>
