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
	$comment = sanitize($_POST['comment']);
	$id = sanitize($_POST['id']);
	$username = $_SESSION['user'];
	$sql = "SELECT * FROM uploads WHERE upid='$id'";
	if($connection->query($sql)->rowCount()==0)
	{
		echo "error...";
		exit();
	}

	$time = disp_time();
	$sql = "INSERT INTO uploadcomments (`upid`,`commenter`,`comment`,`disp`) VALUES ('$id','$username','$comment','$time')";
	$connection->exec($sql);
	log_action($username,'Commented "'.substr($comment,0,50).'..." on picture id = '.$id,$connection);
	notify($username,'pic',$id);
	echo "Your Comment Has been Added
	";
?>