<?
	//start the session and include user data
	session_start();	
	include_once('../includes/config.php');
	include_once('../includes/functions.php');
	connectToDB();

	//Some variables
	$error = array();
	$info = array();
	$warning = array();
	$success = array();
	
	//check the type, set values, and insert rows if applicable
	if(empty($_POST['type']))
		$type = $_GET['type'];
	else
		$type = $_POST['type'];

	if($type == 'person')
	{
		$title = "Edit a Person";

		//code to update a person here
			
	}
	else if($type == 'meet')
	{
		$title = "Edit a Meet";
		
		if(!empty($_POST))
		{
			//validate the values and then update the db.
		}
	}
	else if ($type == 'performance')
	{
		$title = "Edit a Performance";

		if(!empty($_POST))
		{
			//stuff for performance
			
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
    <title>ITC | <?=$title;?></title>
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
                        $("#name-input").tokenInput("http://illinoistrackclub.com/results/json.php?t=n", {
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

	<? printNav('admin-add');?>

  
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
      <li class="active"><?=$title; ?></li>
    </ul>

	<div class="row">
    	<div class="span10">
        <?
			displayMessages($success, $error, $warning, $info);
			
			if($type == 'person') {
				?>
                <h2>Edit a Person</h2>
                 
				 
				<?
			}
			else if ($type == 'meet')
			{			
				?>
				<h2>Edit a Meet</h2>
                
				
				<?
				
			}
			else if ($type == 'performance')
			{
				?>
                	<h2>Edit a Performance</h2>
                

                <?
			}
			else
			{
				?>
				   <a herf="edit.php?type=person">Edit a Person</a><br>
				   <a herf="edit.php?type=meet">Edit a Meet</a><br>
				  <a herf="edit.php?type=performance">Edit a Performance</a><br> 
				<?
            } //end else	
			?>
            <br><br>

            
    	</div>
    </div>

	
        </div> <!-- end container -->

  </body>
</html>