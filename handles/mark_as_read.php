<?php
include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$id = sanitize($_POST['id']);
	$type = sanitize($_POST['type']);
	keep_online($username);
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	if($type=='follow')
	{
		$sql = "UPDATE `notifications` SET `seen`='1' WHERE `id` = '$id'";
		$connection->exec($sql);
	}
	if($type=='all')
	{
		$sql = "UPDATE `notifications` SET `seen`='1' WHERE `participant` = '$username'";
		$connection->exec($sql);
	}

?>