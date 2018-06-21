<?php 

ob_start();
session_start();

if (isset($_SESSION['user']) != "") {
	header("Location: home.php"); //send a registered user to home.php
}

include_once('dbconnect.php');
$error = false;
if(isset($_POST['btn-signup'])) {

	//Proceed with registration
	$first_name = mysqli_real_escape_string($con, $_POST['first_name']);
	$last_name = mysqli_real_escape_string($con, $_POST['last_name']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$pass = mysqli_real_escape_string($con, $_POST['pass']);

	//var_dump($pass);

	//check first name
	if (empty($first_name)) {
	  $error = true;
	  $firstnameError = "Please enter your full first name.";
	 } else if (strlen($first_name) < 3) {
	  $error = true;
	  $firstnameError = "First name must have at least 3 characters.";
	 } else if (!preg_match("/^[a-zA-Z ]+$/",$first_name)) {
	  $error = true;
	  $firstnameError = "First name must contain alphabets and space.";
	 }

	//check last name
	if (empty($last_name)) {
	  $error = true;
	  $lastnameError = "Please enter your full last name.";
	 } else if (strlen($last_name) < 3) {
	  $error = true;
	  $lastnameError = "Last name must have at least 3 characters.";
	 } else if (!preg_match("/^[a-zA-Z ]+$/",$last_name)) {
	  $error = true;
	  $lastnameError = "Last name must contain alphabets and space.";
	 }

	//check email
	if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
	  $error = true;
	  var_dump($error);
	  $emailError = "Please enter valid email address.";
	 } else {
	  // check whether the email exist or not
	  $query = "SELECT email FROM drivers WHERE email = '$email'";
	  $result = mysqli_query($con, $query);
	  //var_dump($result);
	  $count = mysqli_num_rows($result);
	  //var_dump($count);
	  if($count != 0){
	   $error = true;
	   $emailError = "Provided Email is already in use.";
	  }
	 }
//var_dump($firstname);
	// password validation
	 if (empty($pass)){
	  $error = true;
	  $passError = "Please enter password.";
	 } else if(strlen($pass) < 6) {
	  $error = true;
	  $passError = "Password must have atleast 6 characters.";
	 }

	// password hashing for security
	$password = hash('sha256', $pass);

	/*if($error) {
		echo $emailError;
	}*/

	if(!$error) {

	$query = "INSERT INTO drivers (first_name, last_name, email, pass) VALUES('$first_name', '$last_name','$email','$pass')";
	$res = mysqli_query($con, $query);

	if ($res) {
	   $errTyp = "success";
	   $errMSG = "Successfully registered, you may login now";
	   unset($first_name);
	   unset($last_name);
	   unset($email);
	   unset($pass);
	  } else {
	   $errTyp = "danger";
	   $errMSG = "Something went wrong, try again later...";   
	  }

	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Cool wheels</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<header>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <a class="navbar-brand" href="index.php">Cool wheels</a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarText">
		    <ul class="navbar-nav mr-auto">
		      <li class="nav-item"> <!-- active class indicates the current page-->
		        <a class="nav-link" href="fleet.php">Our fleet</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link active" href="register.php">Register</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="logout.php">Log out</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="reservation.php">Make reservation</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="#">Contacts</a>
		      </li>
		    </ul>
		  </div>
		</nav>
	</header>
	<main>
		<h1>Fill the form to register</h1>
		<div class="row">
		<div class="col-8 col-md-offset-2">
   <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        <h2>SIGN UP NOW</h2>
        <hr>
        <?php if(isset($errMSG)) { ?>
            <div class="alert alert-<?php echo $errTyp ?>">
                <?php echo $errMSG; ?>
            </div>
        <?php } ?>
        <!--firstname-->
        <input type="text" name="first_name" class="form-control" placeholder="Enter first name." maxlenth="20" value="">
        <span class="text-danger"><?php //echo $firstnameError ?></span>
        <!--lastname-->
        <input type="text" name="last_name" class="form-control" placeholder="Enter last name" maxlenth="20" value="">
        <span class="text-danger"><?php //echo $lastnameError ?></span>
        <!--email-->
        <input type="email" name="email" class="form-control" placeholder="Enter email" maxlenth="50" value="">
        <span class="text-danger"><?php //echo $emailError?></span>
        <!--password-->
        <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlenth="15" value="">
        <span class="text-danger"><?php //echo $passError ?></span>

        <hr>

        <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
        <hr>
        <small>Already a Member?</small>
        <a href="index.php">Login now</a>
    </form>
	</main>
</body>
</html>
<?php ob_end_flush(); ?>