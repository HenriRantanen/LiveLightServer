				<!-- Kodinohjaus -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Käyttäjät
						</h3></div>
					<div class="panel-body">
					
					<?php

					include "../lib/connectDB.php";

					$sth = $database -> prepare("SELECT UserName, UserActive, UserAtHome, UserID FROM table_of_users");
					$sth -> execute();

					$users = $sth->fetchAll(PDO::FETCH_ASSOC);

					echo "<table width=\"100%\">";
					foreach($users as $user)
					{
						if($user['UserActive'] == "1" && $user['UserName'] != "admin")
						{
							list($firstName) = explode(' ', $user['UserName']);
							echo '<tr style="height: 30px;">';
							echo "<td>";
							if($user['UserAtHome'] == "1")
							{
								$userStatus = "checked";
							}
							else
							{
								$userStatus = "";
							}

							echo '<input class="form-control" type="checkbox" data-label-width="10" data-size="mini" data-on-color="" data-off-color="" data-off-text="Poissa"  data-on-text="Kotona" '.$userStatus.'>';

							echo "</td>";
							echo "<td><h4>".$firstName."</h4></td>";
							echo "</tr>";
						}						
					}
					echo "</table>";
					?>
				</div>
				</div>

				
				
			    
			    