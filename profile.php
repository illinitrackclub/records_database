<?
	//Calculate page load time
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$start = $time;

	//start the session and include user data
	session_start();	
	include_once('includes/config.php');
	include_once('includes/functions.php');
	connectToDB();
	
	//get the performances for the user
	$nameID = $_GET['id'];
	
	//Check if an integer for id. If not, someone probably hit enter and started searching. Redirect to search page.
	if(!empty($_GET['id']) && !is_numeric($_GET['id']))
	{
		echo "<h1>Name not found</h1>";
		header("Location: search.php?s=$nameID");
	}
	
	if(!empty($nameID))
	{
		$p = getPeople($nameID);
		$results = getResults(false, $nameID, false, false);
		
		//Determine which events have been ran and sort into an array based on event_id
		$events = array();
		
		//Sort performances into arrays sorted by season, event, then perf.
		foreach($results as $k=>$r)
		{
			$sorted[$r['season']][$r['event_id']][] = $r;
			$events[] = $r['event_id'];	//not really used (yet?)
			
			//Generate a list of PRs's?
			
		}		
		$titleText = "ITC | $p[first] $p[last]'s Profile";
		
		//Generate a list of PR's
		$prs = array();
		//print_r($sorted);
		$eventU = array_unique($events);
		//print_r($eventU);
	}
	else
	{
		$p = getPeople();
		$titleText = "ITC | Athlete Profiles";
		
		//Apply filters to data, if applicable.
		$m = $_GET['m'];
		$f = $_GET['f'];
		$e = $_GET['e'];

		if(($m == TRUE) || ($f == TRUE) || ($e == TRUE))
		{
			//Loop through the results and if a filter matches, add it to the new array.
			//NOTE: Only checs via "OR" function now. e.g. if m and f were both true, all showed. Fix somehow later.
			foreach($p as $p2)
			{
				if($e == TRUE)
				{
					if($p2['elite'] == 	1)
					{
						$filt[] = $p2;
						continue;
					}
				}
				
				if($m == TRUE)
				{
					if($p2['sex'] == 'm')
					{
						$filt[] = $p2;
						continue;
					}
				}
				
				if($f == TRUE)
				{
					if($p2['sex'] == 'f')
					{
						$filt[] = $p2;
						continue;
					}
				}		
			}
			
			$names = $filt;	
		}
		else
		{
			$names = $p;
		}
	}


?>

<!DOCTYPE html>
<html>
  <head>
    <title><?=$titleText ?></title>
    <meta charset="UTF-8">
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Bootstrap -->
    
    <link href="css/bootstrap.cerulaen.min.css" rel="stylesheet" media="screen">
    <link href="css/styles.css" rel="stylesheet" media="screen">
    <link href="css/token-input.css" rel="stylesheet" media="screen">
    
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="js/jquery.tokeninput.js"></script>
 	<script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
                    $(document).ready(function() {
                        $("#name-input").tokenInput("http://illinoistrackclub.com/results/json.php?t=n", {
                            tokenLimit: 1
                        });
                    });
     </script>

  </head>
  <body data-spy="scroll" data-target=".subnav" data-offset="80">
	<?php include_once("includes/ga.php") ?>
     
    <header class="container">
    	<h1>Illinois Track Club Records &amp; Results</h1>
  	</header>
      <!-- Navigation -->  
<? printNav('profile'); ?>
  
    <!-- Begin Container -->
    <div class="container">
    <ul class="breadcrumb">
      <li><a href="index.php">Home</a> <span class="divider">></span></li>
  	  <li><a href="profile.php">Profiles</a> <span class="divider">></span></li>
      <li class="active"><? if (empty($_GET['id'])) echo "All Profiles"; else echo "$p[first] $p[last]"; ?></li>
    </ul>
    <?
    	if(empty($nameID))
		{
			?>
			<h2>Runner Profiles</h2>
                        <p>Search profiles:
            <form name="namesearch" id="namesearch" action="profile.php" method="get">
                	<input type="text" name="id" id="name-input" size="20" class="search-query" /><input type="submit" value="Go" />
            </form></p>
                        <p>Click a name to view the running history of the athlete with the Illinois Track Club<br><br>
				Filters: <a href="profile.php">All</a> | <a href="?m=true">Men</a> | <a href="?f=true">Women</a> | <a href="?e=true">Illinois Elite</a> <br></p>
            
            
            <div class="row">
            	<div class="span7 offset1">
                    <table class="table table-bordered table-condensed table-striped">
                        <tbody>
                            <tr>
                            <?
								//echo out the names, split into 3 columns
								$len = count($names);
								foreach($names as $k=>$p)
								{
									$nameStr = "<td><a href=\"profile.php?id=$p[id]\">$p[first] $p[last]</a></td>";
									if(((($k+1) % 3) == 0) || $len==$k)
									{
										$nameStr.= "</tr>";
										if($k+1<$len)
											$nameStr.="<tr>";
									}
									echo"$nameStr";	
								}
							?>            
                        </tbody>
                    </table>
            	</div>
            </div>
	 <?			
		}
		else
		{
			?>
        	<header class="page-header">
    			<h1><?="$p[first] $p[last]'s Performances" ?></h1>
  			</header>  
            <div class="row">
                        <!-- Maybe include PR's or something? Disabled for now.

                <div class="span4">
                <h3>Personal Records</h3>
                    <table class="table table-bordered table-condensed table-striped">
                        <thead><tr><th>Event</th><th>Performance</th><th>Meet</th></tr></thead>
                        <tbody>
                            <tr><td>5k</td><td>14:30</td><td>NCC 2013</td></tr>
                    </table>
                    </tbody>
                    
                    <? 
					 
					 ?>
    			</div>
   	                   -->  

            <div class="span6">       
            <?	
			//Display Profile for someone.
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
				
				echo "<h3 style=\"text-align:center\">$seasonStr</h3>";

				//display all performances from that season.
				foreach($res as $k=>$rs)
				{
					$event = getEvents($k);
					
					echo "<h4>$event[name]</h4>";
					echo "<table class=\"table table-bordered table-condensed table-striped\"><thead><tr><th></th><th>Performance</th><th>Meet</th></tr><tbody>";
			
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
						
						if($k2 == 0)
						{
							
						}
							
						echo "<tr><td>$num</td><td>$perfStr</td><td>$mStr</td></tr>";		
					}
					echo "</tbody></table>";
					
				}
			}
			
		}
     ?>
    </div>
    </div>
    <?
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4);
		echo 'Page generated in '.$total_time.' seconds.';
	?>
    </div> <!-- end container -->
  
  </body>
</html>
