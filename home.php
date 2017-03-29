<?php
     
     session_start(); //Start the session
if (!isset($_SESSION['userData']))
    header("Location: index.php");

    // this will avoid mysql_connect() deprecation error.
	error_reporting( ~E_DEPRECATED & ~E_NOTICE );
	// but I strongly suggest you to use PDO or MySQLi.
	
	define('DBHOST', 'localhost');
	define('DBUSER', 'upasana');
	define('DBPASS', 'anasapu@123');
	define('DBNAME', 'Astrowars');
	
	$conn = mysql_connect(DBHOST,DBUSER,DBPASS);
	$dbcon = mysql_select_db(DBNAME);
	
	if ( !$conn ) {
		die("Connection failed : " . mysql_error());
	}
	
	if ( !$dbcon ) {
		die("Database Connection failed : " . mysql_error());
	}
    $error = false;
    if ( isset($_POST['btn-signup']) ) {
		
		// clean user inputs to prevent sql injections
		$tank_name = trim($_POST['tank_name']);
		$tank_name = strip_tags($tank_name);
		$tank_name = htmlspecialchars($tank_name);
                
                		                
                // basic tank name validation
                if (empty($tank_name)) {
			$error = true;
			$tank_nameError = "Please enter your Tank name.";
		} else if (strlen($tank_name) < 3) {
			$error = true;
			$tank_nameError = "Tank Name must have atleat 3 characters.";
		} 
			// check tank name exist or not
			$query = "SELECT userTank_name FROM users WHERE userTank_name='$tank_name'";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			if($count!=0){
				$error = true;
				$tank_nameError = "Provided tank name is already in use.";
			}
                
                
                
		
		// if there's no error, continue to signup
		if( !$error ) {
			
			$query = "INSERT INTO users (  userTank_Name  ) VALUES(  '$tank_name' )";
			$res = mysql_query($query);
				
			if ($res) {
				$errTyp = "success";
				$errMSG = "Successfully registered, you may play now";
				
                                unset($tank_name);
                                
			} else {
				$errTyp = "danger";
				$errMSG = "Something went wrong, try again later...";	
			}	
				
		}
		
		
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Astrowars</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="container">

	<div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div class="form-group">
            	<h2 class="">Spaceship name</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            <?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-danger">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
            
            
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            	<input type="text" name="tank_name" class="form-control" placeholder="Enter Spaceship Name" maxlength="30" value="<?php echo $tank_name ?>" />
                </div>
                <span class="text-danger"><?php echo $tank_nameError; ?></span>
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-login">Submit</button>
            </div>
            
                     
        
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>