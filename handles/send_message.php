<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$user = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$text = sanitize($_POST['text']);
	$messageid = sanitize($_POST['messageid']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($user))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	$time = cur_time();
	$sql = "SELECT sender,receiver FROM messages WHERE messageid='$messageid'";
	foreach ($connection->query($sql) as $key) {
		if($key['receiver']==$user)
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
	$sql = "UPDATE `messages` set `sender`='$user',`receiver`='$name',`message`='$text',`read`='0',time='$time' where `messageid`='$messageid'";
	$connection->exec($sql);
	$sql = "INSERT INTO `conversation` (`messageid`,`message`,`sender`,`read`) VALUES ('$messageid','$text','$user','0')";
	$connection->exec($sql);
?>
<script>
	
</script>