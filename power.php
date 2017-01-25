<?php require_once "lib/session.php"; ?>

<?php

//Function to handle server power status

$request = $_GET["cmd"];

// Time in seconds how long does it take to perform an operation
$resetTime = 100;

switch($request)
{
	case "shutdown":
		$command = "sudo /sbin/shutdown -h now";
		$resetTime = 20;
	break;
	
	case "restart":
		$command = "sudo /sbin/shutdown -r now";
		$resetTime = 60;
	break;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php if($request === "restart"){echo "<meta http-equiv='refresh' content='".($resetTime+1)."; url=index.php' />";}?>

<title>LiveLight Lion</title>
<?php include "lib/head.php"; ?>

<script>
var time = 0;
var max_time = <?php echo ($resetTime*10);?>;

var cinterval;
 
function countdown_timer(){
  time++;
  var process = Math.round(time/max_time*100);
  document.getElementById('process_bar').style.width = process+"%";
  
  if(time == max_time){
    clearInterval(cinterval);
	document.getElementById('process_bar').className  = "progress-bar";
	document.getElementById('process_bar').innerHTML  = "Valmis";
	document.getElementById('shutdownReady').style.display  = "block";
	
  }
}
// 1000 means 0,1 second.
cinterval = setInterval('countdown_timer()', 100);
</script>


</head>

<body>

<?php include "lib/navbar.php"; ?>

<div class="container">
	<?php $titletext= "Asetukset"; include "lib/pageTitle.php"; ?>
	<div class="row clearfix">
		<div class="col-md-12">
			
			<?php if($request === "restart")
			{
				// Delayed redirect to front page
				echo "<h3>Palvelin käynnistyy uudelleen...</h3>";
			}
			elseif($request === "shutdown")
			{
				// Delayed redirect to front page
				echo "<h3>Palvelin sammutetaan</h3>";
			}
			?>
			
			<div class="progress">
			  <div class="progress-bar active progress-bar-striped" id="process_bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
			  </div>
			</div>
			
			<?php if($request === "shutdown")
			{
				echo "
				<div id='shutdownReady' style='display:none;' class=\"alert alert-success alert-dismissible\" role=\"alert\">
				  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
				  <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> <strong>Palvelin on sammutettu</strong> ja virtakaapeli on turvallista irroittaa.
				</div>";
			}
			else
			{
				echo "<p>Sinut ohjataan automaattisesti takaisin etusivulle.</p>";
			}?>
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
<?php shell_exec ($command); ?>
