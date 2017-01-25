<?php require_once "../lib/session.php";
$titletext="Kotin&auml;ytt&ouml;";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="robots" content="noindex, follow">

<meta http-equiv="cache-control" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Standard Favicon -->
<link rel="icon" type="image/x-icon" href="../img/favicon.ico" />

<!-- For iPhone 4 Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../img/Icon-144.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../img/Icon-114.png">

<!-- For iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="100x100" href="../img/Icon-100.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../img/Icon-72.png">

<!-- For iPhone: -->
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="../img/Icon-60.png">
<link rel="apple-touch-icon-precomposed" href="../img/Icon-57.png">

<!-- Bootstrap core CSS -->
<link href="../lib/css/bootstrap.min.css" rel="stylesheet">
<link href="../lib/css/styles.css" rel="stylesheet">



<link href="../lib/docs/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">

<script src="../lib/docs/js/jquery.min.js"></script>


<meta http-equiv="refresh" content="180">
<script src="../main.js"></script>

<style>
html {
  height: 100%;
}
body {
    background-color: #202635;
    background-image: url(img/dashback_1080.jpg);
    background-attachment: fixed;
    background-size: cover;
    color: #ccc;
    .fill
}

.panel{
	background-color: rgba(0,0,0,0.0);
	margin-top: 15px;
    border-radius: 0;
    border: none;
}
.panel-body{
	background-color: rgba(0,0,0,0.3);
}
.panel-heading .panel-default {
	border: none;
	background-color: rgba(0,0,0,0.3); !important
}
.panel-default>.panel-heading {
	border: none;
	background-color: rgba(0,0,0,0.6);
	border-radius: 0px;
}
.sideBar{
	background-color: rgba(0,0,0,0.3);
	padding: 0px;
}
.rowLine{
	border-bottom-width: 2px;
    border-bottom-color: rgba(255,255,255,0.5);
    border-bottom-style: solid;
}
#clock{
	text-align: right;
	margin.bottom: 15px;
}
.container-fluid {
	.fill
	}

.fill
{
	min-height: 100%;
    height: 100%;
}

.snipplet{
	background-color: rgba(0,0,0,0.45);
    width: 100%;
    padding: 10px 5px 1px 15px;
    margin: 15px 0 15px 0;
}
</style>

<?php include "clock.php"; ?>

</head>

<body onload="startTime()" class="fill">

<div class="container-fluid fill">
	<div class="row rowLine">
		<div class="col-xs-3 sideBar hidden-xs">
		<h2 style="padding-left:15px;">LiveLight Lion</h2>
		</div>
		<div class="col-xs-12 col-sm-9">
		<h2 id="clock"></h2>
		</div>
	</div>
	<div class="row fill">
		<div class="col-sm-3 sideBar hidden-xs fill">
			<div class="row">
				<div class="col-xs-12">
				<?php $calendarType = "agenda"; include 'calendar.php'; ?>
				</div>
			</div>
			
		</div>
		<div class="col-xs-12 col-sm-9">
			<div class="row rowLine hidden-sm hidden-xs">
				<div class="col-xs-12">
				<h1>Kotinäyttö Dashboard</h1>
				</div>
			</div>
			<div class="row rowLine">
				<div class="col-xs-4 col-sm-4">
				<?php include 'wakeup.php'; ?>
				</div>
				<div class="col-xs-4 col-sm-4">
				<?php include 'autolight.php'; ?>
				</div>
				<div class="col-xs-4 col-sm-4">
				<?php include 'fitbitSnipplet.php'; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-4">
				<?php include 'weather.php'; ?>
				</div>
				<div class="col-xs-4 col-sm-4">
				<?php include 'homeControl.php'; ?>
				</div>
				<div class="col-xs-4 col-sm-4">
				<?php include 'fitbit.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>	

</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="../lib/js/bootstrap.min.js"></script>

<script src="../lib/docs/js/bootstrap-switch.min.js"></script>

<script>
$(function(argument) {
  $('[type="checkbox"]').bootstrapSwitch();
})
</script>
	
</body>
</html>

<?php
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
	
	$wakeupInfo = "$wakeupTime";
}
else
{
	$wakeupInfo ="Ei asetettu";
}

// Echo the wakeup info
echo "<script>document.getElementById(\"wakeupInfo\").textContent = \"$wakeupInfo\";</script>";

// Check if autolight is enabled
$autoLightStatus = exec('/usr/bin/python /var/www/lib/python/autolight.py --status');

if ($autoLightStatus === "Enabled")
{
	echo "<script>document.getElementById(\"autoLightInfo\").textContent = \"Päällä\";</script>";
}
else 
{	
	echo "<script>document.getElementById(\"autoLightInfo\").textContent = \"Pois\";</script>";
}

?>
