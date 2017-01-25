<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Kytketyt laitteet</h3>
	</div>
	<div class="panel-body">
		<table width="100%">
		<?php

		include "connectDB.php";

		$sth = $database -> prepare("SELECT * FROM table_of_devices");
		$sth -> execute();

		$devices = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($devices as $device)
		{
			if($device['DeviceActive'] == "1")
			{
				echo "<tr>";
				echo "<th width=\"66%\">".$device['DeviceName']."</th>";
				echo "<td>
					<div class=\"colour-dot\" style=\"background-color:#".$device['DeviceCH1'].";\"></div>
					<div class=\"colour-dot\" style=\"background-color:#".$device['DeviceCH2'].";\"></div>
					<div class=\"colour-dot\" style=\"background-color:#".$device['DeviceCH3'].";\"></div>
					<div class=\"colour-dot\" style=\"background-color:#".$device['DeviceCH4'].";\"></div>
					<div class=\"colour-dot\" style=\"background-color:#".$device['DeviceCH5'].";\"></div>
					<div class=\"colour-dot\" style=\"background-color:#".$device['DeviceCH6'].";\"></div>
					
					</td>";			
				echo "</tr>";
			}
		}
		?>
		</table>
	</div>
	<div class="panel-footer">

	</div>
</div>