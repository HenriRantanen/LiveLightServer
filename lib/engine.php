<?php
	include("session.php");
?>

<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">

<?php

	// Include network class
	// include "../library/network.class.php";
	
	// Include color editing functions
	include "color.php";	
	
	// Read all variables
	$command 		= $_GET['command'];
	$colorspace 	= $_GET['colorspace'];
	$colorPicker 	= $_GET['colorPicker'];
	$dimmer 		= setRange($_GET['dimmer']	, 0, 100);
	$red 			= setRange($_GET['red']		, 0, 255);
	$green 			= setRange($_GET['green']	, 0, 255);
	$blue 			= setRange($_GET['blue']	, 0, 255);
	$red2 			= setRange($_GET['red2']	, 0, 255);
	$green2			= setRange($_GET['green2']	, 0, 255);
	$blue2 			= setRange($_GET['blue2']	, 0, 255);
	$hue 			= setRange($_GET['hue']		, 0, 360);
	$saturation 	= setRange($_GET['saturation'], 0, 100);
	$brightness 	= setRange($_GET['brightness'], 0, 100);
	
	// Check for debug mode
	$debugMode = isset($_GET['debug']) ? true : false;
	$debug = "";
	
	// convert colors from sliders to #FFFFFF format without the #
	switch($colorspace)
	{
		case "rgb":
			$color = createColor($red, $green, $blue);
			$color2 = createColor($red2, $green2, $blue2);
			break;
			
		case "hsl":
			echo "HSL";
			$color = HSLToRGB($hue, $saturation, $brightness);
			$color2 = $color;
			break;
			
		case "picker":
			if($debugMode) $debug .= "Selected: $color\n";
			$color = strtoupper(substr($_GET['colorPicker'],1));
			$color2 = $color;
			
			$args = "-c";
			break;
		case "cct":
			$cctValue = shell_exec("python /var/www/lib/python/kelvin.py ".$_GET['cct']);
			$color = substr($cctValue, 0, 6);
			$color2 = $color;
			
			$args = "-c";
			break;
			
		default:
			$color = "808080";
			$color2 = $color;
	}
	
	// Apply the brightness filter to selected color (dimmer)
	$color = setBrightness($color, $dimmer);
	$color2 = setBrightness($color2, $dimmer);
	
		/*$controller1 = new controller;
		//$controller1 -> setID("40A92874"); 
		$controller1 -> setID("4060DAFD");
		$controller2 = new controller;
		$controller2 -> setID("0013A2004060DB04"); 
		$controller3 = new controller;
		$controller3 -> setID("40A9283A"); */

	//Send the data to the controllers
	if($_GET['light1'] === "on")
	{
		//$controller1 -> setColor($color, $color2);
		$command = "python /var/www/lib/python/setDevice.py 1 $color$color2 -p $args";
		shell_exec ($command);
	}
	
	if($_GET['light2'] === "on")
	{
		if($colorspace === "rgb") 
		{
			$color1 = $color;
		}
		elseif($colorspace === "hsl")
		{
			$color1 = saturate($color);
			$color2 = white($color);
		}
		elseif($colorspace === "picker")
		{
			$color1 = saturate($color);
			$color2 = white($color);
		}
		elseif($colorspace === "cct")
		{
			$color1 = $color;
			$color2 = setBrightness(substr($cctValue, 6,4)."00", $dimmer); 
		}
		
		$command = "python /var/www/lib/python/setDevice.py 2 $color1$color2 -p $args";
		shell_exec ($command);
	}

	if($_GET['light3'] === "on")
	{
		if($colorspace === "rgb") 
		{
			$color1 = $color;
		}
		elseif($colorspace === "hsl")
		{
			$color1 = saturate($color);
			$color2 = white($color);
		}
		elseif($colorspace === "picker")
		{
			$color1 = saturate($color);
			$color2 = white($color);
		}
		elseif($colorspace === "cct")
		{
			$color1 = $color;
			$color2 = setBrightness(substr($cctValue, 6,4)."00", $dimmer); 
		}
		
		$command = "python /var/www/lib/python/setDevice.py 3 $color1$color2 -p $args";
		shell_exec ($command);
	}
	
	if($_GET['light4'] === "on")
	{
		$color1 = $color;
		$color2 = $color2;
		
		$command = "python /var/www/lib/python/setDevice.py 4 $color1$color2 -p $args";
		shell_exec ($command);
	}
	
	$command = "python /var/www/lib/python/sendPacket.py";
	shell_exec ($command);	
?>

