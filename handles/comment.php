<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$token = sanitize($_POST['token']);
	$type = sanitize($_POST['type']);
	$id = sanitize($_POST['id']);
	$comment = sanitize($_POST['comment']);
	$div = sanitize($_POST['div']);
	keep_online($username);
	if(empty($comment))
	{
		echo "
			<script>show_alert('Your comment appears to be empty, you cannot make an empty comment')</script>
		";
		log_action($username,'tried to make an empty comment',$connection);
		exit();
	}
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}
	else
	{	if($type == 'post')
		{
			if($connection->query("SELECT id FROM posts WHERE id='$id'")->rowCount() ==0)
			{
				echo "
					<script> show_alert('Sorry The post you are trying to comment on has been removed')</script>
				";
				exit();
			}
			else
			{
				try
				{
					$time = disp_time();
					$sql = "INSERT INTO coms (`upid`,`username`,`comment`,`disp`) VALUES ('$id','$username','$comment','$time')";
					$connection->exec($sql);
					log_action($username,'Commented "'.substr($comment,0,50).'..." on post id = '.$id,$connection);
					notify($username,'COM',$id);
					echo "
						<script>
							show_hint('Comment has been added')
							txSuccess = 1
							show_comments('sta','$div',$id)
						</script>
					";
				}
				catch(PDOException $e)
				{
					log_action('system','error in commenting '.$username,$connection);
				}

			}
		}
		elseif($type == 'pic_post' || $type == 'type')
		{
			if($connection->query("SELECT upid FROM uploads WHERE upid='$id'")->rowCount() ==0)
			{
				echo "
					<script> show_alert('Sorry The post you are trying to comment on has been removed')</script>
				";
				exit();
			}
			else
			{
				try
				{
					$time = disp_time();
					$sql = "INSERT INTO uploadcomments (`upid`,`commenter`,`comment`,`disp`) VALUES ('$id','$username','$comment','$time')";
					$connection->exec($sql);
					log_action($username,'Commented "'.substr($comment,0,50).'..." on picture id = '.$id,$connection);
					notify($username,'pic',$id);
					echo "
						<script>
							show_alert('Comment has been added')
							txSuccess = 2
						</script>
					";
				}
				catch(PDOException $e)
				{
					log_action('system','error in commenting '.$username,$connection);
				}

			}
		}
		elseif($type=='group')
		{
			$today = disp_time();
			$sql = "INSERT INTO cp_comments (`username`,`comment`,`p_id`,`disp`) VALUES ('$username','$comment','$id','$today')";
			$connection->exec($sql);
			echo "<script>
				show_hint('Comment has been added')
				txSuccess = 3
			</script>";
		}
	}
?>
