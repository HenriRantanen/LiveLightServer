<?php	
	session_start();
	
	if(isset($_SESSION["UserID"]))
	{
		// Database connection
		require_once "connectDB.php";
		require_once "eventFunctions.php";
	
		$userID = $_SESSION["UserID"];
			
		// Ask for the user info
		$sth = $database -> prepare("SELECT UserActive FROM table_of_users WHERE UserID = $userID");
		$sth -> execute();

		$user = $sth->fetch(PDO::FETCH_ASSOC);
		
		if($user["UserActive"] === "1")
		{
			if(isset($_GET["lamp"]) && isset($_GET["color"]))
			{
				$color = strtoupper($_GET["color"]);
				$lamp = $_GET["lamp"];
				
				echo "<script>window.close();</script>";
			
				setLamp($lamp, $color);
			}	
		}
		else
		{
			$login = false;
		}
	}
	else
	{
		$login = false;
	}
?>
