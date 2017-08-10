<br>
<br>
<br>
<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$token = sanitize($_POST['token']);
	$post = sanitize($_POST['post']);
	if(!(empty($username)||empty($post)||empty($token)))
	{
		if(transform($username)!=$token)
		{
			echo "<div class='alert alert-warning'><b>There was an error with your post, please logout and login again;</b></div>";
			exit();
		}

		$time = cur_time();
		$disp = disp_time();
		$sql = "INSERT INTO posts (`username`,`content`,`time`,`disp`) VALUES ('$username','$post','$time','$disp')";
		$connection->exec($sql);
		$last_id = $connection->lastInsertId();
		$sql2 = "INSERT INTO feeds (`type`,`uploadid`,`disp`,`username`) VALUES ('status','$last_id','$disp','$username')";
		$connection->exec($sql2);
		notify($username,'created',$last_id);
		echo "
			<script>
				show_alert('Your post has been added');
				$('textarea').val('');
			</script>
		";
	}
	else
	{
		echo "<script>show_alert('Post could not be added, please try again')</script>";
	}
?>
