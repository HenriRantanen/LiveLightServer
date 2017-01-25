<?php require_once "lib/session.php"; 
$titletext="Valaistuseditori";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>
<?php include "lib/head.php"; ?>

<script src="main.js"></script>
</head>

<body onload="init();">

<?php $tab=2; include "lib/navbar.php"; ?>

<form action="lib/engine.php" method="get" id="main" >

<div class="container">
<?php include "lib/pageTitle.php"; ?>

<div class="row clearfix" style="margin-bottom: 20px;">
		<div class="col-md-12 column">
		
<div class="btn-group btn-group-justified" data-toggle="buttons">
  <label class="btn btn-default">
    <input type="checkbox" autocomplete="off" name="light1" id="light1"> Jalkalamppu
  </label>
  <label class="btn btn-default">
    <input type="checkbox" autocomplete="off" name="light2" id="light2"> Kattolamppu
  </label>
  <label class="btn btn-default">
    <input type="checkbox" autocomplete="off" name="light3" id="light3"> Pöytälamppu
  </label>
  <label class="btn btn-default">
    <input type="checkbox" autocomplete="off" name="light4" id="light4"> Seinälista
  </label>
</div>
</div>
</div>

	<div class="row clearfix" style="margin-bottom: 20px;">
		<div class="col-md-12 column">
	<label for="dimmer">Himmennin</label>
		<input type="range" name="dimmer" class="form-control" id="dimmer" min="0" max="100" value="100" onmouseup="updateLights();">
		</div>
		</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			<span class="glyphicon glyphicon-tint" aria-hidden="true"></span>  Värivalitsin
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
               <input type="color" name="colorPicker" class="form-control" style="height: 48px;" value="#7F7F7F" onchange="updateLights('colourPicker');">
		</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
		<span class="glyphicon glyphicon-fire" aria-hidden="true"></span>  Värilämpötila
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
			<label for="cct">Värilämpötila</label>
			<input type="range" name="cct" id="cct" min="2450" max="5500" value="5000" onmouseup="updateLights('cctSlider');">
			<img src="img/cct_grad.png" width="100%" height="5px">
	  </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			<span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>  Kanavasäädin
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		<div class="panel-body">
			<label for="red">Kanava 1</label>
			<input type="range" name="red" id="red"  min="0" max="255" value="128" onmouseup="updateLights('channelSlider');">
			
			<label for="green">Kanava 2</label>
			<input type="range" name="green" id="green"  min="0" max="255" value="<?php echo $green; ?>" onmouseup="updateLights('channelSlider');">
			
			<label for="blue">Kanava 3</label>
			<input type="range" name="blue" id="blue"  min="0" max="255" value="<?php echo $blue; ?>" onmouseup="updateLights('channelSlider');">
			
			<label for="red2">Kanava 4</label>
			<input type="range" name="red2" id="red2"  min="0" max="255" value="<?php echo $red2; ?>" onmouseup="updateLights('channelSlider');">
			
			<label for="green2">Kanava 5</label>
			<input type="range" name="green2" id="green2"  min="0" max="255" value="<?php echo $green2; ?>" onmouseup="updateLights('channelSlider');">
			
			<label for="blue2">Kanava 6</label>
			<input type="range" name="blue2" id="blue2"  min="0" max="255" value="<?php echo $blue2; ?>" onmouseup="updateLights('channelSlider');">
		</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
		<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  HSL-säädin
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
			<label for="hue">Värisävy</label>
			<input type="range" name="hue" id="hue"  min="0" max="360" value="120" onmouseup="updateLights('hslSlider');">
			
			<label for="saturation">Värikylläisyys</label>
			<input type="range" name="saturation" id="saturation"  min="0" max="100" value="100" onmouseup="updateLights('hslSlider');">
			
			<label for="brightness">Kirkkaus</label>
			<input type="range" name="brightness" id="brightness"  min="0" max="100" value="50" onmouseup="updateLights('hslSlider');">
      </div>
    </div>
  </div>
    
</div>
		</div>
	</div>
</div>
</form>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="lib/js/bootstrap.min.js"></script>
	
</body>
</html>
