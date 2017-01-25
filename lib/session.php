<?php	

	// Inner or outer network?
	if(substr($_SERVER['SERVER_ADDR'],0,7) === substr($_SERVER['REMOTE_ADDR'],0,7))
	{
		// LAN-connection, do nothing
	}
	else
	{
		// WAN-connection
		//$login = false;
		//header('Location: login.php?error=wan');
	}
	
	session_start();
	
	// Database connection
	require_once "connectDB.php";
	
	if(isset($_SESSION["UserID"]))
	{
		$userID = $_SESSION["UserID"];
			
		// Ask for the user info
		$sth = $database -> prepare("SELECT * FROM table_of_users WHERE UserID = $userID");
		$sth -> execute();

		$user = $sth->fetch(PDO::FETCH_ASSOC);
		
		if($user["UserActive"] != "1")
		{
			$login = false;
			header("location: login.php?error=blocked");
		}
		else
		{
			$login = true;
		}
	}
	elseif(isset($_COOKIE["UserID"]))
	{
		$userID = $_COOKIE["UserID"];
		$_SESSION["UserID"] = $userID;
		
		// Ask for the user info
		$sth = $database -> prepare("SELECT * FROM table_of_users WHERE UserID = $userID");
		$sth -> execute();

		$user = $sth->fetch(PDO::FETCH_ASSOC);
		
		if($user["UserActive"] != "1")
		{
			$login = false;
			header("location: login.php?error=blocked");
		}
		else
		{
			$login = true;
		}
	}
	else
	{
		$login = false;
		header("location: login.php");
	}
	
?>