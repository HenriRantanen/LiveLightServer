<?php require_once "lib/session.php";
$titletext="Pikanappulat";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>
	
	<script src="main.js"></script>
</head>

<body>

<?php $tab=1; include "lib/navbar.php"; ?>

<div class="container">
<?php include "lib/pageTitle.php"; ?>
	<div class="row clearfix" style="margin-bottom:20px;">
		<div class="col-md-12 column">
			<label for="green">Himmennin</label>
			<input id="himmennin" type="range" class="form-control" min="0" value="100" max="100" onmouseup="setBrightness()">
		</div>
	</div>
	<div class="row clearfix visible-lg" style="margin-bottom:20px;">
		<?php
			require "lib/connectDB.php";
			
			$sth = $database -> prepare("SELECT LampID, LampColor, LampName FROM table_of_lamps");
			$sth -> execute();
			$lamps = $sth->fetchAll(PDO::FETCH_ASSOC);

			foreach($lamps as $lamp)
			{
				$colorbox = "<div class='col-md-2 column'>
								<label for='light".$lamp["LampID"]."'>".$lamp["LampName"]."</label>
								<input type='color' class='form-control' onchange='change(this)' id='light".$lamp["LampID"]."' value='#".$lamp["LampColor"]."' name='light".$lamp["LampID"]."' style='height: 48px;'>
							</div>";
				echo $colorbox;
			}
		?>
		<!--<div class="col-md-2 column">
			<label for="light1">Kattolista</label>
			<input type="color" class="form-control" onchange="change(this)" id="light1" value="#7F7F7F" name="light1" style="height: 48px;">
		</div>
		<div class="col-md-2 column">
			<label for="light2">Jalkalamppu</label>
			<input type="color" class="form-control" onchange="change(this)" id="light2" value="#7F7F7F" name="light2" style="height: 48px;">
		</div>
		<div class="col-md-2 column">
			<label for="light3">Kattolamppu (RGB)</label>
			<input type="color" class="form-control" onchange="change(this)" id="light3" value="#7F7F7F" name="light3" style="height: 48px;">
		</div>
		<div class="col-md-2 column">
			<label for="light4">Kattolamppu (WW-CW)</label>
			<input type="color" class="form-control" onchange="change()" id="light4" value="#7F7F7F" name="light4" style="height: 48px;">
		</div>
		<div class="col-md-2 column">
			<label for="light5">TV:n tausta</label>
			<input type="color" class="form-control" onchange="change(this)" id="light4" value="#7F7F7F" name="light4" style="height: 48px;">
		</div>
		<div class="col-md-2 column">
			<label for="light6">Yöpöytä</label>
			<input type="color" class="form-control" onchange="change(this)" id="light5" value="#7F7F7F" name="light5" style="height: 48px;">
		</div>
		<div class="col-md-2 column">
			<label for="light6">Kasvivalo</label>
			<input type="color" class="form-control" onchange="change(this)" id="light6" value="#7F7F7F" name="light6" style="height: 48px;">
		</div>-->
	</div>
	
	<div class="row clearfix" style="margin-bottom:20px;">
		<div class="col-md-12 column">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingOne">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					<span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>  Perustilat
					</a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body" style="padding-bottom: 10px;">
						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setAutoLight('enable')">
						<span class="glyphicon glyphicon-time" aria-hidden="true"></span> AutoLight</a>
						
						<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'000000','000000','000000','000000','000000','000000','000000')">
						<span class="glyphicon glyphicon-off" aria-hidden="true"></span> Sammuta valot</a>
						
						<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,
							'EBD9D1','FFE8AC',
							'A7C6D2','4AFF00',
							'40FFFF','FF0000',
							'FFEFAB','FF0000')
						">Päivä</a>
						
						<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,
							'000000','000000',
							'000000','000000',
							'000000','000000',
							'010103','000000')
						">Leffamoodi</a>
			
					</div>
				</div>
			</div>
			
			
		  <div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingThree">
			  <h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span>  Säätilat
				</a>
			  </h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
			  <div class="panel-body" style="padding-bottom: 10px;">
			  
	<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'D2D2FF','FFFAD2','558EFF','08FF00','2FF4FF','C40100','786273')">
		Pilvinen</a>
	
	
		
	<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'ffdfab','ffd787','f9ffa6','8cff00','D7FFE3','FF0100','FFD071')">
		Aurinkoinen</a>
			  </div>
			</div>
		  </div>
		  <div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingFour">
			  <h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
				<span class="glyphicon glyphicon-time" aria-hidden="true"></span>  Vuorokaudenajat
				</a>
			  </h4>
			</div>
			<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
			  <div class="panel-body" style="padding-bottom: 10px;">
				<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'FFE3A5','FFE3A5','FF8E30','B48200','FFE3A5','FFE3A5','FFBA53')">
		Aamu</a>
		
					<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,
						'EBD9D1','FFE8AC',
						'A7C6D2','4AFF00',
						'40FFFF','FF0000',
						'FFEF9C','FF0000')
					">Päivä</a>		
					
	<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'020d4d','555C46','4b4f80','100500','011733','101010','190023')">
		Ilta</a>
		
					<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,
						'030301','000000',
						'000000','000000',
						'000000','030000',
						'000000','000000')
					">Yö</a>
			  </div>
			</div>
		  </div>
		  <div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingTwo">
					<h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					<span class="glyphicon glyphicon-tree-conifer" aria-hidden="true"></span>  Teemat
					</a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
					<div class="panel-body" style="padding-bottom: 10px;">
						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'FF9E34','FF8919','FF930E','FF1C00','FF9E34','FF8919','FF9E34')">
						Hehkulamppu</a>
				
						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'FFD49C','FFD878','FFC755','5BFF00','FFD49C','FFD878','FFD49C')">
						Loisteputki</a>
						
						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="random();">
						Satunnainen</a>

						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'0A3C05','0A3C05','FFFF00','000000','0A3C05','0A3C05','383C2F')">
						Peliteema A</a>

						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'A36CFD','A38AFD','2B80FF','000000','A36CFD','A38AFD','8000FF')">
						Peliteema B</a>
						
						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'0080FF','555C46','000000','00FF00','0080FF','555C46','808080')">
						Kerbal Space Center</a>

						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'060002','5E030A','0A0046','000000','0A0046','5E030A','200010')">
						Pornoluola</a>
						
						<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'000000','000000','40004F','30FF00','000000','000000','000000')">
						Photoedit</a>
						
						<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,
							'690042','000000',
							'4F1E57','000000',
							'FFBEBC','000000',
							'FF0F83','000000')
						">Pornoluola</a>
						
						<a href="#"  class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,
							'F63100','000000',
							'5A7304','000000',
							'4E5C01','000000',
							'893603','000000')
						">testi</a>
						
						<a href="#" class="btn btn-default btn-lg" style="margin-bottom: 5px;" onclick="setColour(0,'FFFECD','FFEF8A','0000FF','57FF00','000000','000000','000000')">
						D65</a>
						
					</div>
				</div>
		  </div>
		</div>	
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
