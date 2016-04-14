<!DOCTYPE html>
<?php
  //start the session and include user data
  session_start();  
  include_once('includes/config.php');
  include_once('includes/functions.php');
  connectToDB();

  //Determine which events have been ran and sort into an array based on event_id

?>
<html>
  <head>
    <title>ITC | Records Home</title>
    <meta charset="UTF-8">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.cerulaen.min.css" rel="stylesheet" media="screen">
    <link href="css/styles.css" rel="stylesheet" media="screen">
    <link href="css/token-input.css" rel="stylesheet" type="text/css" />
    
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
  
<?php printNav('index');?>
  
    <!-- Begin Container -->
    <div class="container">
    <ul class="breadcrumb">
      <li><a href="index.php">Home</a> <span class="divider">></span></li>
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
	<div class="row">
    	<div class="span6">
        	<p>Welcome to the records database! This is a work in progress. Play around with the links at the top and see your past performances with the club!
            </p>
            Search profiles:<br>
            <form name="namesearch" id="namesearch" action="profile.php" method="get">
                	<input type="text" name="id" id="name-input" size="20" class="search-query" /><input type="submit" value="Go" />
            </form>
            
    	</div>
        <div class="span4">
        <h4>Latest Results</h4>
		<ul>
		<?php
			$results = getMeet(false, false, false, 5, true);
			foreach ($results as $r)
			{
				echo "<li><a href=\"$r[resultsURL]\">$r[name] $r[year]</a></li>";		
			}
		?>
        </ul>
        
        <br><br>
        <h4>Most Recent Records</h4>
        <ul>
		<?php
			$records = getRecentRecords(5);		

			foreach($records as $r)
			{
				if($r['mark'] > 0)
					echo "<li>$r[event_name] | $r[mark] m | <a href =\"profile.php?id=$r[name_id]\">$r[first] $r[last]</a></li>";
				else
				{
					$time = formatTime($r['seconds'], $r['ms'], $r['event_id']);
					echo "<li>$r[event_name] | $time s | <a href =\"profile.php?id=$r[name_id]\">$r[first] $r[last]</a></li>";
				}
			}
		?>
        </ul>
		<br /><br />
		<h4>Oldest Club Records</h4>
		<ul>
		<?php
			$records = getRecentRecords(5, TRUE);			
			foreach($records as $r)
			{
				if($r['mark'] > 0)
					echo "<li>$r[event_name] | $r[mark] m | <a href =\"profile.php?id=$r[name_id]\">$r[first] $r[last]</a> ($r[date])</li>";
				else
				{
					$time = formatTime($r['seconds'], $r['ms'], $r['event_id']);
					echo "<li>$r[event_name] | $time s | <a href =\"profile.php?id=$r[name_id]\">$r[first] $r[last]</a> ($r[date])</li>";
				}
			}
		?>
		</ul>
        </div>
    </div>

	
        </div> <!-- end container -->

  </body>
</html>