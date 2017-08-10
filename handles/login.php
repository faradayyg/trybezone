<?php
	session_start();
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = strtolower(sanitize($_POST['username']));
	$password = sanitize($_POST['password']);
	echo "
		<style>
			.login
			{
				width:40%;min-width:300px;padding:20px;margin:auto;
			}
		</style>
		<br />
			";
			if(!ctype_alpha($username) && strlen($username)>10)
			{
				$phone = $username;
				$length = strlen($phone);
				$phone = substr($phone,$length-10,10);
				$phone = '0'.$phone;
				$sql = "SELECT `phone`,`username` FROM `users` WHERE `phone` = '$phone' LIMIT 1";
				$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
				foreach ($query as $r)
				{
					$username = $r['username'];
					echo $username;
				}
			}
	$sql = "SELECT username,password,adminstatus,status FROM login WHERE username = '$username' AND password = '$password'";
	try
	{
		$query = $connection->query($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		IF($query->rowCount()==1)
		{
			//do the login here
			foreach($query as $r)
			{
				$status = $r['status'];
				$admin = $r['adminstatus'];
			}
			if($status == 1)
			{
				echo "<div class='alert alert-info login'>Sorry your Account has been suspended for some reason,  please <a href='contact.php'>report this as an issue</a></div>";
				exit();
			}
			else
			{
				$_SESSION['user']=$username;
				echo "
				<div class='alert alert-success login'>You have been successully Logged in please wait, redirecting you. . . </div>
				<script>window.location = 'index.php'</script>";
			}

		}
		elseif($query->rowCount()>1)
		{
			//tell em to fuck off
			echo "<div class='alert alert-danger login'>A fatal error occured, it seems there is an error with your registration, please <a href='contact.php'>report this as an issue</a></div>";
		}
		else
		{
			$sql = "SELECT username FROM login WHERE username ='$username'";
			try
			{
				$query = $connection->query($sql);
				$query->setFetchMode(PDO::FETCH_ASSOC);
				if($query->rowCount()!=0)
				{
					echo "<div class='alert alert-danger login'>Sorry, Looks like The password You entered for $username is incorrect</div>";
				}
				else
				{
					echo "<div class='alert alert-warning login'>
							Sorry, No user by the username \"$username\" exists. <a href='#' data-toggle='modal' data-target='#signUpModal'> Do you want to register? </a>
						  </div>";
				}
			}
			catch(PDOException $e)
			{
				echo DATABASE_ERROR_MSG;
				if(TESTING == 1)
				{
					echo $e->getMessage();
				}
			}
		}
	}
	catch(PDOException $e)
	{
		echo DATABASE_ERROR_MSG;
		if(TESTING == 1)
		{
			echo $e->getMessage();
		}
	}

?>
