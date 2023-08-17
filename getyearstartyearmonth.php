<!-- This routine goes through the sales analysis and populates the sales summary tables -->
<!-- It loops through the customers, and then each customers sales analysis. This is to keep the result set size down as it was initially running out of memory -->

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
	
	// Get the current yearmonth

	$query = "SELECT curyearmonth, yearstartmonth FROM system";
	$result = mysqli_query($link, $query) or die ("Error in query: $query. ".mysqli_error($link));

	$row = mysqli_fetch_row($result);
	$curyearmonth = $row[0];
	$yearstartmonth = $row[1];
	
	$curyear = substr($curyearmonth,0,-2);
	$curmonth = substr($curyearmonth,-2);
	
	$yearstartyear = $curyear;
	$tempmonth = $curmonth;
	$monthindex = 0;
	
	// Loop back to get the start of the year ... it doesn't necessarily start in January. I have the year start month, just need the year start year
	
	while( $tempmonth <> $yearstartmonth)
	{
		$tempmonth--;
		$monthindex++;
		
		if ($tempmonth == 0)
		{
			$tempmonth = 12;
			$yearstartyear--;
		}
		echo $tempmonth." ".$monthindex." ";
	}
	
	$yearstartyearmonth = ($yearstartyear * 100) + $tempmonth;
	$ysales0startindex = $tempmonth - 1;
	$ysales1startindex = $ysales0startindex + 12;
	$ysales2startindex = $ysales1startindex + 12;
	
	echo "yearstartyearmonth: ".$yearstartyearmonth." ysales0startindex: ".$ysales0startindex." ysales1startindex: ".$ysales1startindex." ysales2startindex: ".$ysales2startindex;
?>