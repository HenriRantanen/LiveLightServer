<?php 

$cronjob = shell_exec('crontab -l');
$alert = Isset($cronjob);
$kissa = explode(" ", $cronjob);
	
$setMinute = str_pad($kissa[0], 2, "0", STR_PAD_LEFT);
$setHour = $kissa[1];

if ($alert)
{
	echo "
	<div class=\"col-md-12 column\">
	<div class=\"alert alert-info alert-dismissible\" role=\"alert\">
	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
	<strong>Herätys alkaa</strong> $setHour:$setMinute.
	</div>
	</div>";
}
?>