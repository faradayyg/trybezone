<?php
	session_start();
  include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	$time = cur_time();
  $user = sanitize($_GET['user']);
  $me = $_SESSION['user'];
  if(isset($_POST['send']))
  {
    $text = sanitize($_POST['msg']);
    $sql = "SELECT * FROM messages WHERE sender = '$me' AND receiver = '$user' OR sender = '$user' AND receiver = '$me' ";
  	if($connection->query($sql)->rowCount()!=0)
  	{
  		foreach($connection->query($sql) as $e)
  		{
  			$messageid = $e['messageid'];
  		}
  		$sql = "SELECT sender,receiver FROM messages WHERE messageid='$messageid'";
  		foreach ($connection->query($sql) as $key) {
  			if($key['receiver']==$me)
  			{
  				$name = $key['sender'];
  			}
  			else
  			{
  				$name = $key['receiver'];
  			}
  		}
  		if($connection->query($sql)->rowCount()==0)
  		{
  			exit();
  		}
  		$sql = "UPDATE `messages` set `sender`='$me',`receiver`='$name',`message`='$text',`read`='0',time='$time' where `messageid`='$messageid'";
  		$connection->exec($sql);
  		$chsql = "INSERT INTO `conversation` (`messageid`,`message`,`sender`,`read`) VALUES ('$messageid','$text','$me','0')";
  		$connection->exec($chsql);
  	}
  	else
    {
  		$sql = "INSERT INTO messages (`sender`,`receiver`,`message`,`read`,`time`) VALUES ('$me','$user','$text','0','$time')";
  		$connection->exec($sql);
  		$messageid = $connection->lastInsertId();
  		$sql = "INSERT INTO `conversation` (`messageid`,`message`,`sender`,`read`) VALUES ('$messageid','$text','$me','0')";
  		$connection->exec($sql);
  	}
		echo "<div class='alert alert-danger' style='border-radius:0px;'><b></b> You just sent $_GET[user] a new message!!</div>";
    header("location:conversation.php?id=$messageid");
  }
	include 'includes/header.inc';
	include 'includes/nav.inc';
  if ($user == $_SESSION['user'])
  {
    header('location:messages.php');
  }
  if(!get_name($user))
  {
    echo "<div class='alert alert-danger'><b>Error:</b> Sorry, it Seems The user you are trying to message is not found</div>";
    include 'includes/footer.inc';
    exit();
  }
  else
  {
      echo "<div style='background:#fff;padding:15px;'>
      <h4><img src='../".get_ppic($user,$connection)."tumb.jpg' height='90px'>Send Message to ".get_name($user)."</h4>
      <form method='POST'>
        <textarea name='msg' class='form-control' style='resize:none;
        border:2px solid #aaa;padding:10px' placeholder='Message to $user...'></textarea>
        <button name='send' class='btn btn-danger form-control'>Send</button>
      </form>
      </div>";
  }

  include 'includes/footer.inc';
?>
