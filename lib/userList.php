<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Käyttäjätilit</h3>
	</div>
	<div class="panel-body">
		<table width="100%">
		<?php

		include "connectDB.php";

		$sth = $database -> prepare("SELECT UserName, UserActive, UserAtHome FROM table_of_users");
		$sth -> execute();

		$users = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($users as $user)
		{
			if($user['UserActive'] == "1"){$status = "<span title='Aktiivinen' class='glyphicon glyphicon-ok' aria-hidden='true'></span>";}
			else {$status = "<span title='Käyttäjätili pois käytöstä' class='glyphicon glyphicon-remove' aria-hidden='true'></span>";}
			
			if($user['UserAtHome'] == "1"){$icon = "<span title='Kotona' class='glyphicon glyphicon-home' aria-hidden='true'></span> Kotona";}
			else {$icon = "<span title='Poissa' class='glyphicon glyphicon-globe' aria-hidden='true'></span> Poissa";}
			
			echo "<tr>";
			echo "<th width=\"66%\">".$user['UserName']."</th>";
			echo "<td>".$status."</td>";	
			echo "<td>".$icon."</td>";				
			echo "</tr>";
		}
		?>
		</table>
	</div>
	<div class="panel-footer">

	</div>
</div>