<?php
	session_start();
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	user('../includes/easy_login.inc');
	include 'includes/header.inc';
	include 'includes/nav.inc';
?>

<div style="background:#fff;padding:10px;">
	<b>Channels</b>
	<?php
		$sql = "SELECT * FROM channels LIMIT 20";
		$query = $connection->query($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		foreach($query as $r)
		{
			$follow = '<a class="btn btn-danger" onclick="follow(\''.$r['username'].'\',this,\'group\')">Join</a>';
			if (check_channel_follow($_SESSION['user'],$r['username'])!=0)
			{
				$follow = '<a class="btn btn-default" onclick="follow(\''.$r['username'].'\',this,\'group\')" style="padding:3px">Leave</a>';
			}
			$msql = "SELECT username FROM followers WHERE username = '$r[username]' AND type='group'";
			$num_members = $connection->query($msql)->rowCount();
			 echo "<div class='panel panel-default'>

				<div class='panel-body'><span class='pull-right'>$follow</span>
				<a href='view_channel.php?channel=$r[username]'>";
				if($r['avatar']==Null)
				{
					echo "<i class='ion-ios-people' style='font-size:2em'></i> ";
				}
				else
				{
					echo"<img src='../$r[avatar]tumb.jpg'
					height='40px' style='border-radius:20px'> ";
				}

				echo ucwords($r['channel'])."</a>
				<span class='text-muted'>(Members: $num_members)</span> </div>
				</div>";
		}

		if($query->rowCount()==0)
		{
			echo "<h3> Seems Like no channels have been created at the moment.</h3>channels provide a platform for 
			a group of people with a common interest to interact";
		}
	?>
</div>
<?php
	include 'includes/footer.inc';
?>
