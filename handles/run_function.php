<?php
	//this page will run certain php functions for my js handlers
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$type = sanitize($_POST['type']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	if($type=='messages')
	{
		echo "<script>
		document.getElementById('header_receptor_messages').innerHTML='".get_new_messages($username)."';
		</script>";
	}
	if($type=='show_messages')
	{
		show_new_messages($username);
	}
	if($type=='notifications')
	{
		echo get_new_notifications($username);
	}

	if($type=='show_notifications')
	{
		show_new_notifications($username);
	}
?>