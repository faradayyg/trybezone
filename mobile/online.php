<?php
session_start();
include '../includes/login_required.inc';
include '../includes/constants.inc';
include '../includes/functions.inc';
include '../includes/db.inc';
include 'includes/header.inc';
include 'includes/nav.inc';
//to show the online users.. limit of 20 though
echo "<div  style='background:#fff;padding:10px'>";
$time = cur_time();
$sql = "SELECT users.firstname,users.lastname,lastseen.username,profilepics.profilepic FROM lastseen INNER JOIN users ON lastseen.username = users.username INNER JOIN profilepics ON profilepics.username = users.username WHERE $time - lastseen.lastseen < 600 AND users.username !='$_SESSION[user]' LIMIT 20";
if($connection->query($sql)->rowCount() !=0 )
{
	echo "<b><center>Online Users</center></b>";
}
else {
  echo "<h2 class='text-muted'>Sorry, No online Users at this time</h2>";
}
foreach ($connection->query($sql) as $e) {

	$pic = file_validity('../'.$e['profilepic'].'tumb.jpg');
	echo "
	<table style='margin:2px;width:100%' ><tr>
	<td width='60px'><img src='$pic' height='40px' width='40px'></td>
	<td align='left'><a href='profile.php?view=$e[username]'>".ucwords(strtolower("$e[firstname] $e[lastname]"))."</a></td>
	<td align='right'><a href='create_message.php?user=$e[username]' class='btn btn-default'>Chat</a></td>
	</tr></table>
	";
}
  echo "</div>";
  include 'includes/footer_menu.inc';
  include 'includes/footer.inc';
?>
