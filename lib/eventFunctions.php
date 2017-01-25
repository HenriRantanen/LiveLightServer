<?php

function setChannel($controller, $channel, $value)
{
	require "connectDB.php";
	
	// Check if value is in range
	if($value >= 0 && $value <= 255) {$value = sprintf("%02X", $value);}
	else {return 0;}
	
	// Check if channel is in range
	if($channel >= 0 && $channel <= 6) {}
	else {return 0;}
		
	$sth = $database -> prepare("SELECT * FROM table_of_devices WHERE DeviceID = ?");
	$sth -> bindParam(1, $controller);
	$sth -> execute();
	$device = $sth->fetch(PDO::FETCH_ASSOC);
	
	$sth = $database -> prepare("SELECT LampID, LampChMap, LampColor FROM table_of_lamps WHERE LampDevices = ?");
	$sth -> bindParam(1, $controller);
	$sth -> execute();
	$lamp = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	//print_r($lamp);
	
	// count how many lights are there in the controller
	$lampCount = count($lamp);
	
	// Default values in the case of channel is not used
	$state = array("00","00","00","00","00","00");
	
	// Parse channel values from database
	// Do for each lamp
	for ($i = 0; $i < $lampCount; $i++)
	{
		$lampChannels = substr_count($lamp[$i]["LampChMap"], ";");
		
		// Do for each channel of the lamp
		for($j = 0; $j < $lampChannels; $j++)
		{			
			$channelNumber = substr($lamp[$i]["LampChMap"], $j*2+4,1)-1;
			$channelValue = substr($lamp[$i]["LampColor"], $j*2,2);
			
			// Save the new value to the database
			if($channelNumber === $channel-1)
			{
				// Formulate new lamp colour data
				$chAddress = $lamp[$i]["LampID"];
				$originalState = $lamp[$i]["LampColor"];
				$newState = substr_replace($originalState, $value, $j*2, 2);
				
				// Write the data
				$sth = $database -> prepare("UPDATE table_of_lamps SET LampColor = ? WHERE LampID = ?");
				$sth -> bindParam(1, $newState);
				$sth -> bindParam(2, $chAddress);
				$sth -> execute();
			}

			$state[$channelNumber] = $channelValue;
		}
	}
	
	// Write the new value to the array
	$state[$channel-1] = $value;
	
	// Formulate a command for the TX-function
	$command = "python /var/www/lib/python/setDevice.py ".$device["DeviceMAC"]." ";
	for ($i = 0; $i < 6; $i++){$command .= $state[$i];}
	
	// Execute the command, send data to the controller.
	echo shell_exec($command);
}

