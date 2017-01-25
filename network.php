<?php require_once "lib/session.php"; ?>
<?php

// Function to read signal levels
include "lib/deviceRSSI.php";

// Database connection to read device list
include "lib/connectDB.php";

// Ask the list of devices
$sth = $database -> prepare("SELECT * FROM table_of_devices");
$sth -> execute();

$devices = $sth->fetchAll(PDO::FETCH_ASSOC);

$titletext="Verkko";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>
</head>

<body>

<?php include "lib/navbar.php"; ?>

<div class="container">
	<?php include "lib/pageTitle.php"; ?>
	
	<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Aktiiviset valo-ohjaimet</div>
  <div class="panel-body">
    <p>Lista verkkoon liitetyistä valo-ohjaimista ja laitteista. </p>


  <!-- Table -->
  <table class="table" style="margin-bottom:0px;">
    <tr>
		<th>Ohjain</th>
		<th>Sijainti</th>
		<th><span class="glyphicon glyphicon-ok" title="Laitteen tila" aria-hidden="true"></span></th>
		<th width="50%">Signaalitaso</th>
	</tr>
	<?php
	foreach($devices as $device)
	{
		if($device['DeviceActive'] == "1")
		{
			$procent = getRSSI($device['DeviceMAC']);
			$name = $device['DeviceName'];
			$location = $device['DeviceLocation'];
			
			echo "<tr>
					<td>$name</td>
					<td>$location</td>
					<td><span class=\"glyphicon glyphicon-";
					
					if($procent === "") echo "warning-sign\" title=\"Laite ei löydetty\"";
					elseif($procent > 1) echo "ok\" title=\"Laite vastaa pyyntöihin\"";
					
					echo " aria-hidden=\"true\"></span></td>
					<td>
					<div class=\"progress\" style=\"margin-bottom: 0px;\">
				  <div class=\"progress-bar ";
				  
				  if($procent < 15) echo "progress-bar-danger";
				  elseif ($procent >= 15 && $procent < 25) echo "progress-bar-warning";
				  elseif ($procent >= 25 && $procent < 90) echo "";
				  elseif ($procent >= 90) echo "progress-bar-success";
				  
				  echo "\" id=\"process_bar\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:$procent%;\">$procent%</div>
				</div>
					</td>
				</tr>";
		}
	}
	?>
  </table>
    </div>
</div>
	</div>
	


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="lib/js/bootstrap.min.js"></script>
	
</body>
</html>
