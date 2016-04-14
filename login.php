<?php
   ob_start();
   session_start();
   include_once('includes/config.php');
   include_once('includes/functions.php');
   include_once('includes/passwords.php')
?>
<html lang = "en">
   
   <html>
  <head>
    <title>Login</title>
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
                        $("#name-input").tokenInput("../json.php?t=n", {
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
<?php printNav('login'); ?>
  
    <!-- Begin Container -->
    <div class="container">
    <ul class="breadcrumb">
      <li><a href="index.php">Home</a> <span class="divider">></span></li>
  	  <li><a href="profile.php">Profiles</a> <span class="divider">></span></li>
      <li class="active"><?php if (empty($_GET['id'])) echo "All Profiles"; else echo "$p[first] $p[last]"; ?></li>
    </ul>
      
      <h2>Enter Username and Password</h2> 
      <div class = "container form-signin">
         
         <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				
               if ($_POST['username'] == $username && 
                  $_POST['password'] == $userpassword) {
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = $username;
                  
                  echo 'You have entered valid use name and password';
               }else {
                  $msg = 'Wrong username or password';
               }
            }
         ?>
      </div> <!-- /container -->
      
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post">
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            Username:
            <input type = "text" class = "form-control" 
               name = "username" required autofocus></br>
            Password:
            <input type = "password" class = "form-control"
               name = "password" required>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button>
         </form>
			
         Click here to clean <a href = "logout.php" tite = "Logout">Session.
         
      </div> 
      
   </body>
</html>