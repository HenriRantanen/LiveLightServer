<?php $titletext="Valaisimet";?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>
	
	<script src="main.js"></script>
</head>

<body>
<?php $tab=1; include "lib/navbar.php"; ?>
<div class="container">

<?php
require_once "lib/connectDB.php";

// Ask for the user info
	$sth = $database -> prepare("SELECT * FROM table_of_lamps");
	$sth -> execute();

	$lamps = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	echo "<br><br>";
	
	foreach($lamps as $lamp)
	{
		$device = parseDevice($lamp["LampChMap"]);
		
		$sth = $database -> prepare("SELECT * FROM table_of_devices WHERE DeviceID = $device");
		$sth -> execute();

		$device = $sth->fetch(PDO::FETCH_ASSOC);
		
		echo "<div class=\"col-md-4\">
		<div class=\"panel panel-default\">
				<div class=\"panel-heading\">
					<h3 class=\"panel-title\">
						<span class=\"glyphicon glyphicon-dashboard\" aria-hidden=\"true\"></span> ".$lamp["LampName"]."
					</h3>
				</div>
				<div class=\"panel-body\">";
		
	
		echo "<p>";
		
		// Read the controller
		echo "Kytketty ohjaimeen: ".parseDevice($lamp["LampChMap"])."<br>";
		
		// Read the amount of channels
		echo "Valaisimessa ".parseChannels($lamp["LampChMap"])." ohjauskanavaa:<br>";
		
		// Read all the channel numbers
		for($i = 1; $i <= parseChannels($lamp["LampChMap"]); $i++)
		{
			echo"<div class='colour-dot' style='float:left; background-color:#".$device["DeviceCH$i"].";'></div>";
		}
		
		echo "<br><p>";
		// Read all the channel numbers
		for($i = 1; $i <= parseChannels($lamp["LampChMap"]); $i++)
		{
			echo "Ohjain ".parseDevice($lamp["LampChMap"]).", kanava ".parseDeviceChannel($i, $lamp["LampChMap"])."<br>";
		}
		echo "</p></div>
				<div class=\"panel-footer\">
					
				</div>
				</div></div>";
	}

?>

</div>
<?php 

function parseDevice($channelMap)
{
	$start = strrpos($channelMap, "[")+1;
	$stop = strrpos($channelMap, "; ");
	
	return substr($channelMap, $start, $stop-$start);
}

function parseChannels($channelMap)
{
	$start = strrpos($channelMap, "; ")+2;
	$stop = strrpos($channelMap, "]");
	$channels = substr($channelMap, $start, $stop-$start);
	
	return substr_count($channels, ';')+1;
}

function parseDeviceChannel($index, $channelMap)
{
	$index = $index-1;
	$start = strrpos($channelMap, "; ")+2;
	$stop = strrpos($channelMap, "]");
	$channels = substr($channelMap, $start, $stop-$start);
	
	return substr($channels, ($index*2), 1);
}

?>

</body>