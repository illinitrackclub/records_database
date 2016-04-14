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
	
	//Do the same for the meets.
	$meets = getMeet();
	foreach($meets as $m)
	{
		$meet2[$m['id']] = $m;
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ITC | Performance Lists</title>
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
<?php printNav('performances'); ?>
  
    <!-- Begin Container -->
    <div class="container">
    <?php
		if($_GET['s'] == 'xc')
		{
		?> 
        <ul class="breadcrumb">
          <li><a href="index.php">Home</a> <span class="divider">></span></li>
          <li><a href="performances.php">Performance Lists</a> <span class="divider">></span></li>
          <li class="active">Cross Country</li>
        </ul>
        
        <div class="row">
            <h3>Cross Country</h3>
            <div class="span5">
            <h4>Men</h4>
            <?php
				$xcEvents = getEvents(false, "xc");
                foreach($xcEvents as $xe)
                {
                    $topPerf = getResults(false, false, 1, $xe['id'], 10);
                    
                    if(!empty($topPerf))
                    {
						$aid = str_replace(' ', '', $xe['name']);
                        echo "<h5 id=\"$aid\">$xe[name] Top Performances</h5>";
                        echo "<table class=\"table table-bordered table-striped table-condensed\"><thead><tr><th>Name</th><th>Meet</th><th>Time</th></tr></thead><tbody>";
                        
                        foreach($topPerf as $k=>$res)
                        {
                            $n = $people2[($res['name_id'])];
                            $nStr = "<a href=\"profile.php?id=$res[name_id]\">$n[first] $n[last]</a>";
                            $m = $meet2[$res['meet_id']];
                            $mStr = "$m[year] $m[name]";
                            if($res['seconds'] == 0)
                            {
                                $perf = "$res[mark] m";
                            }
                            else
							{
                                $perf = gmdate('i:s', $res['seconds']);
							}
                            echo "<tr><td>$nStr</td><td>$mStr</td><td>$perf</td></tr>";
                        }	
                        echo "</tbody></table><br />";
                    }
                }
            ?>
            
            </div>
            <div class="span5">
            <h4>Women</h4>
            <?php
                foreach($xcEvents as $xe)
                {
                    $topPerf = getResults(false, false, 2, $xe['id'], 10);
                    
                    if(!empty($topPerf))
                    {
                        echo "<h5>$xe[name] Top Performances</h5>";
                        echo "<table class=\"table table-bordered table-striped table-condensed\"><thead><tr><th>Name</th><th>Meet</th><th>Time</th></tr></thead><tbody>";
                        
                        foreach($topPerf as $res)
                        {
                            $n = $people2[($res['name_id'])];
                            $nStr = "<a href=\"profile.php?id=$res[name_id]\">$n[first] $n[last]</a>";
                            $m = $meet2[$res['meet_id']];
                            $mStr = "$m[year] $m[name]";
                            if($res['seconds'] == 0)
                            {
                                $perf = "$res[mark] m";
                            }
                            else
                                $perf = gmdate('i:s', $res['seconds']);
                            
                            echo "<tr><td>$nStr</td><td>$mStr</td><td>$perf</td></tr>";
                        }	
                        echo "</tbody></table><br />";
                    }
                }
 		/* end XC performances */
		}
		else if($_GET['s'] == 'it')
		{
		$itEvents = getEvents(false, "it");	
		?>
        <ul class="breadcrumb">
          <li><a href="index.php">Home</a> <span class="divider">></span></li>
          <li><a href="performances.php">Performance Lists</a> <span class="divider">></span></li>
          <li class="active">Indoor Track</li>
        </ul>
        
        <div class="row">
		   	<h3>Indoor Track</h3>
            <div class="span5">
            <h4>Men</h4>
            <?php
                foreach($itEvents as $ite)
                {
                    $topPerf = getResults(false, false, 1, $ite['id'], 10);
                    $aid = str_replace(' ', '', $ite['name']);
                    if(!empty($topPerf))
                    {
                        echo "<h5 id=\"$aid\">$ite[name] Top Performances</h5>";
                        $title = 'Time';
						if($topPerf[0]['seconds'] == 0)
							$title = 'Mark';
                        echo "<table class=\"table table-bordered table-striped table-condensed\"><thead><tr><th>Name</th><th>Meet</th><th>$title</th></tr></thead><tbody>";
	                        
                        foreach($topPerf as $k=>$res)
                        {
                            $n = $people2[($res['name_id'])];
                            $nStr = "<a href=\"profile.php?id=$res[name_id]\">$n[first] $n[last]</a>";
                            $m = $meet2[$res['meet_id']];
                            $mStr = "$m[year] $m[name]";
                            if($res['seconds'] == 0)
                            {
                                $perf = "$res[mark] m";
                            }
                            else
							{
                                $perf = gmdate('i:s', $res['seconds']);
								$perf .= '.'.str_pad($res['ms'], 2, '0', STR_PAD_LEFT);
							}
                            echo "<tr><td>$nStr</td><td>$mStr</td><td>$perf</td></tr>";
                        }	
                        echo "</tbody></table><br />";
                    }
                }
            ?>
            
            </div>
            <div class="span5">
            <h4>Women</h4>
            <?php
                foreach($itEvents as $ite)
                {
                    $topPerf = getResults(false, false, 2, $ite['id'], 10);
                    
                    if(!empty($topPerf))
                    {
                        echo "<h5>$ite[name] Top Performances</h5>";
						
						$title = 'Time';
						if($topPerf[0]['seconds'] == 0)
							$title = 'Mark';
                        echo "<table class=\"table table-bordered table-striped table-condensed\"><thead><tr><th>Name</th><th>Meet</th><th>$title</th></tr></thead><tbody>";
						
                        foreach($topPerf as $res)
                        {
                            $n = $people2[($res['name_id'])];
                            $nStr = "<a href=\"profile.php?id=$res[name_id]\">$n[first] $n[last]</a>";
                            $m = $meet2[$res['meet_id']];
                            $mStr = "$m[year] $m[name]";
                            if($res['seconds'] == 0)
                            {
                                $perf = "$res[mark] m";
                            }
                            else
                            {
                                $perf = gmdate('i:s', $res['seconds']);
								$perf .= '.'.str_pad($res['ms'], 2, '0', STR_PAD_LEFT);
							}
                            echo "<tr><td>$nStr</td><td>$mStr</td><td>$perf</td></tr>";
                        }	
                        echo "</tbody></table><br />";
                    }
                }
			/*end indoor track performances*/	
		}
		else if($_GET['s'] == 'ot')
		{
		?>
		<ul class="breadcrumb">
          <li><a href="index.php">Home</a> <span class="divider">></span></li>
          <li><a href="performances.php">Performance Lists</a> <span class="divider">></span></li>
          <li class="active">Outdoor Track</li>
        </ul>
        
        <div class="row">
            <div class="span5">
			<h4>Men</h4>
			<?php
            $otEvents = getEvents(false, "ot");	
                foreach($otEvents as $ote)
                   {
                        $topPerf = getResults(false, false, 1, $ote['id'], 10);
                         $aid = str_replace(' ', '', $ote['name']);
                        if(!empty($topPerf))
                        {
                            echo "<h5 id=\"$aid\">$ote[name] Top Performances</h5>";
                            $title = 'Time';
                            if($topPerf[0]['seconds'] == 0)
                                $title = 'Mark';
                            echo "<table class=\"table table-bordered table-striped table-condensed\"><thead><tr><th>Name</th><th>Meet</th><th>$title</th></tr></thead><tbody>";
                                
                            foreach($topPerf as $k=>$res)
                            {
                                $n = $people2[($res['name_id'])];
                                $nStr = "<a href=\"profile.php?id=$res[name_id]\">$n[first] $n[last]</a>";
                                $m = $meet2[$res['meet_id']];
                                $mStr = "$m[year] $m[name]";
								$mStr = htmlspecialchars($mStr);
                                if($res['seconds'] == 0)
                                {
                                    $perf = "$res[mark] m";
                                }
                                else
                                {
                                    $perf = gmdate('i:s', $res['seconds']);
                                    $perf .= '.'.str_pad($res['ms'], 2, '0', STR_PAD_LEFT);
                                }
                                echo "<tr><td>$nStr</td><td>$mStr</td><td>$perf</td></tr>";
                            }	
                            echo "</tbody></table><br />";
                        }
                    }
                ?>
            </div>
                <div class="span5">
                <h4>Women</h4>
                <?php
                    foreach($otEvents as $ote)
                    {
                        $topPerf = getResults(false, false, 2, $ote['id'], 10);
                        
                        if(!empty($topPerf))
                        {
                            echo "<h5>$ote[name] Top Performances</h5>";
                            
                            $title = 'Time';
                            if($topPerf[0]['seconds'] == 0)
                                $title = 'Mark';
                            echo "<table class=\"table table-bordered table-striped table-condensed\"><thead><tr><th>Name</th><th>Meet</th><th>$title</th></tr></thead><tbody>";
                            
                            foreach($topPerf as $res)
                            {
                                $n = $people2[($res['name_id'])];
                                $nStr = "<a href=\"profile.php?id=$res[name_id]\">$n[first] $n[last]</a>";
                                $m = $meet2[$res['meet_id']];
                                $mStr = "$m[year] $m[name]";
								$mStr = htmlspecialchars($mStr); //handle "&" in some meet names
                                if($res['seconds'] == 0)
                                {
                                    $perf = "$res[mark] m";
                                }
                                else
                                {
                                    $perf = gmdate('i:s', $res['seconds']);
                                    $perf .= '.'.str_pad($res['ms'], 2, '0', STR_PAD_LEFT);
                                }
                                echo "<tr><td>$nStr</td><td>$mStr</td><td>$perf</td></tr>";
                            }	
                            echo "</tbody></table><br />";
                        }
                    }
                /*end outdoor track performances*/
			}
		else
		
		{
		?>
       	<ul class="breadcrumb">
          <li><a href="index.php">Home</a> <span class="divider">></span></li>
          <li class="active">Performance Lists</li>
        </ul>
        
        <div class="row">
            <div class="span5">
            <a href="performances.php?s=xc">Cross Country</a><br>            
            <a href="performances.php?s=it">Indoor Track</a><br>
            <a href="performances.php?s=ot">Outdoor Track</a><br>    
            </div>
       </div>           
        <?php
		
		
		}	
	?>
    

        
        </div>
    
    </div> <!-- end row -->
   
    <?php
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	echo 'Page generated in '.$total_time.' seconds.';
	?>    
    </div> <!-- End Container -->
    
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>