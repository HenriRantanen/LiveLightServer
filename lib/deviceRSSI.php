<?php
//$device = $_GET["mac"];
//$device ="40ABAF23";

function getRSSI($device)
{
	$signalLevel = shell_exec("python /var/www/lib/python/getRSSI.py $device");
	$signalLevel = preg_replace("/[^0-9]/","",$signalLevel);
	return $signalLevel;
}
?>