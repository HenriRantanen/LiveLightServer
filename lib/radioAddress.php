<?php 
	require_once("string.php");
	
	$tiedosto = "/var/www/lib/XBEE.txt";
	$kahva = fopen($tiedosto, 'r');
	
	$Data = fread($kahva, 16);
	fclose($kahva);
	echo normalize_mac($Data);
?>