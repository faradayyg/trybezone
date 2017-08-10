<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
	$id = sanitize($_GET['id']);
	if(isset($_POST['send']))
	{
		$message = sanitize($_POST['msg']);
		if(strlen($message)==0)
		{
			echo "<center class='text-muted' style='background:#555'>Sorry you cannot send empty messages</center>";
		}
		else{
		$time = cur_time();
		$user = $_SESSION['user'];
		$sql = "SELECT sender,receiver FROM messages WHERE messageid='$id'";
		foreach ($connection->query($sql) as $key) {
			if($key['receiver']==$user)
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
		$sql = "UPDATE `messages` set `sender`='$user',`receiver`='$name',`message`='$message',`read`='0',time='$time' where `messageid`='$id'";
		$connection->exec($sql);
		$sql = "INSERT INTO `conversation` (`messageid`,`message`,`sender`,`read`) VALUES ('$id','$message','$user','0')";
		$connection->exec($sql);
		}
	}
?>

	<div style='background:#fff'>
		<?php
			$sql = "SELECT * FROM messages WHERE messageid = $id";
			foreach($connection->query($sql) as $r)
			{
				if($r['sender']!=$_SESSION['user'] AND $r['receiver']!=$_SESSION['user'])
				{
					echo '<br /><center><h3>You are not authorised to View this message!</h3></center><br />';
					include 'includes/footer.inc';
					log_action($_SESSION['user'],'Tried to view an unauthorised message',$connection);
					exit();
				}
					if($r['sender']==$_SESSION['user'] )
					{
						$name = get_name($r['receiver']);
					}
					else{
						$name = get_name($r['sender']);
						$connection->exec("UPDATE `messages` set `read`='1' WHERE `messageid`='$id'");
					}
			}

			if(isset($_GET['page']))
			{
				$sub = $_GET['page']*10;
			}
			else
			{
				$sub = 10;
			}
			echo "<br /><h4 align='center'>Conversation with $name</h4>";
			$sql = "SELECT * FROM conversation WHERE messageid=$id";
			$num = $connection->query($sql)->rowCount()-$sub;
			if($num>0)
			{
				$page = isset($_GET['page']) ? $_GET['page']+1 : 2;
				echo "<center><a href='?id=$id&page=$page' ><b>Show older Messages</b></a></center>";
			}
			elseif($num<0)
			{
				$num = 0;
			}
			$sql = "SELECT * FROM conversation WHERE messageid=$_GET[id] ORDER BY stamp ASC LIMIT $num, 10";
			$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$property = "align='left'";
				$property = $r['sender'] == $_SESSION['user'] ? "align='right'":"align='left'";
				echo "<div style='padding:5px;border-bottom:1px solid #aaa;' $property>
				<b><a href='profile.php?view=$r[sender]'>".get_name($r['sender'])."</a></b><br />$r[message]
				<br /><small class='text-muted'>$r[stamp]</small></div>";
			}

			echo "<div style='padding:10px;background:#ddd'>
			<form method='post'>
				<textarea style='width:100%' name='msg'></textarea><br />
				<input type='submit' value='send' class='btn btn-danger' name='send'>
			</form></div>"
			?>
	</div>

<?php

	include 'includes/footer.inc';
?>
