<?php
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
	
	//Re-do People array to assign the key of the assosc array from the row id. Probably inefficient and should be changed in the function instead of here.
	//The goal is to reduce queries to the DB.
	$people = getPeople();
	foreach($people as $p)
	{
		$people2[$p['id']] = $p;
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ITC | Club Records</title>
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
<?php printNav('records'); ?>
  
    <!-- Begin Container -->
    <div class="container">
    <ul class="breadcrumb">
      <li><a href="index.php">Home</a> <span class="divider">></span></li>
  	  <li class="active">Club Records</li>
    </ul>
  
<!-- subnav disabled for now, figure out code later  
      <header>
          <h1>Club Records</h1>
      	<div class="subnav">
         <ul class="nav nav-pills">
          <li><a href="#xc">Cross Country</a></li>
          <li><a href="#it">Indoor Track</a></li>
          <li><a href="#ot">Outdoor Track</a></li>
         </ul>
      	</div>
  	</header>  
  -->
  <header class="page-header">
    <h2>Club Records</h2>
  </header>

	<section id="xc">
    	<div class="page-header">
    	 <h3>Cross Country</h3>
    	</div>
        
        <div class="row">
        <div class="span8 offset1">
        <table class="table table-bordered table-striped table-condensed">
        <thead>
         <tr><th>Men</th><th>Event</th><th>Women</th></tr>
        </thead>
        <tbody>
        <?php
			$xcEvents = getEvents(false, 'xc');
			foreach($xcEvents as $xce)
			{
				//get the top performance for this event. Probably not the most efficient way to do it, tons of queries to the DB. Redo somehow? Maybe sort somehow to reduce hits.
				$menPerf = getResults(false, false, 1, $xce['id'], 1);
				$wPerf = getResults(false, false, 2, $xce['id'], 1);
				
				if(!empty($menPerf))
				{
					$p = $people2[$menPerf['name_id']];
					$time = formatTime($menPerf['seconds'], 0, $xce['id']);
					$menStr = "$p[last], $p[first] <strong>$time</strong>";
				}
				else
					$menStr = "-----";
					
				if(!empty($wPerf))
				{
					$p = $people2[$wPerf['name_id']];
					$time = formatTime($wPerf['seconds'], 0, $xce['id']);
					$wStr = "$p[last], $p[first] <strong>$time</strong>";
				}
				else
					$wStr = "-----";
		
				echo "<tr><td>$menStr</td><td>$xce[name]</td><td>$wStr</td></tr>";				
			}	
		?>
         </tbody>  
        </table>
     </div>
     </div>
   </section>

	<section id="it">
    	<div class="page-header">
    	 <h3>Indoor Track</h3>
    	</div>
        
        <div class="row">
        <div class="span8 offset1">
        <table class="table table-bordered table-striped table-condensed">
        <thead>
         <tr><th>Men</th><th>Event</th><th>Women</th></tr>
        </thead>
        <tbody>
        <?php
			$itEvents = getEvents(false, 'it');
			foreach($itEvents as $ite)
			{
				//get the top performance for this event. Probably not the most efficient way to do it, tons of queries to the DB. Redo somehow? Maybe sort somehow to reduce hits.
				if($ite['relay'] == 1)
				{
					//Performance is a relay. Pull from a different table.
					$mStr = "--Relays not yet imported--";
					$wStr = "--Relays not yet imported--";
				}
				else
				{
					$menPerf = getResults(false, false, 1, $ite['id'], 1);
					$wPerf = getResults(false, false, 2, $ite['id'], 1);
				
				
					if(!empty($menPerf))
					{
						$p = getPeople($menPerf['name_id']);
						if($menPerf['seconds'] == 0)
							$perf = "$menPerf[mark] m";
						else
						{
							$perf = formatTime($menPerf['seconds'], $menPerf['ms'], $ite['id']);
						}
						$mStr = "$p[last], $p[first] <strong>$perf</strong>";
					}
					else
						$mStr = "-----";
						
					if(!empty($wPerf))
					{
						$p = getPeople($wPerf['name_id']);
						if($wPerf['seconds'] == 0)
							$perf = "$wPerf[mark] m";
						else
						{
							$perf = formatTime($wPerf['seconds'], $wPerf['ms'], $ite['id']);
						}
						$wStr = "$p[last], $p[first] <strong>$perf</strong>";
					}
					else
						$wStr = "-----";
				}			
				echo "<tr><td>$mStr</td><td>$ite[name]</td><td>$wStr</td></tr>";
			}	
		?>         
        </tbody>
        </table>
      </div>
      </div> 
    </section>
    
	<section id="ot">
    	<div class="page-header">
    	 <h3>Outdoor Track</h3>
    	</div>
        
        <div class="row">
        <div class="span8 offset1">
        <table class="table table-bordered table-striped table-condensed">
        <thead>
         <tr><th>Men</th><th>Event</th><th>Women</th></tr>
        </thead>
        <tbody>
        <?php
			$otEvents = getEvents(false, 'ot');
			foreach($otEvents as $ote)
			{
				//get the top performance for this event. Probably not the most efficient way to do it, tons of queries to the DB. Redo somehow? Maybe sort somehow to reduce hits.
				if($ote['relay'] == 1)
				{
					//Performance is a relay. Pull from a different table.
					$mStr = "--Relays not yet imported--";
					$wStr = "--Relays not yet imported--";
				}
				else
				{
					$menPerf = getResults(false, false, 1, $ote['id'], 1);
					$wPerf = getResults(false, false, 2, $ote['id'], 1);
				
				
					if(!empty($menPerf))
					{
						$p = getPeople($menPerf['name_id']);
						if($menPerf['seconds'] == 0)
							$perf = "$menPerf[mark] m";
						else
						{
							$perf = formatTime($menPerf['seconds'], $menPerf['ms'], $ote['id']);
						}
						$mStr = "$p[last], $p[first] <strong>$perf</strong>";
					}
					else
						$mStr = "-----";
						
					if(!empty($wPerf))
					{
						$p = getPeople($wPerf['name_id']);
						if($wPerf['seconds'] == 0)
							$perf = "$wPerf[mark] m";
						else
						{
							$perf = formatTime($wPerf['seconds'], $wPerf['ms'], $ote['id']);
						}
						$wStr = "$p[last], $p[first] <strong>$perf</strong>";
					}
					else
						$wStr = "-----";
				}			
				echo "<tr><td>$mStr</td><td>$ote[name]</td><td>$wStr</td></tr>";
			}	
		?>         
        </tbody>
        </table>
      </div>
      </div> 
    </section>
    
    <?php
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4);
		echo 'Page generated in '.$total_time.' seconds.';
	?>
        </div>
      <!-- end container -->

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>