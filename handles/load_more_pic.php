<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$user = sanitize($_POST['user_to_load']);
	$token = sanitize($_POST['token']);
	$start = sanitize($_POST['start']);
	$stop = sanitize($_POST['stop']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}
	$sql = "SELECT * FROM uploads WHERE user='$user' ORDER BY stamp DESC limit $start,$stop";
	foreach($connection->query($sql) as $r)
	{
		$r['picture'] = advanced_file_validity('../'.$r['picture'],$r['picture']);
		echo "<div class='col-sm-3 pointer' onclick='pic_dialog($r[upid])' style='padding:3px;border:1px solid #ddd;border-radius:3px;width:150px;margin:5px;overflow:hidden;max-height:130px' >
			<img src='$r[picture]tumb.jpg' width='150PX' style=''/>
		</div>";
	}
?>