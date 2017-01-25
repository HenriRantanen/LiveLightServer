<?php
	function white($color)
	{
		// Parse RGB values
		$red = round(hexdec(substr($color, 0,2)));
		$green = round(hexdec(substr($color, 2,2)));
		$blue = round(hexdec(substr($color, 4,2)));
		
		$minimi = min($red, $green, $blue);
		
		return createColor($minimi, $minimi, $minimi);
	}

	function saturate($color)
	{
		// Parse RGB values
		$red = round(hexdec(substr($color, 0,2)));
		$green = round(hexdec(substr($color, 2,2)));
		$blue = round(hexdec(substr($color, 4,2)));
		
		$minimi = min($red, $green, $blue);
		
		$red = $red - $minimi;
		$green = $green - $minimi;
		$blue = $blue - $minimi;
		
		return createColor($red, $green, $blue);
	}

	// Apply dimmer value to color
	function setBrightness($color, $dimmer)
	{
		// Set the brightness value to 0-100 (%)
		//if($dimmer <= 0) 	$dimmer = 0;
		//if($dimmer >= 100) 	$dimmer = 100;
		$dimmer = setRange($dimmer, 0, 100);
		
		$dimmer = $dimmer/100;
		
		$red = $dimmer*round(hexdec(substr($color, 0,2)));
		$green = $dimmer*round(hexdec(substr($color, 2,2)));
		$blue = $dimmer*round(hexdec(substr($color, 4,2)));
		
		$color = sprintf('%02X', ($red)).sprintf('%02X', ($green)).sprintf('%02X', ($blue));
		
		return $color;
	}

	// Create color "FF00FF" from (255,0,255)
	function createColor($r, $g, $b)
	{
		return sprintf('%02X', ($r)).sprintf('%02X', ($g)).sprintf('%02X', ($b));
	}
	
	// Make sure the value is inside the range
	function setRange($value, $minimum, $maximum)
	{
		if($value <= $minimum) 	$value = $minimum;
		elseif($value >= $maximum) 	$value = $maximum;
		
		return $value;
	}
	
	// Convert HSL values to RGB color
	function HSLToRGB($h, $s, $l)
	{
		$h = setRange($h, 0, 360);
		$s = setRange($s, 0, 100);
		$l = setRange($l, 0, 100);
		
		$h = ((float)$h) / 360.0;
		$s = ((float)$s) / 100.0;
		$l = ((float)$l) / 100.0;

		if($s == 0)
		{
		  $r = $l;
		  $g = $l;
		  $b = $l;
		}
		else
		{
			if($l < .5)
			{
				$t2 = $l * (1.0 + $s);
			}
			else
			{
				$t2 = ($l + $s) - ($l * $s);
			}
				$t1 = 2.0 * $l - $t2;

			$rt3 = $h + 1.0/3.0;
			$gt3 = $h;
			$bt3 = $h - 1.0/3.0;

			if($rt3 < 0) $rt3 += 1.0;
			if($rt3 > 1) $rt3 -= 1.0;
			if($gt3 < 0) $gt3 += 1.0;
			if($gt3 > 1) $gt3 -= 1.0;
			if($bt3 < 0) $bt3 += 1.0;
			if($bt3 > 1) $bt3 -= 1.0;

			if(6.0 * $rt3 < 1) $r = $t1 + ($t2 - $t1) * 6.0 * $rt3;
			elseif(2.0 * $rt3 < 1) $r = $t2;
			elseif(3.0 * $rt3 < 2) $r = $t1 + ($t2 - $t1) * ((2.0/3.0) - $rt3) * 6.0;
			else $r = $t1;

			if(6.0 * $gt3 < 1) $g = $t1 + ($t2 - $t1) * 6.0 * $gt3;
			elseif(2.0 * $gt3 < 1) $g = $t2;
			elseif(3.0 * $gt3 < 2) $g = $t1 + ($t2 - $t1) * ((2.0/3.0) - $gt3) * 6.0;
			else $g = $t1;

			if(6.0 * $bt3 < 1) $b = $t1 + ($t2 - $t1) * 6.0 * $bt3;
			elseif(2.0 * $bt3 < 1) $b = $t2;
			elseif(3.0 * $bt3 < 2) $b = $t1 + ($t2 - $t1) * ((2.0/3.0) - $bt3) * 6.0;
			else $b = $t1;
		}

		$r = (int)round(255.0 * $r);
		$g = (int)round(255.0 * $g);
		$b = (int)round(255.0 * $b);

		$RGB = $b + ($g << 0x8) + ($r << 0x10);
		
		return createColor($r, $g, $b);
	}
	
	function RGBtoHSL( $r, $g, $b ) {
    $oldR = $r;
    $oldG = $g;
    $oldB = $b;
 
    $r /= 255;
    $g /= 255;
    $b /= 255;
 
    $max = max( $r, $g, $b );
    $min = min( $r, $g, $b );
 
    $h;
    $s;
    $l = ( $max + $min ) / 2;
    $d = $max - $min;
 
        if( $d == 0 ){
            $h = $s = 0; // achromatic
        } else {
            $s = $d / ( 1 - abs( 2 * $l - 1 ) );
 
        switch( $max ){
                case $r: 
                    $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 ); 
                    break;
 
                case $g: 
                    $h = 60 * ( ( $b - $r ) / $d + 2 ); 
                    break;
 
                case $b: 
                    $h = 60 * ( ( $r - $g ) / $d + 4 ); 
                    break;
            }                                
    }
 
    return array( $h, $s, $l );
	//return $s;
}

	function desaturateMin( $r, $g, $b ) 
	{
		return round(min($r, $g, $b)/3);
	}
	
	// "#abba15" --> "ABBA15"
	function normalizeHex($hex)
	{
		$hex = strtoupper($hex);
		if(substr($hex,0,1) === "#"){$hex = substr($hex,1);}
		return $hex;
	}
?>