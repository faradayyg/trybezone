<?php
	include('include.inc');
	$id = sanitize($_POST['id']);
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$type = sanitize($_POST['type']);
	$response = array();
	if($token != transform($username))
	{
		$response['message'] = "
			there is an error with your session, please logout and login again
		";
		$response["status"] = ERR;
	}
	else
	{
		if($type == 'sta')
		{
			$type = "status";
			$tt = "com_like";
		}
		elseif($type == 'pic')
		{
			$type = "picture";
			$tt = "pic_like";
		}
		elseif($type == 'cha')
		{
			$type = "group";
			$tt = "channel_like";
		}

		$sql = "SELECT id FROM likes WHERE username = '$username' AND type = '$type' AND upid='$id'";
		$numb = $connection->query($sql)->rowCount();
		if($numb == 0)
		{
			$dis = disp_time();
			$sql = "INSERT INTO likes (`username`,`type`,`upid`,`disp`) VALUES ('$username','$type','$id','$dis')";
			log_action($username,"Liked a picture $dis",$connection);
			notify($username,$tt,$id);
			$connection->exec($sql);
		}
		else
		{
			$sql = "DELETE FROM likes WHERE username = '$username' AND type = '$type' AND upid='$id'";
			$connection->exec($sql);
		}
		$response["status"] = OK;
		$response["message"] = OK;
	}
?>