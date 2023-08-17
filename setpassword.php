<!-- This script sets the user password. It accepts two parameters - userid and password.-->

<?php
    require_once 'dblogin.php';	
	
	date_default_timezone_set('Europe/London');
	
	ini_set('log_errors',1);
	ini_set('display_errors',1);
	ini_set('error_log', 'error_log');
	ini_set('memory_limit', '1G');
	
	// Start time
	$start_datetime = date('Y-m-d H:i:s');

	error_reporting(E_ALL);	
	
	// open connection 
    $link = mysqli_connect($host, $user, $pass, $db) or die ("Unable to connect!"); 

	// Enable autocommit
	mysqli_autocommit($link, TRUE);
	
	if (isset($_GET['userid'])) 
	{
		$userid = $_GET['userid'];
	}
	else
	{
		exit("Userid parameter has not been provided");
	}

	if (isset($_GET['password'])) 
	{
		$password = $_GET['password'];
	}
	else
	{
		exit("Password parameter has not been provided");
	}

	$query = "UPDATE users SET users.password = MD5('qm&h".$password."pg!@') WHERE userid = $userid";
	echo $query;
	$result = mysqli_query($link, $query) or die ("Error in query: $query. ".mysqli_error($link));
?>
