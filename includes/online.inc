<div id='online_list' class='hidden-sm hidden-xs' style="position:fixed;right:3px;top:38px;width:200px">
<?php

//to show the online users.. limit of 20 though

$time = cur_time();
$sql = "SELECT users.firstname,users.lastname,lastseen.username,profilepics.profilepic FROM lastseen INNER JOIN users ON lastseen.username = users.username INNER JOIN profilepics ON profilepics.username = users.username WHERE $time - lastseen.lastseen < 600 AND users.username !='$_SESSION[user]' LIMIT 20";
if($connection->query($sql)->rowCount() !=0 )
{
	echo "<b><center>&nbsp;</center></b>";
}
foreach ($connection->query($sql) as $e) {

	$pic = advanced_file_validity($e['profilepic'].'tumb.jpg',$e['profilepic'].'tumb.jpg');
	echo "
	<table onclick='init_chat(\"$e[username]\")' style='margin:2px;width:100%;background:#eee;cursor:pointer;' ><tr>
	<td onclick='init_chat(\"$e[username]\")'><img  src='$pic' height='40px' width='40px'></td>
	<td><a href='profile.php?view=$e[username]' >".ucwords(strtolower("$e[firstname]"))."</a></td>
	<td align='left'><div style='padding:5px;height:10px;width:10px;border-radius:5px;background:#0a0'></div></td>
	</tr></table>
	";
}
?>
</div>
<script type="text/javascript">
	setInterval(function() {
		$.post('handles/see_online.php',{user:user,token:token},function(argument) {
			$('#online_list').html(argument)
		})
	},3000)
</script>
