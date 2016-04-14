<?php
//this file should create JSON formatted results (whatever that means)	
	$type = $_GET['t'];
	$season = $_GET['s'];
	
	#connect to the db
	mysql_pconnect("localhost") or die("Could not connect");
	mysql_select_db("illinitrackclub_agprojec") or die("Could not select database");
	
	# Perform the query
	if($type == 'n')
	{
		$search = mysql_real_escape_string($_GET["q"]);
		$query = sprintf("SELECT id,first,last FROM `records_people` WHERE `first` LIKE '%%%s%%' OR `last` LIKE '%%%s%%' ORDER BY last DESC", $search, $search);
				
		$arr = array();
		$rs = mysql_query($query);
			
		# Collect the results, combine the first and last name fields, and perpare the object
		while($obj = mysql_fetch_object($rs)) {
			$obj2 = $obj;
			$obj2->name = $obj->first.' '.$obj->last;
			unset($obj2->first);
			unset($obj2->last);
			$arr[] = $obj2;		
		}		
	}
	else if ($type == 'e')
	{
		if($season == 'other')
		{
			
			
		}
		else
		{
			$query = sprintf("SELECT id,name FROM `records_events` WHERE name LIKE '%%%s%%' and season = '$season' ORDER BY name DESC", mysql_real_escape_string($_GET["q"]));
				
			$arr = array();
			$rs = mysql_query($query);
				
			# Collect the results, combine the first and last name fields, and perpare the object
			while($obj = mysql_fetch_object($rs)) {
				$arr[] = $obj;		
			}
		}
	}
	else if($type == 'm')
	{
		$query = sprintf("SELECT id,name,year,season,date_start,date_end FROM `records_meets` WHERE name LIKE '%%%s%%' ORDER BY name DESC", mysql_real_escape_string($_GET["q"]));
			
		$arr = array();
		$rs = mysql_query($query);
			
		# Collect the results, combine the first and last name fields, and perpare the object
		while($obj = mysql_fetch_object($rs)) {
			$obj2 = $obj;
			$obj2->name = $obj->year.' '.$obj->name;
			unset($obj2->year);
			$arr[] = $obj2;		
		}
	}
	
	# JSON-encode the response
	$json_response = json_encode($arr);
	
	# Optionally: Wrap the response in a callback function for JSONP cross-domain support
	if($_GET["callback"]) {
		$json_response = $_GET["callback"] . "(" . $json_response . ")";
	}
	
	# Return the response
	echo $json_response;

?>