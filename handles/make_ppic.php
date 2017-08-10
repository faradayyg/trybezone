<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$token = sanitize($_POST['token']);
	$username = sanitize($_POST['username']);
	$file = sanitize($_POST['file']);
	$id = sanitize($_POST['id']);
	//verify that the user is not hijacked by the transform property
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	$sql = "UPDATE `profilepics` SET `profilepic`='$file',`upid`='$id' WHERE username = '$username'";
	$u = $connection->exec($sql);
	echo "<script>show_alert('profile picture updated')</script>";
		log_action($username,'changed profilepic',$connection);
	if(!$u)
	{
		$sql = "INSERT INTO profilepics (profilepic,upid,username) VALUES('$file','$id','$username')";
		$connection->exec($sql);
		echo "<script>show_alert('profile picture inserted')</script>";
	}
?>
