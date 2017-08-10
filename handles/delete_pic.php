<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$id = sanitize($_POST['id']);
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}
	$sql = "SELECT * FROM uploads WHERE upid='$id' AND user = '$username'";
	if($connection->query($sql)->rowCount()==0)
	{

		echo "<script>show_alert('You are not allowed to delete this picture, this event will be reported')</script>";
		log_action($username,'unauthorised delete on picture id = $id ',$connection);
	}
	else{
		foreach ($connection->query($sql) as $r) {
			$file = $r['picture'];
		}
		$sql = "DELETE FROM uploads WHERE upid='$id'";
		$connection->exec($sql);
		$sql = "DELETE FROM feeds WHERE type='picture' AND uploadid='$id' OR type='ppicture' AND uploadid = '$id'";
		$connection->exec($sql);
		$sql = "UPDATE profilepics SET profilepic = 'images/no_pic.png' WHERE upid='$id'";
		$connection->exec($sql);
		unlink($file);
		echo "<script>show_alert('Picture Deleted');hide('id','dummy2');load_feeds()</script>";
	}
?>
