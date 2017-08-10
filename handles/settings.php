<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$data = sanitize($_POST['data']);
	$type = sanitize($_POST['type']);
	$token = sanitize($_POST['token']); 
	if(transform($username)!=$token)
	{
		echo "<script>show_alert('There was an error with your post, please logout and login again;')</script>";
		exit();
	}

	if($type=='level')
	{
		$sql = "UPDATE users SET level = '$data' WHERE username='$username'";
		$connection->exec($sql);
		log_action($username,'changed level to '.$data,$connection);
	}

	elseif($type=='department')
	{
		$sql = "UPDATE users SET department = '$data' WHERE username='$username'";
		$connection->exec($sql);
		log_action($username,'changed department to '.$data,$connection);
	}

	elseif($type == 'privacy')
	{
		if($data=='phone')
		{
			$sql = "SELECT value FROM settings WHERE username = '$username' AND setting = 'show_phone'";
			foreach ($connection->query($sql) as $r) {
				$phone_setting = $r['value'];
			}
			if($connection->query($sql)->rowCount() == 0)
			{
				$sql2 = "INSERT INTO settings (`username`,`setting`,`value`) VALUES ('$username','show_phone','0')";
				$connection->exec($sql2);
			}
			else
			{
				$phone_setting_value = 1;
				if($phone_setting == 1)
				{
					$phone_setting_value=0;
				}
				$sql = "UPDATE settings SET value = '$phone_setting_value' WHERE username='$username' AND setting = 'show_phone'";
				$connection->exec($sql);
				log_action($username,'changed privacy on '.$data,$connection);
			}
		}
		if($data=='email')
		{
			$sql = "SELECT value FROM settings WHERE username = '$username' AND setting = 'show_email'";
			foreach ($connection->query($sql) as $r) {
				$phone_setting = $r['value'];
			}
			if($connection->query($sql)->rowCount() == 0)
			{
				$sql2 = "INSERT INTO settings (`username`,`setting`,`value`) VALUES ('$username','show_email','0')";
				$connection->exec($sql2);
			}
			else
			{
				$phone_setting_value = 1;
				if($phone_setting == 1)
				{
					$phone_setting_value=0;
				}
				$sql = "UPDATE settings SET value = '$phone_setting_value' WHERE username='$username' AND setting = 'show_email'";
				$connection->exec($sql);
				log_action($username,'changed privacy on '.$data,$connection);
			}
		}
	}

	elseif ($type=='password') {
		$old_password = sanitize($_POST['old']);
		$new_password = sanitize($_POST['neww']);
		$repeat_password = sanitize($_POST['repeat']);
		if(empty($new_password))
		{
			echo "<script>show_alert('All fields MUST be filled in correctly')</script>";
			exit();
		}
		if($repeat_password != $new_password)
		{
			echo "<script>show_alert('<i class=\" icon ion-android-lock text-danger\" style=\" font-size:3em\"></i><br />The two new passwords are not the same, use passwords that are easy to remember for you but hard to guess')</script>";
			exit();
		}
		elseif (strlen($repeat_password) < 6)  {
			echo "<script>show_alert('<i class=\" icon ion-sad text-danger\" style=\" font-size:3em\"></i><br />Sorry your password is not long enough, passwords Should be at least 6 characters in length')</script>";
			exit();
		}

		else
		{
			$sql = "SELECT password FROM login WHERE username = '$username'";
			foreach ($connection->query($sql) as $r) {
				$password = $r['password'];
			}
			if($old_password != $password)
			{
				echo "<script>show_alert('<i class=\" icon ion-sad text-danger\" style=\" font-size:3em\"></i><br /> Sorry, Old password is incorrect, Make sure this is your account and try again')</script>";
				exit();
			}
			echo "<script>show_alert('<i class=\" icon ion-checkmark-circled text-success\" style=\" font-size:3em\"></i><br /> Settings saved');$('input').val('')</script>";
			$sql = "UPDATE login SET password = '$repeat_password' WHERE username='$username'";
				$connection->exec($sql);
				log_action($username,'changed password ',$connection);
		}
	}
?>