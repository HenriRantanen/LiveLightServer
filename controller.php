<?php 
require_once "lib/session.php"; 
require_once "lib/connectDB.php";
require_once "lib/string.php";
require_once "lib/deviceRSSI.php";

$speed = $_POST["speed"];
$controller = $_POST["controller"];
$save = $_POST["save"];

$sth = $database -> prepare("SELECT * FROM table_of_devices");
$sth -> execute();

$devices = $sth->fetchAll(PDO::FETCH_ASSOC);

// If save PWM button has been clicked
if(isset($_POST["speed"]) && isset($_POST["controller"]) && ($save === "pwm"))
{
	$command = "python /var/www/lib/python/setPWM.py $controller $speed";
	
	shell_exec($command);
	
	$sth = $database -> prepare("UPDATE table_of_devices SET DeviceSpeed = $speed WHERE DeviceID = $controller");
	$sth -> execute();
}

if(isset($_POST["controller"]) && $controller != "Valitse")
{
	$sth = $database -> prepare("SELECT * FROM table_of_devices WHERE DeviceID = $controller");
	$sth -> execute();

	$editableDevice = $sth->fetch(PDO::FETCH_ASSOC);
	
	$deviceSelected = true;
}




$titletext="Ohjaimen asetukset";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>

</head>

<body>

<?php include "lib/navbar.php"; ?>

<div class="container">
<?php include "lib/pageTitle.php"; ?>
	<div class="row clearfix">
		<div class="col-md-12">
			<form method="post">
			  <div class="form-group">
				<label for="controller">Valo-ohjain</label>
				<select class="form-control" id="controller" name="controller" onchange="this.form.submit()">
				<option>Valitse</option>
				<?php
				
					foreach($devices as $device)
					{
						if($controller === $device["DeviceID"]){$select = "selected";}
						else {$select = "";}
						
						
						//echo "<option $select value='".$device["DeviceID"])."'>".$device["DeviceName"]."</option>";
						echo "<option $select value='".$device["DeviceID"]."'>".$device["DeviceName"]."</option>";
					}
				?>
				</select>
			  </div>
			
			<div class="<?php if(!($deviceSelected)) echo "hidden";?>">
			
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Tiedot
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
	  <table class="table" style="margin-bottom:0px;">
		 <tr>
		 <th width="33%">Sijainti</th>
		 <td><?php echo $editableDevice["DeviceLocation"]; ?></td>
		 </tr>
		 <tr>
		 <th>MAC-osoite</th>
		 <td><?php echo normalize_mac("0013A200".$editableDevice["DeviceMAC"]); ?></td>
		 </tr>
		 <tr>
		 <th>Signaalitaso</th>
		 <td><?php echo getRSSI($editableDevice["DeviceMAC"]); ?>%</td>
		 </tr>
		  <tr>
		 <th>PWM-taajuus</th>
		 <td><?php 
		 switch($editableDevice["DeviceSpeed"])
		 {
			case 0: echo "Releohjaus, PWM pois päältä"; break;
			case 1: echo "30 Hz"; break;
			case 2: echo "120 Hz"; break;
			case 3: echo "490 Hz"; break;
			case 4: echo "3,9 kHz"; break;
			case 5: echo "31 kHz"; break;
		 }
		 ?></td>
		 </tr>
		 <tr>
		 <th>Käytössä</th>
		 <td><?php if($editableDevice["DeviceActive"]) echo "Kyllä"; 
		 else echo "Pois käytöstä";?></td>
		 </tr>
		</table>
		 </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> PWM-taajuus
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
	  <div class="form-group">
			<label for="controller">PWM-taajuus</label>
			<select class="form-control" id="speed" name="speed">
				<?php
					if($deviceSelected) $speed = $editableDevice["DeviceSpeed"];
				?>
				<option <?php if($speed === "0") echo "selected"; ?> value="0">0 Hz (releille)</option>
				<option <?php if($speed === "1") echo "selected"; ?> value="1">30 Hz</option>
				<option <?php if($speed === "2") echo "selected"; ?> value="2">120 Hz</option>
				<option <?php if($speed === "3") echo "selected"; ?> value="3">490 Hz</option>
				<option <?php if($speed === "4") echo "selected"; ?> value="4">3,9 kHz</option>
				<option <?php if($speed === "5") echo "selected"; ?> value="5">31,4 kHz (oletus)</option>
			</select>
		  </div>
		  
		  <button type="submit" name="save" value="pwm" class="btn btn-default"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Tallenna ohjaimeen</button>
	  </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <span class="glyphicon glyphicon-adjust" aria-hidden="true"></span> Kanavamääritykset
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <div class="row" style="margin-bottom:20px;">
		<p>Määritä minkä värinen valaisin on kytketty ohjauskanavaan.</p>
			<?php
		
			for($i = 1; $i <= 6; $i++)
			{
				$column = "DeviceCH$i";
				$colour = $editableDevice["$column"];
				echo "
			<div class=\"col-sm-2\">
				<label for=\"ch1\">Kanava $i</label>
				<input class=\"form-control\" id=\"ch$i\" type=\"color\" value=\"#$colour\">
			</div>";
			}		

			?>
			
		</div>
		<button type="submit" name="save" value="channels" class="btn btn-default"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Tallenna</button>

		
		</div>
    </div>
  </div>
</div>
		</div>	
		
		
		<div class="<?php if($deviceSelected) echo "hidden";?>">
		<p>Valitse valo-ohjain luettelosta </p>
		</div>
			</form>
		</div>		
	</div>
	</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="lib/js/bootstrap.min.js"></script>
	
</body>
</html>