function setLamp($lamp, $hex)
{
	require "connectDB.php";
	require_once "color.php";
	
	// remove # from the start
	$hex = normalizeHex($hex);
	
	// Bake the single values from HEX triplet
	$red 	= hexdec(substr($hex, 0,2));
	$green 	= hexdec(substr($hex, 2,2));
	$blue 	= hexdec(substr($hex, 4,2));
	
	// Read lamp information
	$sth = $database -> prepare("SELECT LampID, LampDevices, LampChMap, LampColor FROM table_of_lamps WHERE LampID = ?");
	$sth -> bindParam(1, $lamp);
	$sth -> execute();
	$lamp = $sth->fetch(PDO::FETCH_ASSOC);
		
	// Count the amount of channels in the lamp
	$lampChannels = substr_count($lamp["LampChMap"], ";");
	
	// Read previous state of the controller
	$state = getControllerStatus($lamp["LampDevices"]);
	
	// Read the settings of the controller
	$sth = $database -> prepare("SELECT * FROM table_of_devices WHERE DeviceID = ?");
	$sth -> bindParam(1, $lamp["LampDevices"]);
	$sth -> execute();
	$device = $sth->fetch(PDO::FETCH_ASSOC);
	
	//Start making the new state
	
	//If multiple channels are used
	if($lampChannels > 3)
	{
	
		// Find the PW channel
		$pwChNumber = $mapValue = 0;
		
		// Test all channels which has the highest mask and save the number
		for ($i = 1; $i <= $lampChannels; $i++)
		{
			$channelMap = $device["DeviceCH".((string)($i))];
					
			if($mapValue < hexdec(substr($channelMap, 0,2))+hexdec(substr($channelMap, 2,2))+hexdec(substr($channelMap, 4,2)))
			{
				$mapValue = hexdec(substr($channelMap, 0,2))+hexdec(substr($channelMap, 2,2))+hexdec(substr($channelMap, 4,2));
				$pwChNumber = $i;
			}
		}
		$pwChMask = $device["DeviceCH".((string)($pwChNumber))];
		
		// Calculate the pure white channel
		$PW = desaturateMin((hexdec(substr($pwChMask, 0,2))/255) * $red, (hexdec(substr($pwChMask, 2,2))/255) * $green, (hexdec(substr($pwChMask, 4,2))/255) * $blue);
		
		// Set the pure white channel
		$state[$pwChNumber-1] = sprintf("%02X", $PW);
		
		// Reduce the input brightness
		$red = $red-$PW;
		$green = $green-$PW;
		$blue = $blue-$PW;
		
		//Find the WW channel
		if($lampChannels > 4)
		{
			$wwChNumber = $mapValue = 0;
			// Test all channels which has the highes mask and save the number
			for ($i = 1; $i <= $lampChannels; $i++)
			{
				$channelMap = $device["DeviceCH".((string)($i))];
				
				if(($i != $pwChNumber) && ($mapValue < hexdec(substr($channelMap, 0,2))+hexdec(substr($channelMap, 2,2))+hexdec(substr($channelMap, 4,2))))
				{
					$mapValue = hexdec(substr($channelMap, 0,2))+hexdec(substr($channelMap, 2,2))+hexdec(substr($channelMap, 4,2));
					$wwChNumber = $i;
				}
			}
			$wwChMask = $device["DeviceCH".((string)($wwChNumber))];
		
			$WW = desaturateMin((hexdec(substr($wwChMask, 0,2))/255) * $red, (hexdec(substr($wwChMask, 2,2))/255) * $green, (hexdec(substr($wwChMask, 4,2))/255) * $blue);
			
			// Set the pure white channel
			$state[$wwChNumber-1] = sprintf("%02X", $WW);
			
			// Reduce the input
			$red = $red-$WW;
			$green = $green-$WW;
			$blue = $blue-$WW;
		}
		
		// Create the new state for the controller
		for ($i = 0; $i < $lampChannels; $i++)
		{
			// TO all color channels
			if($i != $wwChNumber-1 && $i != $pwChNumber-1)
			{
				$channelNumber = substr($lamp["LampChMap"], $i*2+4,1);
				$channelMap = $device["DeviceCH".$channelNumber];
				
				// Get the multiplier values from database
				$colour["red"] 	= sprintf("%02X", round((hexdec(substr($channelMap, 0,2))/255) * $red));
				$colour["green"] 	= sprintf("%02X", round((hexdec(substr($channelMap, 2,2))/255) * $green));
				$colour["blue"] 	= sprintf("%02X", round((hexdec(substr($channelMap, 4,2))/255) * $blue));
				echo "<br>";
				
				if($colour["green"] === "00" && $colour["blue"] === "00")
				{ $state[$channelNumber-1] = $colour["red"];}
				else if($colour["red"] === "00" && $colour["blue"] === "00")
				{ $state[$channelNumber-1] = $colour["green"];}
				else if($colour["red"] === "00" && $colour["green"] === "00")
				{ $state[$channelNumber-1] = $colour["blue"];}
				else {$state[$channelNumber-1] = "00";}
				
				// Get the multiplier values from database
			/*	
				$colour["red"] 		= hexdec(substr($hex, 0,2))*(bool)(hexdec(substr($channelMap, 0,2)));
				$colour["green"] 	= hexdec(substr($hex, 2,2))*(bool)(hexdec(substr($channelMap, 2,2)));
				$colour["blue"] 	= hexdec(substr($hex, 4,2))*(bool)(hexdec(substr($channelMap, 4,2)));
				
				//print_r($colour);
	/*
				$colour["red"] 		= (hexdec(substr($channelMap, 0,2))/255) * $red;
				$colour["green"] 	= (hexdec(substr($channelMap, 2,2))/255) * $green;
				$colour["blue"] 	= (hexdec(substr($channelMap, 4,2))/255) * $blue;
				
				
				if($colour["green"] === 0 && $colour["blue"] === 0)
				{ 
					$state[$channelNumber-1] = sprintf("%02X", round($colour["red"]));
				}
				else if($colour["red"] === 0 && $colour["blue"] === 0)
				{ 
					$state[$channelNumber-1] = sprintf("%02X", round($colour["green"]));
				}
				else if($colour["red"] === 0 && $colour["green"] === 0)
				{
					$state[$channelNumber-1] = sprintf("%02X", round($colour["blue"]));
				}
				else {$state[$channelNumber-1] = "00";}*/
			}
		}
		
		//print_r ($state);
	}
	else
	{
		// Create the new state for the controller
		for ($i = 0; $i < $lampChannels; $i++)
		{
			$channelNumber = substr($lamp["LampChMap"], $i*2+4,1);
			$channelMap = $device["DeviceCH".($channelNumber)];
				
			// Get the multiplier values from database
			
			$colour["red"] 		= hexdec(substr($hex, 0,2))*(bool)(hexdec(substr($channelMap, 0,2)));
			$colour["green"] 	= hexdec(substr($hex, 2,2))*(bool)(hexdec(substr($channelMap, 2,2)));
			$colour["blue"] 	= hexdec(substr($hex, 4,2))*(bool)(hexdec(substr($channelMap, 4,2)));
			
			//print_r($colour);
/*
			$colour["red"] 		= (hexdec(substr($channelMap, 0,2))/255) * $red;
			$colour["green"] 	= (hexdec(substr($channelMap, 2,2))/255) * $green;
			$colour["blue"] 	= (hexdec(substr($channelMap, 4,2))/255) * $blue;
			*/
			
			if($colour["green"] === 0 && $colour["blue"] === 0)
			{ 
				$state[$channelNumber-1] = sprintf("%02X", round($colour["red"]));
			}
			else if($colour["red"] === 0 && $colour["blue"] === 0)
			{ 
				$state[$channelNumber-1] = sprintf("%02X", round($colour["green"]));
			}
			else if($colour["red"] === 0 && $colour["green"] === 0)
			{
				$state[$channelNumber-1] = sprintf("%02X", round($colour["blue"]));
			}
			else {$state[$channelNumber-1] = "00";}
		}
		
		//print_r($state);
	
		// Write the data to the database
		$sth = $database -> prepare("UPDATE table_of_lamps SET LampColor = ? WHERE LampID = ?");
		$sth -> bindParam(1, $hex);
		$sth -> bindParam(2, $lamp["LampID"]);
		$sth -> execute();
	}			

	// Add fan-controlling functionality
	$state = setFan($device, $state);
	
	// Formulate a command for the TX-function
	$command = "/usr/bin/python /var/www/lib/python/setDevice.py ".$device["DeviceID"]." ";
	for ($i = 0; $i < 6; $i++){$command .= $state[$i];}
	
	// Enable color correction
	$command .= " -c";

	//print_r($state);
	
	echo $command;
	// Execute the command, send data to the controller.
	shell_exec($command);
	
	}

