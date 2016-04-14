<?php
	//start the session and include user data
	session_start();	
	include_once('../includes/config.php');
	include_once('../includes/functions.php');
	connectToDB();

	//error_reporting(E_ALL);
	
	//Some variables
	$error = array();
	$info = array();
	$warning = array();
	$success = array();
	$multi = $_GET['multi'];
	if(empty($_GET['n']))
		$n = 5;
	else
		$n = $_GET['n'];
	
	//check the type, set values, and insert rows if applicable
	if(empty($_POST['type']))
		$type = $_GET['type'];
	else
		$type = $_POST['type'];

	if($type == 'person')
	{
		$title = "Add a Person";				
			$length = count($_POST['firstName']);
			//Loop through the posted values and enter into the DB
			//We must check that the required values are included. If any are missing, do not subnit the query, and alert the user
			for($i=0; $i<$length ; $i++)
			{
				//Check for first & last name and submit only if complete
				if(!empty($_POST['firstName'][$i]) && !empty($_POST['lastName'][$i]))
				{
					$newPerson = addPerson($_POST['firstName'][$i], $_POST['lastName'][$i], $_POST['sex'][$i], $_POST['gradYr'][$i], $_POST['email'][$i], $_POST['alumni'][$i], $_POST['elite'][$i]);
					$fullName = $_POST['firstName'][$i].' '.$_POST['lastName'][$i];
					
					if($newPerson === TRUE)
						$success[] = "Success! $fullName, has been added to the database";
					else
						$error[] = "Error! $fullName could not be added to the database. Please check your values and try again.";	
				
				}
				else if(empty($_POST['firstName'][$i]) && empty($_POST['lastName'][$i]))
				{
				}
				else
				{
					$first = $_POST['firstName'][$i];
					$last = $_POST['lastName'][$i];
					$error[] = "Error! There was a problem with row $i. You probably left something blank. First name was \"$first\" and last was \"$last\". Please re-enter the values";
				}
			}
	}
	else if($type == 'meet')
	{
		$title = "Add a Meet";
		
		if(!empty($_POST))
		{
			if(empty($_POST['year']) || empty($_POST['startdate']) || empty($_POST['enddate']) || empty($_POST['name']))
			{
				$error[] = "Error! Please make sure you have filled in all of the required fields";		
			}
			else
			{		
				$meet = addMeet($_POST['year'], $_POST['season'], $_POST['name'], $_POST['startdate'], $_POST['enddate'], $_POST['host'], $_POST['location'], $_POST['course'], $_POST['results'], $_POST['photos'], $_POST['notes'], $_POST['splitsURL']);
				
				if($meet === TRUE)
					$success[] = "Success! $_POST[name] has been added to the meets";
				else
					$error[] = "Error! Could not create your meet. Please try again";
			}
			
		}
	}
	else if ($type == 'performance')
	{
		$title = "Add a Performance";

		//print_r($_POST);
		
		if(!empty($_POST))
		{
			if(empty($_POST['name']) || empty($_POST['meet']) || empty($_POST['event']) || empty($_POST['date']))
			{
				$error[] = "Error! Please make sure you have filled in all of the required fields";		
			}
			else
			{		
				//Convert HH:MM:SS.ms to seconds and ms, if applicable
				if(!empty($_POST['time']))
				{
					$splitMS = explode('.', $_POST['time']);					
					if(empty($splitMS[1]))
						$ms = 0;
					else
						$ms = $splitMS[1];
					
					$splitHMS = explode(':', $splitMS[0]);

					//print_r($splitMS);
					//print_r($splitHMS);

					if(count($splitHMS) == 1)
						$seconds = $splitHMS[0];
					elseif(count($splitHMS) == 2)
						$seconds = 60*$splitHMS[0] + $splitHMS[1];
					elseif(count($splitHMS) == 3)
						$seconds = 60*$splitHMS[0] + 60*$splitHMS[1] + $splitHMS[2];
				}
				else
				{
					$seconds = 0;
					$ms = 0;
				}
					
				if(empty($_POST['mark']))
					$mark = 0;
				else
					$mark = $_POST['mark'];			
			
				$meet = addPerformance($_POST['name'], $_POST['meet'], $_POST['date'], $_POST['event'], $seconds, $ms, $mark, $_POST['wind'], $_POST['postgrad'], $_POST['unattached']);
				
				if($meet == 1)
					$success[] = "Success! The performance of $_POST[time] $_POST[mark] has been added. ";
				else if($meet == 2)
					$success[]  = "Success! The performance of $_POST[time] $_POST[mark] has been added. <strong>New club record</strong>";
				else
					$error[] = "Error! Could not create your meet. Please try again";
			}
			
		}
	}
	else
	{
		$title = "Add...";
	}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>ITC | <?php$title;?></title>
    <meta charset="UTF-8">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <!-- Bootstrap -->
    <link href="../css/bootstrap.cerulaen.min.css" rel="stylesheet" media="screen">
    <link href="../css/styles.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="../css/token-input.css" type="text/css" />
    
	<script src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="../js/jquery.tokeninput.js"></script>
 	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">
                    $(document).ready(function() {
                        $("#name-input").tokenInput("../json.php?t=n", {
                            tokenLimit: 1
                        });
                    });
     </script>
    
  </head>
  <body data-spy="scroll" data-target=".subnav" data-offset="80">
	<?php include_once("../includes/ga.php") ?>
    
  	<header class="container">
    	<h1>Illinois Track Club Records &amp; Results</h1>
  	</header>

	<?php printNav('admin-add');?>

  
  <div class="container">
      <div class="subnav">
      	<div class="nav-collapse">
            <ul class="nav nav-tabs">
            <li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Add...<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="add.php?type=person">Person</a></li>
                <li><a href="add.php?type=meet">Meet</a></li>
                <li><a href="add.php?type=performance">Performance</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Edit...<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="edit.php?type=person">Person</a></li>
                <li><a href="edit.php?type=meet">Meet</a></li>
                <li><a href="edit.php?type=performance">Performance</a></li>
              </ul>
            </li>
              <li><a href="#">Edit Users</a></li>
              <li><a href="#">Forms</a></li>
              <li><a href="#">Miscellaneous</a></li>
              <li class="pull-right"><a href="#">Log out</a></li>
            </ul>
          
         </div>
      </div>
  </div>
  
    <!-- Begin Container -->
    <div class="container">
    <ul class="breadcrumb">
      <li><a href="../index.php">Home</a> <span class="divider">></span></li>
      <li><a href="index.php">Admin</a> <span class="divider">></span></li>    
      <li class="active"><?php=$title; ?></li>
    </ul>

	<div class="row">
    	<div class="span10">
        <?php
			displayMessages($success, $error, $warning, $info);
			
			if($type == 'person') {
				?>
                <h2>Add new People</h2>
                 <form name="input" action="add.php" method="post" id="person" autocomplete="off" class="multiform">
                    <fieldset>
                        
                        <div>
                            <label for="firstName">First Name *</label>
                            <?php
								$tc = 1;
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;		//figure out the tab index
									echo "$i) <input name=\"firstName[]\" type=\"text\" size=\"16\" tabindex=\"$tn\" /><br />";
								}										
								$tc++;
							?>
                        </div>          
                        <div>
                            <label for="lastName">Last name *</label>
                            <?php
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;
									echo "<input name=\"lastName[]\" type=\"text\" size=\"16\" tabindex=\"$tn\" /><br />";
								}
								$tc++;

							?>
                        </div>
                        
                        <div>
							 <label for="sex">Sex</label>
							<?php
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;
                             		echo "<select name=\"sex[]\" tabindex=\"$tn\"><option value=\"m\">Male</option><option value=\"f\">Female</option></select><br />";
									 
								}
								$tc++;
                            ?>
                        </div>
                        <div>
                        	<label for="gradYr">Class</label>
                            <?php
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;
									echo"<input type=\"text\" name=\"gradYr[]\" size=\"5\" tabindex=\"$tn\" /><br />";	
								}
								$tc++;
							?>
                        </div>
                        <div>
                        	<label for="email">Email</label>
                             <?php
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;
									 echo "<input type=\"email\" name=\"email[]\" size=\"20\" tabindex=\"$tn\"/><br />";
								}
								$tc++;

							?>
                           
                        </div>
                        <div>
                            <label for="alumni">Alumni</label>
                             <?php
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;
									echo "<input type=\"hidden\" name=\"alumni[]\" value=\"0\" /><input type=\"checkbox\" name=\"alumn[]\" value=\"1\" tabindex=\"$tn\" /><br />";
								}
								$tc++;
							?>
                        </div>
                        <div>
							<label for="elite">Elite</label>
                              <?php
								for($i=0; $i<$n; $i++)
								{
									$tn = $i*7+$tc;
									echo"<input type=\"hidden\" name=\"elite[]\" value=\"0\" /><input type=\"checkbox\" name=\"elite[]\" value=\"1\" tabindex=\"$tn\" /><br /> ";
								}
								$tc++;

							?>
                    	</div>
                    <input type="hidden" name="type" value="person" /><br />
                    </fieldset>
                    <a href="?type=person&n=<?php=$n+5; ?>" tabindex="<?php $tn++; echo $tn; ?>">Add more rows</a> (reloads page)<br />
                    <input type="submit" name="Submit" value="Submit" tabindex="<?php $tn++; echo $tn; ?>" />
                    <input type="reset" name="Reset" value="Reset" tabindex="<?php $tn++; echo $tn; ?>" />
                </form>
                * = Required Field              
				<?php
			}
			else if ($type == 'meet')
			{			
				?>
				<h2>Add a Meet</h2>
                <form name="input" action="add.php" method="post" autocomplete="off">
                    <fieldset>
                
                    <label for="year">Year:</label>
                        <input name="year" type="text" size="5" value="<?php echo date('Y'); ?>" required />*<br />
                    <label for="season">Season:</label>
                        <select name="season">
                            <option value="xc">Cross Country</option>
                            <option value="it">Indoor Track</option>
                            <option value="ot">Outdoor Track</option>
                            <option value="other">Other</option>
                        </select><br /><br />
                        
                    <label for="startdate">Start Date:</label>
                        <input type="date" name="startdate" size="10" value="<?phpif(!empty($error)) echo $_POST['startdate']; ?>" required />*<br />
                    <label for="enddate">End Date:</label>
                        <input type="date" name="enddate" size="10" value="<?php if(!empty($error)) echo $_POST['enddate']; ?>" required/>*<br />
                        Date format: yyyy-mm-dd<br /><br />
                        
                    <label for="name">Meet Name</label>
                        <input type="text" name="name" size="40" value="<?php if(!empty($error)) echo $_POST['name']; ?>" required />*<br />
                    <label for="host">Host School</label>
                        <input type="text" name="host" size="40" value="<?php if(!empty($error)) echo $_POST['host']; ?>" required /><br />        
                    <label for="location">Location</label>
                        <input type="text" name="location" size="20" value="<?php if(!empty($error)) echo $_POST['location']; ?>"/><br />
                    <label for="course">Course</label>
                        <select name="course">
                            <option value="">N/A</option>
                            <option value="xc_eiu">EIU (XC)</option>
                            <option value="xc_arb">The Arboretum (XC)</option>
                            <option value="xc_loyola">Loyola (XC)</option>
                        </select>
                    <br /> <br />       
                    <label for="results">Full Results URL</label>
                        <input type="url" name="results" size="45" value="<?php if(!empty($error)) echo $_POST['results']; ?>" /><br />
                    <label for="splitsURL">Splits URL</label>
                        <input type="url" name="splitsURL" size="45" value="<?php if(!empty($error)) echo $_POST['splitsURL']; ?>" />
                    <label for="photos">Photo Album URL</label>
                        <input type="url" name="photos" size="45" value="<?php if(!empty($error)) echo $_POST['photos']; ?>" />
                    <br /><br />
                    
                    <label for="notes">Notes:</label>
                        <textarea name="notes" cols="35" rows="4"><?php if(!empty($error)) echo $_POST['notes']; ?></textarea>     
                
                    <input type="hidden" name="type" value="meet" /><br />
                    </fieldset>
                    
                    <input type="submit" name="Submit" value="Submit" />
                    <input type="reset" name="Reset" value="Reset" />
                </form>
				
				
				<?php
				
			}
			else if ($type == 'performance')
			{
				?>
                	<h2>Add a Performance</h2>
                
                    <form name="performance" id="performance" action="add.php" method="post" autocomplete="off">
                    <fieldset>
                        <label for="name">Name:</label>
                            <input type="text" name="name" id="name-input" size="20" /><br />
                        <label for="meet">Meet</label>
                            <input type="text" name="meet" id="meet-input" size="20" /><br />
                        <script type="text/javascript">
                        $(document).ready(function() {
                            $("#meet-input").tokenInput("../json.php?t=m", {
                                tokenLimit: 1,
                                onDelete: function() {
                                                    //$("#event-input").tokenInput('clear');
                                                    $("#event-input").remove();
                                                    $("#event-input").tokenInput('destroy');
                                                    //$("#event-input").remove();
                                                    //$(".token-input-list").clear();
                                                    },
                                onAdd: function (item) 
                                        {seasonUrl = "../json.php?t=e&s=" + item.season;
                                                $("#event-input").tokenInput(seasonUrl, {
                                                tokenLimit: 1
                                                });
                                        }
                            });
                        });
                        </script>
                        <label for="event">Event</label>
                            <input type="text" name="event" id="event-input" size="20" /><br />
                        <label for="date">Date</label>
                            <input type="text" name="date" id="date-pick" class="date-pick" size="10" />yyyy-mm-dd<br />
                        <label for="time">Time</label>
                            <input type="text" name="time" size="10" />HH:MM:SS.ms<br />
                        <label for="mark">Mark</label>
                            <input type="text" name="mark" size="10" /><br />
                            <input type="hidden" name="wind" value="0" />
							<input type="checkbox" name="postgrad" value="1" /> Wind-Aided  <br />   
                            <input type="hidden" name="postgrad" value="0" /> 
                            <input type="checkbox" name="postgrad" value="1" /> Post Collegiate<br />   
                        
                        <input type="hidden" name="type" value="performance" /><br />
                    </fieldset>
                    <input type="submit" name="Submit" value="Submit" />
                    <input type="reset" name="Reset" value="Reset" />
                    <input type="button" onclick="javascript:alert('seasonUrl: ' + seasonUrl)" />
                </form>
                <?php
			}
			else
			{
			?>
               <a herf="add.php?type=person">Add a Person</a><br>
			   <a herf="add.php?type=meet">Add a Meet</a><br>
              <a herf="add.php?type=performance">Add a Performance</a><br> 
            <?php
                }
			
			
			?>
            <br><br>

            
    	</div>
    </div>

	
        </div> <!-- end container -->

  </body>
</html>