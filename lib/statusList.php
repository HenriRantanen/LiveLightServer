<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Palvelimen tiedot
					</h3>
				</div>
				<div class="panel-body">
					<table width="100%">
					<tr>
						<th width="33%">Isäntänimi</th>
						<td><?php echo gethostname();?></td>
					</tr>
					<tr>
						<th>IP-osoite</th>
						<td><?php echo $_SERVER['SERVER_ADDR']; ?></td>
					</tr>
					<tr>
						<th>Radiomoduli</th>
						<td><?php include "lib/radioAddress.php";?></td>
					</tr>
					<tr>
						<th>Ajastuspalvelu</th>
						<td>
							<?php
							$cronStatus = substr(shell_exec('/usr/sbin/service cron status'), 0, 16);
							if($cronStatus === "cron is running."){echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Käytössä";}
							else{echo "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Ei käytössä";}
							?>
						</td>
					</tr>
					<tr>
						<th>FTP-palvelin</th>
						<td>
							<?php
							$ftpStatus = substr(shell_exec('/usr/sbin/service proftpd status'), 39, 17);
							if($ftpStatus === "currently running"){echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Käytössä, portti 21";}
							else{echo "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Ei käytössä";}
							?>
						</td>
					</tr>
					<tr>
						<th>HTTP-palvelin</th>
						<td>
							<?php
							$apacheStatus = substr(shell_exec('/usr/sbin/service apache2 status'), 0, 18);
							if($apacheStatus === "Apache2 is running"){echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Käytössä, portti 80";}
							else{echo "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Ei käytössä";}
							?>
						</td>
					</tr>
					<tr>
						<th>SSH-palvelin</th>
						<td>
							<?php
							$sshStatus = substr(shell_exec('/usr/sbin/service ssh status'), 0, 16);
							if($sshStatus === "sshd is running."){echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Käytössä, portti 5022";}
							else{echo "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Ei käytössä";}
							?>
						</td>
					</tr>
					</table>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>