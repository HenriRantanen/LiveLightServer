<?php require_once "lib/session.php"; ?>
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
	
	$wakeupInfo = "Herätys asetettu $wakeupTime, valaistus alkaa kirkastumaan $alarm.";
}
else
{
	$wakeupInfo ="Herätystä ei ole asetettu.";
}
	
$titletext="Herätys";
?>


<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>
	<script src="main.js"></script>
</head>

<body>

<?php $tab=3; include "lib/navbar.php"; ?>

<form method="get" action="lib/cron.php">

<div class="container">
<?php include "lib/pageTitle.php"; ?>

	<div class="row clearfix" style="margin-bottom:20px;">
		<div class="col-md-12 column">

			<div class="panel panel-default box-shadow">
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Aseta uusi herätys</h3>
				</div>
				<div class="panel-body">
					<input type="time" name="wakeupTime" class="form-control wakeupTime" style="margin-bottom: 20px;" value="<?php echo $wakeupTime; ?>">
					<div class="hidden-xs">
						<button type="submit" name="button" value="set" class="btn btn-default pull-left" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Aseta herätys</button>
						<div class="btn-group pull-right" role="group">
							<button type="submit" name="button" value="clear" class="btn btn-default "><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Poista herätys</button>
							<button type="submit" name="button" value="off" class="btn btn-default "><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Sammuta valot</button>
						</div>
					</div>
					
					<div class="hidden-md hidden-lg hidden-sm">

					<button type="submit" name="button" value="set" class="btn btn-default " style="margin-bottom: 20px; width: 100%;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Aseta herätys</button>

						<div class="btn-group btn-group-justified" role="group">
							<div class="btn-group" role="group">
								<button type="submit" name="button" value="clear" class="btn btn-default"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Poista herätys</button>
							</div>
							<div class="btn-group" role="group">						
								<button type="submit" name="button" value="off" class="btn btn-default"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Sammuta valot</button>
							</div>
						</div>
						
					</div>
					

				</div>
			</div>
			
		</div>
	</div>
	<div class="row clearfix" style="margin-top:20px;">
		<div class="col-md-12 column">
		<div class="panel panel-default box-shadow">
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Tila</h3>
				</div>
				<div class="panel-body">
				<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?php echo $wakeupInfo; ?>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="lib/js/bootstrap.min.js"></script>
	
</body>
</html>
