<?php

session_start();
require_once "lib/connectDB.php";
require_once "lib/password.php";

// Destroy the cookie and session
if(isset($_COOKIE["UserID"]))
{
	setcookie('UserID', null, -1, '/');
	unset($_SESSION["UserID"]);
	session_destroy();
}

//$formUserID = "";
$formUserPW = "";

//$formUserPWfeedback = "";
$formUserIDfeedback = $username = $rememberMe = $warning = $loginSuccess = "";


// If page has been submitted
if("submit" === (isset($_GET["button"]) ? $_GET["button"] : ''))
{
	// Read data from the form
	$username = $_GET["uid"];
	$password = $_GET["pw"];
	$rememberMe = $_GET["stayLogged"];

	// Ask for the user info
	$sth = $database -> prepare("SELECT * FROM table_of_users WHERE UserName='$username'");
	$sth -> execute();

	$user = $sth->fetch(PDO::FETCH_ASSOC);

	//print_r($user);
	
	// Is there user in the database?
	if(empty($user))
	{
		// No user in the database
		$formUserID = "has-error";
		$formUserIDfeedback = "<span class=\"glyphicon glyphicon-remove form-control-feedback\" aria-hidden=\"true\"></span>";
		$warning = "<div class=\"alert alert-danger\" role=\"alert\">
		  <strong><span class=\"glyphicon glyphicon-warning-sign\" aria-hidden=\"true\"></span> Virhe!</strong> Käyttäjää ei löydy.
		</div>";
	}
	else
	{
		// User found!
		$formUserID = "has-success";
		$formUserIDfeedback = "<span class=\"glyphicon glyphicon-ok form-control-feedback\" aria-hidden=\"true\"></span>";
		
		// Is user active?
		if($user["UserActive"])
		{
			// Does the password match the database?
			if(salt($password, $user["UserSalt"]) === $user["UserPW"])
			{
				// Yes, it matches
				$formUserPW = "has-success";
				$formUserPWfeedback = "<span class=\"glyphicon glyphicon-ok form-control-feedback\" aria-hidden=\"true\"></span>";
				
				$loginSuccess = true;
			}
			else
			{
				// No it does not.
				$formUserPW = "has-error";
				$formUserPWfeedback = "<span class=\"glyphicon glyphicon-remove form-control-feedback\" aria-hidden=\"true\"></span>";
				$warning = "<div class=\"alert alert-warning\" role=\"alert\">
				  <strong><span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span> Virhe!</strong> Antamasi salasana on virheellinen.
				</div>";
			}
		}
		else
		{
			$formUserID = "has-warning";
			$formUserIDfeedback = "<span class=\"glyphicon glyphicon-warning-sign form-control-feedback\" aria-hidden=\"true\"></span>";
			$warning = "<div class=\"alert alert-warning\" role=\"alert\">
			  <strong><span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span> Huom:</strong> Käyttäjätili on poistettu käytöstä.
			</div>";
		}
	}
}

// If login was a success, setup cookie
if($loginSuccess)
{
	$_SESSION["UserID"] = $user["UserID"];
	
	// Set cookie access for 30 days if wanted
	if($rememberMe === "on")
	{
		setcookie("UserID", $user["UserID"], time() + (86400 * 30), "/");
	}
	
	header("location: index.php");
}

$titletext="Kirjaudu sisään";
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
	<?php 
	if("wan" === isset($_GET["error"]) ? $_GET["error"] : '')echo "<div class=\"alert alert-info\" role=\"alert\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span> Käyttö on estetty ulkoverkosta, kytke laitteesi langattomaan lähiverkkoon.</div>";
	
	if("blocked" === isset($_GET["error"]) ? $_GET["error"] : '')echo "<div class=\"alert alert-warning\" role=\"alert\"><span class=\"glyphicon glyphicon-warning-sign\" aria-hidden=\"true\"></span> Käyttäjätili on poistettu käytöstä.</div>";
	
	if(isset($_GET["logout"]))echo "<div class=\"alert alert-success\" role=\"alert\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Kirjauduttu ulos onnistuneesti.</div>";
	?>
	
	<?php include "lib/pageTitle.php"; ?>
	<div class="row clearfix">
		<div class="col-md-12">
		
		<?php echo $warning; ?>
		
			<form class="form-horizontal" method="get">
			  <div class="form-group has-feedback <?php echo $formUserID; ?>">
				<label for="inputUserName" class="col-sm-2 control-label">Käyttäjätunnus</label>
				<div class="col-sm-10">
				  <input type="text" class="form-control" id="inputUserName" placeholder="Käyttäjätunnus" name="uid" value="<?php echo $username; ?>">
				  <?php echo $formUserIDfeedback; ?>
				</div>
			  </div>
			  
			  <div class="form-group has-feedback <?php echo $formUserPW; ?>">
				<label for="inputPassword" class="col-sm-2 control-label">Salasana</label>
				<div class="col-sm-10">
				  <input type="password" class="form-control" id="inputPassword" placeholder="Salasana" name="pw">
				  <?php if(isset($formUserPWfeedback)) echo $formUserPWfeedback; ?>
				</div>
			  </div>
			  
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <div class="checkbox">
					<label>
					  <input type="checkbox" name="stayLogged" <?php if($rememberMe === "on") echo "checked";?>> Pysy kirjautuneena tällä laitteella
					</label>
				  </div>
				</div>
			  </div>
			  
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" class="btn btn-default" name="button" value="submit"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Kirjaudu sisään</button>
				</div>
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
