<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$to_follow = sanitize($_POST['to_follow']);
	$type = sanitize($_POST['type']);
	$t_now = cur_time();
	keep_online($username);
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	if($username == $to_follow and $type!='group')
	{
		echo "error";
		throw new Exception("trying to follow self.. not allowed", 1);
		
		exit();
	}

	$sql = "SELECT * FROM followers WHERE username = '$to_follow' AND follower='$username' AND type='$type'";
	if($connection->query($sql)->rowCount()!=0)
	{
		$sql = "DELETE FROM `followers` WHERE username = '$to_follow' AND follower='$username' AND type='$type'";
		$connection->exec($sql);
		$sql = "DELETE FROM `subscriptions` WHERE subs_to = '$to_follow' AND username='$username' AND type='$type'";
		$connection->exec($sql);
		$sql = "DELETE FROM notifications WHERE latest='$username' AND participant = '$to_follow' AND type = 'follow'";
		$connection->exec($sql);
		log_action($username,'unfollowed '.$to_follow,$connection);
		if($type=='group')
		{
			echo "Join";
		}
		else {
			# code...
			echo "Follow";
		}
		exit();
	}
	else
	{
		$sql = "INSERT INTO `followers` (`username`,`follower`,`type`) VALUES ('$to_follow','$username','$type')";
		$connection->exec($sql);
		$sql = "INSERT INTO `subscriptions` (`username`,`subs_to`,`type`) VALUES ('$username','$to_follow','$type')";
		$connection->exec($sql);
		$sql = "INSERT INTO `notifications`(`latest`,`participant`,`type`,`stamp`) VALUES ('$username','$to_follow','follow','$t_now') ";
		$connection->exec($sql);
		log_action($username,'followedd '.$to_follow,$connection);
		if($type=='group')
		{
			echo "Leave";
		}
		else {
			# code...
			echo "Unfollow";
		}
	}
?>
