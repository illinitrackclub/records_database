<?
	//start the session and include user data
	session_start();	
	include_once('includes/config.php');
	include_once('includes/functions.php');
	connectToDB();

	//Determine which events have been ran and sort into an array based on event_id

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ITC: Performance Lists</title>
<link href="includes/styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="main">
    <div id="header">
    <h2>Illinois Track Club Records & Results</h2>
    <br /><br /><br />
    <div style="text-align:center">
    <a href="index.php">Home</a> | <a href="profile.php">Runner Profiles</a> | <a href="records.php">Club Records</a> | <a href="performances.php">Performance Lists</a> | <a href="meets.php">Meet Results</a> | <a href="http://illinoistrackclub.com">ITC Home</a></div>
    </div>
    <div id="content">
    <a href="index.php">Home</a> > <a href="performances.php">Performance Lists</a> > 

    <?	
	$xcEvents = getEvents(false, "xc");	
	foreach($xcEvents as $xe)
	{
		$topPerf = getResults(false, false, 1, $xe['id'], 10);
				
		echo "<h2>$xe[name] Top 10 Times</h2>";
		echo "<table><tr><td>Name</td><td>Meet</td><td>Time</td></tr>";
		
		foreach($topPerf as $res)
		{
			$n = getPeople($res['name_id']);
			$nStr = "$n[first] $n[last]";
			$m = getMeet($res['meet_id']);
			$mStr = "$m[year] $m[name]";
			if($res['seconds'] == 0)
			{
				$perf = "$res[mark] m";
			}
			else
				$perf = gmdate('i:s', $res['seconds']);
			
			echo "<tr><td> $nStr </td><td>$mStr</td><td>$perf</td></tr>";
		}	
		echo "</table><br /><br />";		
	}

    ?>
    </div>
    <div id="footer">
    	<a href="index.php">Home</a> | <a href="profile.php">Runner Profiles</a> | <a href="records.php">Club Records</a> | <a href="performances.php">Performance Lists</a> | <a href="meets.php">Meet Results</a> | <a href="http://illinoistrackclub.com">ITC Home</a></div>
    </div>
</div>
</body>
</html>