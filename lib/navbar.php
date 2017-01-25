<?php
// Generate info for the wakeup-button
$alarm = shell_exec('/usr/bin/python /var/www/lib/python/setAlarm.py --time');
$alarm = preg_replace( "/\r|\n/", "", $alarm);

if($alarm != "--:--")$wakeup = "Herätys alkaa $alarm";
else $wakeup ="Aseta herätys";

// If current tab is not set, dont highlight anything.
if(!(isset($tab))) $tab = 0;

// Check if autolight is enabled
$autoLightStatus = exec('/usr/bin/python /var/www/lib/python/autolight.py --status');



if ($autoLightStatus === "Enabled")
{
	$autoLightStatus = "ON";
	$alcLink = "lib/autoMode.php?set=0";
}
else 
{	
	$autoLightStatus = "OFF";
	$alcLink = "lib/autoMode.php?set=1";
}

?>
<div id="loaderScreen" style="position: absolute; z-index: 100; width: 100%; height: 100%; background-color: rgba(0, 16, 64, 0.5); display:none; ">
	<div class="panel panel-default" style="width: 160px; margin-left: auto; margin-right: auto; margin-top: 130px;">
		<div class="panel-body">
		<img src="/img/ajax-loader.gif" />
		<div>Myllytetään...</div>
  </div>
</div>
</div>
<nav class="navbar navbar-default navbar-inverse">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">LiveLight Lion</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<?php if($login)
			{
			echo "<ul class=\"nav navbar-nav\">";
			
				echo "<li "; 
				if($tab===1) echo "class=\"active\"";
				echo "><a href=\"index.php\"><span class=\"glyphicon glyphicon-th\" aria-hidden=\"true\"></span> Pikanappulat</a></li>
				
				<li "; 
				if($tab===2) echo "class=\"active\"";
				echo "><a href=\"editor.php\"><span class=\"glyphicon glyphicon-align-justify\" aria-hidden=\"true\"></span> Editori</a></li>";
				
				echo"<li "; 
				if($tab===3) echo "class=\"active\"";
				echo "><a href=\"dashboard.php\"><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span> Kotinäyttö</a></li>";
			
			echo "</ul>";
			echo "<ul class=\"nav navbar-nav navbar-right\">";
			
			echo "<li ";
			echo "><a href=\"$alcLink\" target=\"_blank\"><span class=\"glyphicon glyphicon-cog\" aria-hidden=\"true\"></span> Automaattiohjaus $autoLightStatus</a></li>";
			
				echo "<li ";
				if($tab===3) echo "class=\"active\"";
				echo "><a href=\"alarm.php\"><span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span> $wakeup</a></li>";

				echo "<li class=\"visible-lg"; 
				if($tab===5) echo " active"; 
				echo "\"><a href=\"settings.php\"><span class=\"glyphicon glyphicon-cog\" aria-hidden=\"true\"></span> Työkalut</a></li>";
				echo "<li><a href=\"login.php?logout\"><span class=\"glyphicon glyphicon-log-out\" aria-hidden=\"true\"></span> Kirjaudu ulos </a></li>";
			echo "</ul>";
			}
			?>
		</div>
	</div>
</nav>