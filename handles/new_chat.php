<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$person = sanitize($_POST['person']);
	$token = sanitize($_POST['token']);
	$text = sanitize($_POST['message']);
		$time = cur_time();
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}
	$sql = "SELECT * FROM messages WHERE sender = '$username' AND receiver = '$person' OR sender = '$person' AND receiver = '$username' ";
	if($connection->query($sql)->rowCount()!=0)
	{
		foreach($connection->query($sql) as $e)
		{
			$messageid = $e['messageid'];
		}
		$sql = "SELECT sender,receiver FROM messages WHERE messageid='$messageid'";
		foreach ($connection->query($sql) as $key) {
			if($key['receiver']==$username)
			{
				$name = $key['sender'];
			}
			else
			{
				$name = $key['receiver'];
			}
		}
		if($connection->query($sql)->rowCount()==0)
		{
			exit();
		}
		$sql = "UPDATE `messages` set `sender`='$username',`receiver`='$name',`message`='$text',`read`='0',time='$time' where `messageid`='$messageid'";
		$connection->exec($sql);
		$chsql = "INSERT INTO `conversation` (`messageid`,`message`,`sender`,`read`) VALUES ('$messageid','$text','$username','0')";
		$connection->exec($chsql);
	}
	else{
		$sql = "INSERT INTO messages (`sender`,`receiver`,`message`,`read`,`time`) VALUES ('$username','$person','$text','0','$time')";
		$connection->exec($sql);
		$messageid = $connection->lastInsertId();
		$sql = "INSERT INTO `conversation` (`messageid`,`message`,`sender`,`read`) VALUES ('$messageid','$text','$username','0')";
		$connection->exec($sql);
	}

	echo "<script>dialog_message_view($messageid)</script>";
?>
