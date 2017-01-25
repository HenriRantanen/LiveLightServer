<?php
	
	$button = isset($_GET["button"]) ? $_GET["button"] : '';
	$wakeupTime = isset($_GET["wakeupTime"]) ? $_GET["wakeupTime"] : '';

	if($button == "set")
	{	
		setWakeup ($wakeupTime);
		header('Location: ../alarm.php');
	}
	
	if($button == "clear")
	{
		$command = "/usr/bin/python /var/www/lib/python/setAlarm.py --remove";
		$output = shell_exec($command);
		header('Location: ../alarm.php');
	}
	
	if($button == "off")
	{
		exec('pkill -f "python /var/www/lib/python/wakeup.py"');
		exec('python /var/www/lib/python/off.py');
		//$command = "/usr/bin/python /var/www/lib/python/setAlarm.py --remove";
		//$output = shell_exec($command);
		
		$command = "/usr/bin/python /var/www/lib/python/autolight.py --disable";
		echo shell_exec($command);
		
		header('Location: ../alarm.php');
	}
	
	if($button == "list")
	{	
		$command = "crontab -l";
		$cronfile = exec($command, $jobs);
		
		echo "<h1>CronTab contents</h1>\r\n";
		
		$index = 0;
		foreach ($jobs as $job)
		{
			if($job != "")
			{
				echo "<h4>".substr($job, strpos($job, "# ") + 1)."</h4>\r\n";
				echo $job."\r\n";
			}
			$index++;			
		}
	}
	
	function setWakeup ($wakeupTime)
	{
		$minute = substr($wakeupTime, 3);
		$hour = substr($wakeupTime, 0, 2);

		// Vähennä herätysajasta 45min
		if($minute < 45) 
		{
			if($hour == 0)
			{
				$hour = 23;
			}
			else
			{
				$hour--;
			}
			$minute = 60-(45-$minute);
		}
		else
		{
			$minute -= 45;
		}
		
		$command = "/usr/bin/python /var/www/lib/python/setAlarm.py -hr $hour -min $minute";
		$output = shell_exec($command);

	}
?>
