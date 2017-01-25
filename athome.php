<?php require_once "lib/session.php";
$titletext="Kotona/poissa";
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

<form id="status" method="get">
<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="btn-group btn-group-lg">
				 <input class="btn btn-default" type="submit" name="status" value="Kotona">
				 <input class="btn btn-default" type="submit" name="status" value="Poissa">
			</div>
		</div>
	</div>
</form>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="lib/js/bootstrap.min.js"></script>
	
</body>
</html>