function setFan($device, $state)
{
	require "connectDB.php";
	require_once "color.php";
	
	$usedChannels = 0;
	$power = 0;
	
	// Search for the first FAN channel and the value
	for ($i = 1; $i <= 6; $i++)
	{
		$channelMask = $device["DeviceCH".((string)($i))];
				
		if(substr($channelMask, 0,3) === "FAN")
		{
			$fanChannel = $i-1;
			$fanSpeed = hexdec(substr($device["DeviceState"], $i*2-2, 2));
			$fanMinimum = hexdec(substr($channelMask, 3,2)); //minimum fan-speed on procent
		}
		elseif($channelMask != "000000")
		{
			$usedChannels++;
			$power += hexdec(substr($state[$i-1], 0,2));
		}
	}
	
	// devide by amount of channels
	$procent = round(($power/$usedChannels)/255*100);	
	
	// If fan is found
	if(isset($fanChannel))
	{
		// Calculate fan-speed.
		if(empty($procent))
		{
			$fanSpeed = 0;
		}
		else
		{
			//Linear
			//$fanSpeed = round((((255-$fanMinimum)/100)*$procent)+$fanMinimum);
			
			//Power of 2 (power comes in squares!)
			$fanSpeed = round(((255-$fanMinimum)/10000)*($procent*$procent)+$fanMinimum);
		}
		
		$state[$fanChannel] = sprintf("%02X", $fanSpeed);
		return $state;
	}
	else
	{
		//No fan attached;
		return $state;
	}
	

}

function getControllerStatus($controller)
{
	require "connectDB.php";
	
	$sth = $database -> prepare("SELECT * FROM table_of_devices WHERE DeviceID = ?");
	$sth -> bindParam(1, $controller);
	$sth -> execute();
	$device = $sth->fetch(PDO::FETCH_ASSOC);
		
	// Default values in the case of channel is not used
	$state = array("00","00","00","00","00","00");

	// Read the previous state of the controller
	$sth = $database -> prepare("SELECT LampChMap, LampColor FROM table_of_lamps WHERE LampDevices = ?");
	$sth -> bindParam(1, $controller);
	$sth -> execute();
	$lamps = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	//print_r($lamps);
	
	// Run for each lamp connected to the controller
	for ($j = 0; $j < count($lamps); $j++)
	{
		// Count the channels that the lamp uses
		$channels = substr_count($lamps[$j]["LampChMap"], ";");
		
		// Run for each channel connected to the lamp
		for ($i = 0; $i < $channels; $i++)
		{
			//echo $i;
			$channel = substr($lamps[$j]["LampChMap"], $i*2+4,1)-1;
			$value = substr($lamps[$j]["LampColor"], $i*2,2);
			
			$state[$channel] = $value;
		}
	}
	
	return $state;
}

?>