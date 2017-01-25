<?php
// Create a 200 x 200 image
$canvas = imagecreatetruecolor(2000, 1600);


// Allocate colors
$cred = imagecolorallocate($canvas, 255, 10, 10);
$cgreen = imagecolorallocate($canvas, 10, 255, 10);
$cblue = imagecolorallocate($canvas, 10, 10, 255);
$cblack = imagecolorallocate($canvas, 0, 0, 0);
$cgray = imagecolorallocate($canvas, 128, 128, 128);
$cwhite = imagecolorallocate($canvas, 255, 255, 255);

imagefill($canvas, 0, 0, $cwhite);

//142 = 6500K
for ($i = 0; $i <= 1500; $i++)
{
	$cct = (int)($i*((332)/(15))+1800);
	
/*****************************/
if($cct < 6500)
{
	$red = 255;
	$green = log10(($cct-1000)/(332.9))*(209.4); // Perfect//(int)(log10(($cct-1000)/266)*(152.6))+($cct*5.8/1000);
	$blue = (255-16)/(6500-2000)*($cct-2000)+16; // Perfect
}
else
{
	$red = -log10(($cct-6000)/100000000)*70.8-120.5;
	$green = -log10(($cct-6000)/100000000)*(43-0.74)+31;
	$blue = 255; // Perfect
	
}

/******************************/
	
	//$color = shell_exec("python /var/www/lib/python/kelvin.py ".$cct);
	
	/*$red = round(hexdec(substr($color, 0,2)));
	$green = round(hexdec(substr($color, 2,2)));
	$blue = round(hexdec(substr($color, 4,2)));*/
	
	$ccct = imagecolorallocate($canvas, $red, $green, $blue);
	
	imageline ($canvas, $i+100, 10, $i+100, 140, $ccct);
	
	imagesetpixel ($canvas, $i+100, 1180, $cblack);
	imagesetpixel ($canvas, $i+100, 1180-4*32, $cgray);
	imagesetpixel ($canvas, $i+100, 1180-4*64, $cgray);
	imagesetpixel ($canvas, $i+100, 1180-4*96, $cgray);
	imagesetpixel ($canvas, $i+100, 1180-4*128, $cblack);
	imagesetpixel ($canvas, $i+100, 1180-4*160, $cgray);
	imagesetpixel ($canvas, $i+100, 1180-4*192, $cgray);
	imagesetpixel ($canvas, $i+100, 1180-4*224, $cgray);
	imagesetpixel ($canvas, $i+100, 1180-4*256, $cblack);
	
	imagefilledellipse  ($canvas, $i+100, 1180-4*$red, 4, 4, $cred);
	imagefilledellipse  ($canvas, $i+100, 1180-4*$green, 4, 4, $cgreen);
	imagefilledellipse  ($canvas, $i+100, 1180-4*$blue, 4, 4, $cblue);
	//imagesetpixel ($canvas, $i+100, 1180-4*$green, $cgreen);
	//imagesetpixel ($canvas, $i+100, 1180-4*$blue, $cblue);
	
	if($i %71 === 0) {
	  imageline ($canvas, $i+100, 1190, $i+100, 1180-4*256, $cblack);
	  imagestring ($canvas, 2, $i+85, 1200, "$cct K", $cblack);
	}
	
}


// Output and free from memory
header('Content-Type: image/jpeg');

imagejpeg($canvas, NULL, 100);
imagedestroy($canvas);

?>

  