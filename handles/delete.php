<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
  $token = sanitize($_POST['token']);
	$type = sanitize($_POST['type']);
	$id = sanitize($_POST['id']);
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}
  else
  {
    if($type == 'status')
    {
      $sql = "SELECT * FROM posts WHERE username= '$username' AND id = '$id'";
      if($connection->query($sql)->rowCount()==0)
      {
        echo "Sorry Youre trying to delete a post that is not yours";
        log_action($username,'unauthorised delete on post id = $id ',$connection);
      }
      else
      {
        $sql = "DELETE FROM posts WHERE username= '$username' AND id = '$id'";
        $connection->exec($sql);
        $sql = "UPDATE `notifications` SET `seen`='1' WHERE `type` = ";
        $sql .= " 'com_like' OR `type` = 'COM' OR `type` = 'created' and `cid` = '$id' ";
        $connection->exec($sql);
        log_action($username,' deleted post id = $id ',$connection);
        echo "Deleted";
      }
    }
  }
?>
