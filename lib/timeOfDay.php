<?php
function simulateDay()
{
	$time = date("G", time());
		
	if($time >= 0 && $time < 6)
	{
		$name = "Yö";

		$color1 = "040506";
		$color2 = "040404";
		$color3 = "000000";
		$color4 = "000000";
		$color5 = "000000";
		$color6 = "121210";
		$color7 = "010000";
	}
	else if($time >= 6 && $time < 8)
	{
		$name = "Aamuyö";
		$color1 = "403929";
		$color2 = "403929";
		$color3 = "40240C";
		$color4 = "2D2100";
		$color5 = "403929";
		$color6 = "403929";
		$color7 = "403929";
	}
	else if($time >= 8 && $time < 11)
	{
		$name = "Aamu";
		$color1 = $color2 = $color3 = $color4 = $color5 = $color6 = $color7 = "FFE3A5";
	}
	else if($time >= 11 && $time < 17)
	{
		$name = "Päivä";
		$color1 = "EBD9D1";
		$color2 = "FFE8AC";
		$color3 = "A7C6D2";
		$color4 = "4AFF00";
		$color5 = "FFBCB4";
		$color6 = "FFEB96";
		$color7 = "FFD2CD";
	}
	else if($time >= 17 && $time < 20)
	{
		$name = "Iltapäivä";
		$color1 = "766D69";
		$color2 = "807456";
		$color3 = "546369";
		$color4 = "258000";
		$color5 = "805E5A";
		$color6 = "80764B";
		$color7 = "FFD2CD";
	}
	else if($time >= 20 && $time <= 23)
	{
		$name = "Ilta";
		$color1 = "0A0907";
		$color2 = "0A0907";
		$color3 = "0A0602";
		$color4 = "070500";
		$color5 = "0A0907";
		$color6 = "0A0907";
		$color7 = "190023";
	}
	
	$command = "python /var/www/lib/python/setPreset.py $color1 $color2 $color3 $color4 $color5 $color6 $color7 000000";
	shell_exec ($command);
	
	return $name;
}
?>