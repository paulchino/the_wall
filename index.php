<?php
session_start();
include_once('new-connection.php');

?>

<html>
<head>
	<title>Login and Registration</title>
</head>
<style type="text/css">
input {
	display: block;
}
</style>
<body>
	<?php 
	if (isset($_SESSION['errors']))
	{
		foreach ($_SESSION['errors'] as $error) {
	?>
		<p> <?= $error ?> </p> 
	<?php 
		}
		unset($_SESSION['errors']);
	}
	?>

	<?php 
	if (isset($_SESSION['success']))
	{
	?>
		<p> <?= $_SESSION['success'] ?> </p> 
	<?php  
		unset($_SESSION['success']);
	}
	?>



	<h2>Login</h2>
	<form action='process.php' method='post'>
		<input type='hidden' name='action' value='login' />
		Email Address<input type='text' name='email' />
		Password<input type='text' name='password' />
		<input type='submit' value='login'>
	</form>
	<h2>Register</h2>
	<form action='process.php' method='post'>
		<input type='hidden' name='action' value='register' />
		First Name<input type='text' name='first_name' />
		Last Name<input type='text' name='last_name' />
		Email Address<input type='text' name='email' />
		Password<input type='text' name='password' />
		Confirm Password<input type='text' name='confirm' />
		<input type='submit' value='register'>
	</form>

</body>
</html>