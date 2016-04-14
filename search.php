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
	
	//Search the db for the requested string, if >=3 characters

	
	
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ITC | Search</title>
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
<? printNav('performances'); ?>
  
    <!-- Begin Container -->
    <div class="container">

       	<ul class="breadcrumb">
          <li><a href="index.php">Home</a> <span class="divider">></span></li>
          <li class="active">Search</li>
        </ul>
        
        <div class="row">
            <div class="span5">
            <? echo "You have searched for <strong>$_GET[s]</strong>. The search function is not yet active, please go back and use the autocomplete box to search for a name."; ?>
                    
            </div>    
    </div> <!-- end row -->
   
    <?
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	echo '<br />Page generated in '.$total_time.' seconds.';
	?>    
    </div> <!-- End Container -->
    
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>