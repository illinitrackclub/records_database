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
	
	//Get the list of current records from the events table
	$rnew = getRecords();
	
	//print_r($rnew);
	
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

  <?php
	foreach($rnew as $k=>$r)
	{
		//print the first part of the table
		if($k == 'xc')
		{
			?>
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
		}
		elseif($k == 'it')
		{
			?>
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
		}
		elseif($k == 'ot')
		{
			?>
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
		}
		//loop through each event and display the records
		foreach($r as $e)
		{
			//format the performance correctly. Check if empty / a relay
			if(!empty($e['record_m']))
			{
				$r_m = $e['record_m'];
				$perf_m = formatPerf($r_m['seconds'], $r_m['ms'], $r_m['mark'], $e['id']);
				$mStr = "$r_m[last], $r_m[first] <strong>$perf_m</strong>";
			}
			else
			{
				$mStr = "---------";
			}
			if(!empty($e['record_w']))
			{
				$r_w = $e['record_w'];
				$perf_w = formatPerf($r_w['seconds'], $r_w['ms'], $r_w['mark'], $e['id']);
				$wStr = $r_w['last'].", $r_w[first] <strong>$perf_w</strong>";			
			}
			else
			{
				$wStr = "---------";
			}
			
			//echo the performances
			$aText = str_replace(' ', '', $e['name']);
			$eText = "<a href=\"performances.php?s=$e[season]#$aText \">$e[name]</a>";
			echo "<tr><td>$mStr</td><td>$eText</td><td>$wStr</td></tr>";
		
		}
	
	
	
		echo "</tbody></table></div></div></section>";
	}	//end foreach
  
		//Page generated time
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