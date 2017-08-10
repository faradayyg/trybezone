<?php
//see online
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$user = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	//verify that the user is not hijacked by the transform property
	if($token != transform($user))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		log_action($user,' transform check fail ',$connection);
		exit();
	}

	$time = cur_time();
	$sql = "SELECT users.firstname,users.lastname,lastseen.username,profilepics.profilepic FROM lastseen INNER JOIN users ON lastseen.username = users.username INNER JOIN profilepics ON profilepics.username = users.username WHERE $time - lastseen.lastseen < 600 AND users.username !='$_SESSION[user]' LIMIT 20";
	if($connection->query($sql)->rowCount() !=0 )
	{
		echo "<b><center>&nbsp;</center></b>";
	}
	foreach ($connection->query($sql) as $e) {
		$pic = advanced_file_validity('../'.$e['profilepic'].'tumb.jpg',$e['profilepic'].'tumb.jpg');
		echo "
		<table onclick='init_chat(\"$e[username]\")' style='margin:2px;width:100%;background:#eee;cursor:pointer;' ><tr>
		<td onclick='init_chat(\"$e[username]\")'><img  src='$pic' height='40px' width='40px'></td>
		<td><a href='profile.php?view=$e[username]' >".ucwords(strtolower("$e[firstname]"))."</a></td>
		<td align='left'><div style='padding:5px;height:10px;width:10px;border-radius:5px;background:#0a0'></div></td>
		</tr></table>
		";
}

?>
