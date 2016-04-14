<?php
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
<title>ITC: Meet Results</title>
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
    <a href="index.php">Home</a> > <a href="performances.php">Meet Results</a> > 

    <?php	
	$allMeets = getMeet();
	$sorted = array();
	foreach($allMeets as $am)
	{
		$sorted[$am['year']][$am['season']][] = $am;
	}
	
	//print_r($sorted);
	
	echo "<ul>";
	foreach($sorted as $year=>$season)
	{
		echo "<li>$year<ul>";
		
		foreach($season as $season=>$meet)
		{
			echo "<li>$season<ul>";
			
			foreach($meet as $m)
			{
				echo "<li>$m[name]<ul>";
					if(!empty($m['resultsURL']))
						echo "<li><a href=\"$m[resultsURL]\">Results</a></li>";
				if(!empty($m['photosURL']))
					echo "<li><a href=\"$m[photosURL]\">Photos</a></li>";
				
				echo"</ul></li>";
			}
			
			echo "</ul></li>";
		}
		
		echo "</ul></li>";
		
	}
	echo "</ul>";
	

    ?>
    </div>
    <div id="footer">
    	<a href="index.php">Home</a> | <a href="profile.php">Runner Profiles</a> | <a href="records.php">Club Records</a> | <a href="performances.php">Performance Lists</a> | <a href="meets.php">Meet Results</a> | <a href="http://illinoistrackclub.com">ITC Home</a></div>
    </div>
</div>
</body>
</html>