<?php
	session_start();
	include('../../includes/constants.inc');
	include('../../includes/db.inc');
	include('../includes/functions.inc');
	if($_SERVER["REQUEST_METHOD"]=='POST')
	{
		$rdr = '';
		if($_GET['rdr'])
		{
			$rdr = sanitize($_GET['rdr']);
		}
		$username = sanitize($_POST['usr']);
		$password = sanitize($_POST['pwrd']);
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
			}
		}
		$sql = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";
		if($connection->query($sql)->rowCount()==1)
		{
			$_SESSION['user']=strtolower($username);
			if(isset($_GET['rdr']))
			{
				header('location:../'.sanitize($_GET['rdr']));
			}
			else
			{
				header('location:../index.php');
			}
		}
		else
		{

			$sql = "SELECT * FROM login WHERE username = '$username'";
			if($connection->query($sql)->rowCount()>0)
			{
				$error = urlencode("The password you entered for user $username is invalid");
			}
			else
			{
				$error = urlencode("Username and Password are both incorrect");
			}

			header("location:../login.php?rdr=$rdr&error=$error");
		}
	}
?>
