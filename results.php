<?
	//start the session and include user data
	session_start();	
	include_once('includes/config.php');
	include_once('includes/functions.php');
	connectToDB();

	//Determine which events have been ran and sort into an array based on event_id

?>
<!DOCTYPE html>
<html>
  <head>
    <title>ITC | Meet Results</title>
    <meta charset="UTF-8">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.cerulaen.min.css" rel="stylesheet" media="screen">
    <link href="css/styles.css" rel="stylesheet" media="screen">

  </head>
  <body data-spy="scroll" data-target=".subnav" data-offset="80">
	<?php include_once("includes/ga.php") ?> 
    
  	<header class="container">
    	<h1>Illinois Track Club Records &amp; Results</h1>
  	</header>
  
      <!-- Navigation --> 
<? printNav('results'); ?>

<!-- Begin Container -->
    <div class="container">

	

    <?	
	$allMeets = getMeet();
	$sorted = array();
	foreach($allMeets as $am)
	{
		$sorted[$am['season']][$am['year']][] = $am;
	}
	
	//Order the meets into xc, it, ot
	uksort($sorted, "sSort");
	
	//print breadcrumbs
	$res = '<li><a href="results.php">Meet Results</a> <span class="divider">></span></li>';
	  switch($_GET['s'])
	  {
		case 'xc':
			$text = '<li class="active">Cross Country</li></ul>';
			break;
		case 'it':
			$text = '<li class="active">Indoor Track</li></ul>';
			break;
		case 'ot':
			$text = '<li class="active">Outdoor Track</li></ul>';
			break;
		case 'all':
			$text = '<li class="active">All Results</li></ul>';
			break;			
		default:
			$text = '</ul>';
			$res = '<li class="active">Meet Results</li>';
	  }
		echo "<ul class=\"breadcrumb\">
		  <li><a href=\"index.php\">Home</a> <span class=\"divider\">></span></li>".$res.$text;  

	echo "<ul>";
	foreach($sorted as $year=>$season)
	{
		if($_GET['s'] == $year || $_GET['s'] == "all") {  //pulls the season from the url, only displays values from loop if it matches the right season
			
		if($_GET['s'] == 'xc') 				
			$displayseason = "Cross Country Results";	
			
		if($_GET['s'] == 'it') 				
			$displayseason = "Indoor Track Results";
			
		if($_GET['s'] == 'ot') 
			$displayseason = "Outdoor Track Results";
			
		if($_GET['s'] == 'all') {
			if($year == 'xc')
				$displayseason = "Cross Country Results";
			if($year == 'it')
				$displayseason = "Indoor Track Results";
			if($year == 'ot')
				$displayseason = "Outdoor Track Results";
			}
	
		echo "<h3>$displayseason</h3><ul>";
		

		krsort($season);
		foreach($season as $season=>$meet)
		{
			echo "<h4>$season</h4><ul>";
			
			foreach($meet as $m)
			{
				echo "<li><a href=\"\#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$m[name]</a><ul class=\"dropdown-menu results\">";
				if(!empty($m['resultsURL']))
					echo "<li><a href=\"$m[resultsURL]\">Results</a></li>";
				if(!empty($m['splitsURL']))
					echo "<li><a href=\"$m[splitsURL]\">Splits</a></li>";
				if(!empty($m['photosURL']))
					echo "<li><a href=\"$m[photosURL]\">Photos</a></li>";
				if( empty($m['photosURL']) && empty($m['resultsURL']) && empty($m['splitsURL']) )
					echo "<li>No data available.</li>";
				echo"</ul></li>";
			}
			
			echo "</ul></li>";
		}
		
		echo "</ul>";
		
	}
	}
	echo "</ul>";
	
	if(empty($_GET['s']))
		{
	echo '
			<div class="row">
				<div class="span5">
				<a href="results.php?s=xc">Cross Country</a><br>            
				<a href="results.php?s=it">Indoor Track</a><br>
				<a href="results.php?s=ot">Outdoor Track</a><br>    
				</div>
		   </div>   ';        	
		}	

    ?>
     </div> <!-- End Container -->
    
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>