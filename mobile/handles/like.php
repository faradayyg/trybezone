<?php
	session_start();
	if(!isset($_SESSION['user']))
	{
		echo "Please Login to like this post";
		exit();
	}
	include('../../includes/constants.inc');
	include('../../includes/db.inc');
	include('../../includes/functions.inc');
	$username = $_SESSION['user'];
	$type = sanitize($_POST['type']);
	$id = sanitize(sanitize($_POST['id']));

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
			echo "Unlike";
		}
		else
		{
			$sql = "DELETE FROM likes WHERE username = '$username' AND type = '$type' AND upid='$id'";
			$connection->exec($sql);
			echo "Like";
		}

?>
