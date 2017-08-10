<?php
include('include.inc');
$id = sanitize($_POST['id']);
$token = sanitize($_POST['token']);
$username = sanitize($_POST['username']);
$type = sanitize($_POST['type']);
$comment = sanitize($_POST['comment']);
$response = array();

if($token != transform($username))
{
	$response['message'] =  "
		show_alert('there is an error with your session, please logout and login again')</script>
	";
	$response['status'] = ERR;
}
else
{
	if(empty($comment))
	{
		$response['message'] = "Your comment appears to be empty, you cannot make an empty comment";
		log_action($username,' tried to make an empty comment ',$connection);
		$response['status'] = ERR;
	}

	else
	{
		if($type == 'post')
		{
			if($connection->query("SELECT id FROM posts WHERE id='$id'")->rowCount() ==0)
			{
				$response['message']= "
					Sorry The post you are trying to comment on has been removed
				";
				$response['status'] = ERR;
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
					$response['message']= "
						Comment has been added successfully 
					";
					$response['status'] = OK;
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
				$response['message'] = "
					Sorry The post you are trying to comment on has been removed
				";
				$response['status'] = ERR;
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
					$response['message'] = "added!";
					$response['status'] = OK;
				}
				catch(PDOException $e)
				{
					log_action('system','error in commenting '.$username,$connection);
				}

			}
		}
	}
}
echo json_encode($response);

?>