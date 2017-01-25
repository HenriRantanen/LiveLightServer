<?php require_once "lib/session.php";
$titletext="Asetukset";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titletext; ?> | LiveLight Lion</title>

<?php include "lib/head.php"; ?>

<script src="main.js"></script>
</head>

<body>

<?php $tab=5; include "lib/navbar.php"; ?>

<div class="container">
	<?php include "lib/pageTitle.php"; ?>
	<div class="row clearfix">
	<div class="col-md-4 column">
			<h3>
				Palvelin
			</h3>
			
			<div class="btn-group btn-group-justified" role="group" style="margin-bottom:20px;">
				<a type="button" class="btn btn-default" href="power.php?cmd=restart"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Käynnistä uudelleen</a>
				<a type="button" class="btn btn-default" data-toggle="modal" data-target="#shutdown"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Sammuta palvelin</a>
			</div>
			<?php include "lib/statusList.php";?>
		</div>
		<div class="col-md-4 column">
			<h3>
				Käyttäjät
			</h3>
			<button type="button" class="btn btn-default" style="margin-bottom:20px;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Käyttäjien hallinta</button>
			<?php include "lib/userList.php";?>
			
		</div>
		<div class="col-md-4 column">
			<h3>
				Liitetyt valo-ohjaimet
			</h3>
			<div class="btn-group btn-group-justified" role="group" style="margin-bottom:20px;">
				<a href="controller.php" type="button" class="btn btn-default" ><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Ohjaimen asetukset</a>
				<a href="network.php" type="button" class="btn btn-default" ><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Verkko</a>
			</div>
			<?php include "lib/deviceList.php";?>
		</div>
		
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
		<h3>
			Työkalut
		</h3>
		<ul class="list-unstyled">
		  <li><a href="http://192.168.0.200/phpmyadmin/" title="Tietokantojen hallinta"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> phpMyAdmin</a></li>
		<li><a href="http://192.168.0.200/listLamps.php" title="Listaa lamput"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Listaa lamput</a></li>
		<li><a href="http://192.168.0.200/lib/cron.php?button=list" title="Listaa lamput"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Näytä ajastustyöt</a></li>
		
		</ul>
		
		
		<p>Palvelimen kello sivun lataushetkellä <?php echo date("G:i j.n.Y", time()); ?>
		</div>
		
</div>


<!-- Modal -->
<div class="modal fade" id="shutdown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Sammuta palvelin</h4>
      </div>
      <div class="modal-body">
        <h4>Ookkony ihan varma?</h4>
		<p>Palvelimen uudelleenkäynnistäminen vaatii sen virtajohdon irroittamisen ja kytkemisen takaisin.</p>
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-danger" href="power.php?cmd=shutdown"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Sammuta palvelin</a>
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

