<?php	
	session_start();
	
	if(isset($_SESSION["UserID"]))
	{
		// Database connection
		require_once "connectDB.php";
	
		$userID = $_SESSION["UserID"];
			
		// Ask for the user info
		$sth = $database -> prepare("SELECT UserActive FROM table_of_users WHERE UserID = $userID");
		$sth -> execute();

		$user = $sth->fetch(PDO::FETCH_ASSOC);
		
		if($user["UserActive"] === "1")
		{
			if(isset($_GET["controller"]) && isset($_GET["channel"]) && isset($_GET["value"]))
			{
				$value = $_GET["value"];
				$channel = $_GET["channel"];
				$controller = $_GET["controller"];
				
				echo "<script>window.close();</script>";
				require_once "eventFunctions.php";
				
				setChannel($controller, $channel, $value);
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
