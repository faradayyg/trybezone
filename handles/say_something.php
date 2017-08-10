<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$id = sanitize($_POST['id']);
	$comment = ucfirst(sanitize($_POST['comment']));
	$token = sanitize($_POST['token']);
	if(!(empty($username)||empty($comment)||empty($id)))
	{
		if(transform($username)!=$token)
		{
			echo "<script>show_alert(<b>There was an error with your post, please logout and login again;</b>)</script>";
			exit();
		}

		$time = cur_time();
		$disp = disp_time();
		$sql = "UPDATE uploads SET content = '$comment' WHERE upid='$id'";
		$connection->exec($sql);
		echo "<script>show_alert('Picture Uploaded and comment added')</script>";
	}
	else
	{
		echo "<script>show_alert('Picture Uploaded but comment not added')</script>";
	}
?>
