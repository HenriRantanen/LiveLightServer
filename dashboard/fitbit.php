<?php require_once "../lib/session.php";

$fitbitData = explode(",", shell_exec('/usr/bin/python /var/www/lib/python/fitbit.py'));
$fitbitCaloriesLeftprocent = round($fitbitData[2]/$fitbitData[1]*100,1);
$fitbitWeightGoalprocent = round(($fitbitData[5]-$fitbitData[8])/($fitbitData[5]-$fitbitData[6])*100,1);
// 8 nykypaino
// 5 alkupaino
// 6 tavoitepaino
$fitbitFatGoalprocent = round((15-$fitbitData[9])/(15-$fitbitData[7])*100,1);


$fitBitBarColor = "success";

if($fitbitData[1]-$fitbitData[2] < 50)
{
	$fitBitBarColor = "warning";
}
if($fitbitData[1] < $fitbitData[2])
{
	$fitBitBarColor = "danger";
}

echo '<script>document.getElementById("foodInfo").textContent = "'.($fitbitData[1]-$fitbitData[2]).' kcal";</script>';
?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-apple" aria-hidden="true"></span> FitBit
					</h3></div>
				<div class="panel-body">
					<table width="100%">
					<tbody>
					<tr>
						<th colspan="2">Syöty</th>
					</tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
							  <div class="progress-bar progress-bar-<?php echo $fitBitBarColor; ?>" role="progressbar" aria-valuenow="<?php echo $fitbitData[2]; ?>" aria-valuemin="0" aria-valuemax="<?php echo $fitbitData[1]; ?>" style="width: <?php echo $fitbitCaloriesLeftprocent; ?>%">
							    <span class="sr-only"><?php echo $fitbitCaloriesLeftprocent; ?>% Complete (success)</span>
							    <?php echo $fitbitCaloriesLeftprocent; ?>%
							  </div>
						</div>
					</td>
					</tr>
					<tr>
						<th colspan="2">Juotu</th>
					</tr>
					<tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
						  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $fitbitData[3]; ?>" aria-valuemin="0" aria-valuemax="1893" style="width: <?php echo round($fitbitData[3]/1893*100); ?>%">
						    <span class="sr-only"><?php echo round($fitbitData[3]/1893*100); ?>% Complete (success)</span>
						    <?php echo round($fitbitData[3]/1893*100); ?>%
						  </div>
					</div>
					</td>
					</tr>
					<tr>
						<th colspan="2">Paino</th>
						</tr>
						<!--<th>Tavoite <small><?php echo $fitbitData[4]; ?></small></th>
					</tr>
					<tr>
						<td><span class="glyphicon glyphicon-scale" aria-hidden="true"></span> <?php echo round($fitbitData[8],1); ?> kg</td>
						<td><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> <?php echo round($fitbitData[6],1); ?> kg</td>
					</tr>''-->
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
							  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $fitbitWeightGoalprocent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $fitbitWeightGoalprocent; ?>%">
							    <span class="sr-only"><?php echo $fitbitWeightGoalprocent; ?>% Complete (success)</span>
							    <?php echo $fitbitWeightGoalprocent; ?>%
							  </div>
						</div>
					</td>
					</tr>
					<tr>
						<th colspan="2">Rasva</th>
						<!--<td>Tavoite</td>
					</tr>
					<tr>
						<td><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> <?php echo round($fitbitData[9],1); ?> %</td>
						<td><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> <?php echo round($fitbitData[7],1); ?> %</td>-->
					</tr>
					<td colspan="2">
						<div class="progress" style="margin-top: 8px;">
							  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $fitbitFatGoalprocent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $fitbitFatGoalprocent; ?>%">
							    <span class="sr-only"><?php echo $fitbitFatGoalprocent; ?>% Complete (success)</span>
							    <?php echo $fitbitFatGoalprocent; ?>%
							  </div>
						</div>
					</td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>