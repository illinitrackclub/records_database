<?php
	//start the session and include user data
	session_start();	
	include_once('includes/config.php');
	include_once('includes/functions.php');
	connectToDB();
	
	
	//get the performances for the user
	$nameID = $_GET['id'];
	$p = getPeople($nameID);
	$results = getResults(false, $nameID, false, false);
	
	//Determine which events have been ran and sort into an array based on event_id
	$events = array();
		
	//Display the seasons in order of the current season.
	//e.g. IT,OT,XC Jan-March, OT,XC,IT Apr-July, XC,IT,OT Aug-Dec
	if(date('m') <=3)
		$sorted = array('it' => array(),'ot' => array(),'xc' => array());
	elseif(date('m') <=7)
		$sorted = array('ot' => array(),'xc' => array(),'it' => array());
	else
		$sorted = array('xc' => array(),'it' => array(),'ot' => array());
	
	//Sort performances into arrays sorted by season, event, then perf.
	foreach($results as $k=>$r)
	{
		$sorted[$r['season']][$r['event_id']][] = $r;
		$events[] = $r['event_id'];	//not really used (yet?)
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ITC: <?php="$p[first] $p[last]'s"; ?> Running Profile</title>
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
    <a href="index.php">Home</a> > <a href="profile.php">Runner Profiles</a> > 

    <?php
		if(empty($nameID))
		{
			?>
			<h2>Runner Profiles</h2>
            <p>Click a name to view the running history of the athlete with the Illinois Track Club</p>
            <ul>
			<?php
			$people = getPeople(false);
			foreach($people as $p)
			{
				$nameStr = "<a href=\"profile.php?id=$p[id]\">$p[first] $p[last]</a>";
				echo"<li>$nameStr</li>";	
			}
			echo "</ul>";
			
		}
		else
		{
			echo "$p[first] $p[last]";
			echo "<h2>$p[first] $p[last]'s Past Performances</h2>";
			$showms = 1;			
			
			//Sort by season, event, then performance and display the results
			foreach($sorted as $season=>$res)
			{
				if($season == 'xc')
				{
					$seasonStr = "Cross Country";
					$showms = 0;
				}
				elseif($season == 'it')
				{
					$seasonStr = "Indoor Track";
					$showms = 1;
				}
				else if($season == 'ot')
				{
					$seasonStr = "Outdoor Track";
					$showms = 1;	
				}
				else
				{
					$seasonStr = "Other";
					$showms = 1;
				}
				
				echo "<h3>$seasonStr</h3>";

				//display all performances from that season.
				foreach($res as $k=>$rs)
				{
					$event = getEvents($k);
					
					echo "<h3>$event[name]</h3>";
					echo "<table><tr><td></td><td>Performance</td><td>Meet</td></tr>";
			
					//display the performances under each event
					foreach($rs as $k2=>$perf)
					{
						$m = getMeet($perf['meet_id']);
						$mStr = "$m[year] $m[name]";
						$num = $k2+1;
						
						if($perf['seconds'] == 0)
						{
							$perfStr = "$perf[mark] m";
						}
						else
						{
							$perfStr = gmdate('i:s', $perf['seconds']);
							if($showms == 1)
								$perfStr .= '.'.str_pad($perf[ms], 2, '0', STR_PAD_LEFT);
						}
							
						echo "<tr><td>$num)</td><td>$perfStr</td><td>$mStr</td></tr>";		
					}
					echo "</table>";
					
				}
			}
		}
    ?>
    </div>
    <div id="footer">
    	<a href="index.php">Home</a> | <a href="profile.php">Runner Profiles</a> | <a href="records.php">Club Records</a> | <a href="performances.php">Performance Lists</a> | <a href="meets.php">Meet Results</a> | <a href="http://illinoistrackclub.com">ITC Home</a></div>
    </div>
</div>
</body>
</html>