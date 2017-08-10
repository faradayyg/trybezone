<?php
	if(!isset($_SESSION))
	{
		session_start();
	}
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$firstname = ucwords(sanitize($_POST['firstname']));
	$lastname = ucwords(sanitize($_POST['lastname']));
	$phone = sanitize($_POST['phone']);
	$day = sanitize($_POST['day']);
	$month = sanitize($_POST['month']);
	$year = sanitize($_POST['year']);
	$gender = sanitize($_POST['gender']);
	$username = strtolower(sanitize($_POST['username']));
	$password = sanitize($_POST['password']);
	$error = $err=null;
	if(!preg_match("/^\+|0[0-9]*$/",$phone))
	{
		$error = 'sorry, but the phone number you entered is not correct hint:(start with + or 0) <br/>';
		$err = 1;
	}
	if(strlen($phone)<9)
	{
		$error.='You seem to have entered a phone number that is too short to be correct <br />';
		$err = 1;
	}
	if(!preg_match("/^[a-z][a-z0-9_]*$/", $username))
	{
		$err=1;
		$error .="Sorry, Your username either contains spaces or an invalid character<br />";
	}
	if (stripos($username, ' ')>0) {
		$error .= "Sorry, There can be <b>NO</b> spaces in your username <br />";
		$err =1;
	}
	if(strlen($firstname) < 3 || strlen($lastname)<3)
	{
		$error .= "Sorry, none of your name fields can be less than 3 <br />";
		$err = 1;
	}
	if(strlen($username)<3 )
	{
		$error .= "Oops It seems your Username is too short. <br />";
	}
	
	//block to check for free or used usernames and phone numbers
	$sql = "SELECT username FROM login WHERE username = '$username' ";
	
		$query = $connection -> query($sql);
		foreach($query as $r)
		{
			if($query->rowCount()!=0)
			{
				$error .= "Sorry ,that username \"$username\" is already taken <br />";
				$err=1;
			}
		}
	$sql = "SELECT phone FROM users WHERE phone = '$phone' ";
	
		$query = $connection -> query($sql);
		foreach($query as $r)
		{
			if($query->rowCount()!=0)
			{
				$error .= "Sorry, Phone number \"$phone\" has already been registered <br />";
				$err=1;
			}
		}
	
	if($err!=1)
	{
		//do insertion
		$sql = "INSERT INTO users (`firstname`,`lastname`,`username`,`day`,`month`,`year`,`sex`,`phone`,`level`) VALUES 
		('$firstname','$lastname','$username','$day','$month','$year','$gender','$phone','100')";
		$sql2 = "INSERT INTO login (`username`,`password`) VALUES('$username','$password')";
		$sql3 = "INSERT INTO profilepics (`username`,`profilepic`) VALUES('$username','images/ppic.png')";
		try
		{
			mkdir("../uploads/$username");
			$connection-> beginTransaction();
			$connection -> exec($sql);
			$connection -> exec($sql2);
			$connection -> exec($sql3);
			$connection->commit();
			$_SESSION['user'] = $username;
			echo "
			<script>window.location='index.php'</script>
			<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> Congratulations!!! Your Sign Up was successful <br /> if you are not automatically redirected,
			<a href='index.php' title='continue to site'> click here to continue</a></div>";
		}
		catch(PDOException $e)
		{
			
			if(TESTING == 1)
			{
				echo $e->getMessage();
			}
		}
		
	}
	else
	{
		echo "
			<div class='alert alert-warning text-danger' onmousemove='show(`id`,`regbody`);show(`id`,`regfooter`);' id='error_place'>
				<span class='close' onclick='dismiss(`id`,`error_place`)'>
					&times;
				</span>$error
				<script>
					$('#regfooter').slideDown()
					$('#regbody').slideDown()
				</script>
			</div>";
	}
?>
