<?php require_once "lib/session.php";
$titletext="Kotin&auml;ytt&ouml;";

$fitbitData = explode(",", shell_exec('/usr/bin/python /var/www/lib/python/fitbit.py'));
$fitbitCaloriesLeftprocent = round($fitbitData[2]/$fitbitData[1]*100,1);
$fitbitWeightGoalprocent = round(($fitbitData[5]-$fitbitData[8])/($fitbitData[5]-$fitbitData[6])*100,1);
// 8 nykypaino
// 5 alkupaino
// 6 tavoitepaino
$fitbitFatGoalprocent = round((15-$fitbitData[9])/(15-$fitbitData[7])*100,1);


$fitBitBarColor = "success";

if($fitbitData[2]-$fitbitData[3] < 50)
{
	$fitBitBarColor = "warning";
}
if($fitbitData[2] < $fitbitData[3])
{
	$fitBitBarColor = "danger";
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>
	<meta http-equiv="refresh" content="300">
	<script src="main.js"></script>
</head>

<body>

<?php $tab=3; include "lib/navbar.php"; ?>

<div class="container">

<div class="row">
			<div class="col-xs-6 col-sm-4">
			
				<!-- Sääwidget -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> S&auml;&auml;tiedot
						</h3></div>
					<div class="panel-body">
						<a href="http://www.accuweather.com/fi/fi/helsinki/133328/weather-forecast/133328" class="aw-widget-legal"></a><div id="awcc1445373687241" class="aw-widget-current"  data-locationkey="" data-unit="c" data-language="fi" data-useip="true" data-uid="awcc1445373687241"></div><script type="text/javascript" src="http://oap.accuweather.com/launch.js"></script>
					</div>
				</div>
			</div>

			<div class="col-xs-6 col-sm-4">
			
			<!-- Ravinto -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-apple" aria-hidden="true"></span> FitBit
					</h3></div>
				<div class="panel-body">
					<table width="100%">
					<tbody>
					<tr>
						<th>Syöty</th>
						<th>Syötävä vielä tänään</th>
					</tr>
					<tr>
						<td><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?php echo $fitbitData[2]; ?> kcal</td>
						<td><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> <?php echo $fitbitData[1]-$fitbitData[2]; ?> kcal</td>
						</tr>
					<tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
							  <div class="progress-bar progress-bar-<?php echo $fitBitBarColor; ?>" role="progressbar" aria-valuenow="<?php echo $fitbitData[2]; ?>" aria-valuemin="0" aria-valuemax="<?php echo $fitbitData[1]; ?>" style="width: <?php echo $fitbitCaloriesLeftprocent; ?>%">
							    <span class="sr-only"><?php echo $fitbitCaloriesLeftprocent; ?>% Complete (success)</span>
							    <?php echo $fitbitCaloriesLeftprocent; ?>%
							  </div>
						</div>
					</td>
					</tr>
					<tr>
						<th colspan="2">Juotu</th>
					</tr>
					<tr>
						<td colspan="2"><span class="glyphicon glyphicon-tint" aria-hidden="true"></span> <?php echo $fitbitData[3]; ?> ml</td>
						</tr>
					<tr>
					<tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
						  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $fitbitData[3]; ?>" aria-valuemin="0" aria-valuemax="1893" style="width: <?php echo round($fitbitData[3]/1893*100); ?>%">
						    <span class="sr-only"><?php echo round($fitbitData[3]/1893*100); ?>% Complete (success)</span>
						    <?php echo round($fitbitData[3]/1893*100); ?>%
						  </div>
					</div>
					</td>
					</tr>
					<tr>
						<th>Paino</th>
						<th>Tavoite <small><?php echo $fitbitData[4]; ?></small></th>
					</tr>
					<tr>
						<td><span class="glyphicon glyphicon-scale" aria-hidden="true"></span> <?php echo round($fitbitData[8],1); ?> kg</td>
						<td><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> <?php echo round($fitbitData[6],1); ?> kg</td>
					</tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
							  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $fitbitWeightGoalprocent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $fitbitWeightGoalprocent; ?>%">
							    <span class="sr-only"><?php echo $fitbitWeightGoalprocent; ?>% Complete (success)</span>
							    <?php echo $fitbitWeightGoalprocent; ?>%
							  </div>
						</div>
					</td>
					</tr>
					<tr>
						<td>Rasva</td>
						<td>Tavoite</td>
					</tr>
					<tr>
						<td><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> <?php echo round($fitbitData[9],1); ?> %</td>
						<td><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> <?php echo round($fitbitData[7],1); ?> %</td>
					</tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
							  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $fitbitFatGoalprocent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $fitbitFatGoalprocent; ?>%">
							    <span class="sr-only"><?php echo $fitbitFatGoalprocent; ?>% Complete (success)</span>
							    <?php echo $fitbitFatGoalprocent; ?>%
							  </div>
						</div>
					</td>
					</tr>
					</tbody>
					</table>
					
					
				</div>
			</div>
			
			
			</div>
			
			<div class="col-xs-6 col-sm-4">
				<!-- Kodinohjaus -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Koti
						</h3></div>
					<div class="panel-body">
					<a href="http://192.168.0.100:8080/"> Kodi MediaCenter</a>
					<h4><span class="glyphicon glyphicon-lamp" aria-hidden="true"></span> Valaistus</h4>
						<div class="btn-group btn-group-justified" role="group">
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span id="lightsOn">P&auml;&auml;lle</span> <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu">
									<li><a href="#" onclick="setPreset('auto')">Automaatti</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="#" onclick="setPreset('daylight')">P&auml;iv&auml;nvalo</a></li>
									<li><a href="#" onclick="setPreset('night')">Y&ouml;valo</a></li>
								  </ul>
							</div>
							<div class="btn-group" role="group">
								<button type="button" onclick="setPreset('off')" class="btn btn-default"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Pois</button>
							</div>
						</div>
						
					<!-- Herätys -->
					<h4><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Herätys</h4>
					<p id="wakeupInfo">Odota...</p>
					
					<!-- Käyttäjät 
					<h4><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Käyttäjät</h4>
					
					<?php

					include "lib/connectDB.php";

					$sth = $database -> prepare("SELECT UserName, UserActive, UserAtHome FROM table_of_users");
					$sth -> execute();

					$users = $sth->fetchAll(PDO::FETCH_ASSOC);

					echo "<table width=\"100%\">";
					foreach($users as $user)
					{
						if($user['UserActive'] == "1")
						{
							echo "<tr>";
							echo "<th width=\"66%\">".$user['UserName']."</th>";
							if($user['UserAtHome'] == "1")
							{
								echo "<td><button type=\"button\" class=\"btn btn-default btn-xs\">Kirjaudu ulos</button></td>";
							}
							else
							{
								echo "<td><button type=\"button\" class=\"btn btn-default btn-xs\">Kirjaudu sisään</button></td>";
							}
							echo "</tr>";
						}						
					}
					echo "</table>";
					
					?>-->
					
					</div>
				</div>
			</div>
		</div>	

<div class="row">
	<div class="col-sm-6">
	<!-- Kalenteri -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Kalenteri
				</h3></div>
			<div class="panel-body">
				
				<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=WEEK&amp;height=350&amp;wkst=2&amp;bgcolor=%23CCCCCC&amp;src=henri.rantanen92%40gmail.com&amp;color=%238cc63e&amp;src=ah31vg7fjs0ruq81te17lpkc68%40group.calendar.google.com&amp;color=%230ea57f&amp;src=8mf2f3useeo1fogesttr5amnt8%40group.calendar.google.com&amp;color=%238dd7f7&amp;src=hg76dtpp5jsk0kh7hmcdhls9sk%40group.calendar.google.com&amp;color=%23182C57&amp;ctz=Europe%2FHelsinki" style="border-width:0" width="100%" height="350" frameborder="0" scrolling="no"></iframe>

			</div>
		</div>
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

// Echo the wakeup info
echo "<script>document.getElementById(\"wakeupInfo\").textContent = \"$wakeupInfo\";</script>";

// Check if autolight is enabled
$autoLightStatus = exec('/usr/bin/python /var/www/lib/python/autolight.py --status');

if ($autoLightStatus === "Enabled")
{
	echo "<script>document.getElementById(\"lightsOn\").textContent = \"Automaatti\";</script>";
}
else 
{	
	echo "<script>document.getElementById(\"lightsOn\").textContent = \"Päälle\";</script>";
}

?>
