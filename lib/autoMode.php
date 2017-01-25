<?php
session_start();

if(isset($_SESSION["UserID"]) || $_GET["key"] === "mJqxsmuvrNmWc")
{
	$set = $_GET["set"];
	
	if($set == "1")
	{
		$command = "/usr/bin/python /var/www/lib/python/autolight.py --enable";
		shell_exec($command);
		echo "ALC Enabled";
	}
	elseif($set == "0")
	{
		$command = "/usr/bin/python /var/www/lib/python/autolight.py --disable";
		echo shell_exec($command);
		echo "ALC Disabled";
	}
}
header('Location: ../index.php');
?>