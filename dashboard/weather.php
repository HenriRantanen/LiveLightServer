<?php
$apiKey = "ddb9de05cf46c221";
$city = "Helsinki";
$jsonUrl = "http://api.wunderground.com/api/".$apiKey."/conditions/q/CA/".$city.".json";

$json = file_get_contents($jsonUrl);
$obj = json_decode($json);

$weather = $obj->current_observation; 

switch ($weather->icon)
{
	case "rain":
		$imgfile = "img/23.png";
		$weatherText = "Sadetta";
		break;	

	case "lightrain":
		$imgfile = "img/20.png";
		$weatherText = "Kevyttä sadetta";
		break;

	case "mostlycloudy":
		$imgfile = "img/19.png";
		$weatherText = "Pilvistä";
		break;

	case "clear":
		$imgfile = "img/19.png";
		$weatherText = "Selkeää ja poutaa";
		break;



	default:
		$imgfile = $weather->icon_url;
		$weatherText = $weather->weather;
		break;
}

$weatherTime = date("H:i j.n.Y", $weather->observation_epoch);


$img = '<img src="'.$imgfile.'">';

?>

<!-- Sääwidget -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> S&auml;&auml;tiedot
		</h3>
		</div>
	<div class="panel-body">
		<span><small><?php echo $weather->display_location->city; ?>
		<?php echo $weatherTime; ?></small></span>

	<div class="row">
		<div class="col-md-6 col-sm-5">
		<h2><?php echo $weather->temp_c; ?> &degC</h2>
		</div>
		<div class="col-md-6 col-sm-4">
		<?php echo $img; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<table width="100%">
				<tbody>
				<tr>
					<td>Tuntuu kuin <?php echo $weather->feelslike_c; ?> &degC</td>
				</tr>
				<tr>
					<td>Tuuli <?php echo round(($weather->wind_kph)*0.27777777, 2); ?> m/s <?php echo windDirection($weather->wind_degrees); ?></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<table width="100%">
				<tbody>
				<tr>
					<td><?php echo $weatherText; ?></td>
				</tr>
				<tr>
					<td>Ilmankosteus <?php echo $weather->relative_humidity; ?></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>


	</div>
</div>

<?php

function windDirection($degrees)
{
	$text = "";

	if($degrees < 315 || $degrees > 22)	{
		$text = "pohjoisesta";}
	else if($degrees >= 22 && $degrees < 67) {
		$text = "koillisesta";}
	else if($degrees >= 67 && $degrees < 112) {
		$text = "idästä";}
	else if($degrees >= 112 && $degrees < 157) {
		$text = "kaakosta";}
	else if($degrees >= 157 && $degrees < 202) {
		$text = "etelästä";}
	else if($degrees >= 202 && $degrees < 225) {
		$text = "lounaasta";}
	else if($degrees >= 225 && $degrees < 270) {
		$text = "lännestä";}
	else if($degrees >= 270 && $degrees < 315) {
		$text = "luoteesta";}

	return $text;
}

?>