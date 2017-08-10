<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
	if(!isset($_GET['view']))
	{
		echo "<h4>Sorry, seems like this post does not exist</h4>";include 'includes/footer.inc';exit();
	}
	$id = sanitize($_GET['view']);

?>
<title>Viewing Post | <?php echo SITE_NAME ?></title>
<div style="background:#fff;padding:10px">

	<?php
	$upd = "UPDATE notifications SET seen = '1' WHERE cid = '$id' and participant = '$_SESSION[user]' and type='COM' or type='com_like'";
	$connection->exec($upd);
		$sql = "SELECT posts.*,users.firstname,users.lastname,profilepics.profilepic FROM posts";
		$sql .= " INNER JOIN users on users.username = posts.username";
		$sql .= " INNER JOIN profilepics on users.username = profilepics.username";
		$sql .= " WHERE posts.id = '$id' and posts.visible='1' LIMIT 1";
		$query = $connection->query($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		foreach($query as $r)
		{
			$img = file_validity('../'.$r['profilepic'].'tumb.jpg');
			$num_likes_q = "SELECT * FROM likes WHERE type='status' and upid='$id'";
			$num_likes = $connection->query($num_likes_q)->rowCount();
			$num_likes_me_q = "SELECT * FROM likes WHERE type='status' and upid='$id' and username = '$_SESSION[user]'";
			$num_likes_me = $connection->query($num_likes_me_q)->rowCount();
			$say = 'Like';
			if($num_likes_me == 1)
			{
				$say = 'Unlike';
			}
			$com_sql = "SELECT * FROM coms WHERE upid='$id'";
			$com_count = $connection->query($com_sql)->rowCount();
			echo"
			<div>
			<img src='$img' height='30px' width='30px'> <a href='profile.php?view=$r[username]'> $r[firstname] $r[lastname]</a> <small class='text-muted'l>$r[disp]</small><br />
			<span style='padding-left:15px;word-wrap:break-word;whitespace:pre-line;'>$r[content]</span><br /><br />
			<a id='$id' onclick=\"like('sta','$id','$id')\">$say</a> | <a href='likes.php?view=$id&type=sta'>Likes: $num_likes</a> | Comments: $com_count
			</div>
			";
		}

		if($query->rowCount()==0)
		{
			echo "Sorry, The post has either been deleted or does not exist!.";
			exit();
		}
	?><br />

	<?php
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			if($connection->query("SELECT id FROM posts WHERE id='$id'")->rowCount() ==0)
			{
				echo "
					...
				";
				exit();
			}
			else
			{
				try
				{
					$comment = sanitize($_POST['com']);
					$time = disp_time();
					$sql = "INSERT INTO coms (`upid`,`username`,`comment`,`disp`) VALUES ('$id','$_SESSION[user]','$comment','$time')";
					$connection->exec($sql);
					log_action($_SESSION['user'],'Commented "'.substr($comment,0,50).'..." on post id = '.$id,$connection);
					notify($_SESSION['user'],'COM',$id);
					echo "
						<b>Comment has been added</b>
					";
				}
				catch(PDOException $e)
				{
					log_action('system','error in commenting '.$_SESSION['user'],$connection);
				}

			}
		}
		$sql = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='COM' AND `participant` = '$_SESSION[user]'  ";
		$sql2 = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='created' AND `participant` = '$_SESSION[user]'  ";
		$sql3 = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='com_like' AND `participant` = '$_SESSION[user]'  ";
		try {
			$r = $connection->exec($sql);
			$r = $connection->exec($sql2);
			$r = $connection->exec($sql3);
		} catch (PDOException $e) {
			log_action('system','error logging',$_SESSION['user'],$connection);
		}
	?>
	<div style='background:#fee;padding:10px'>
		<?php
			$com_sql = "SELECT * FROM coms WHERE upid='$id'";
			$com_count = $connection->query($com_sql)->rowCount();
			$start = $com_count - 5;
			$say2 = '';
			if($start < 0)
			{
				$start = 0;
			}
			else
			{
				$say2 = (!isset($_GET['viewall'])) ? "<center><a href='$_SERVER[REQUEST_URI]&viewall'>view all $com_count comments</a></center>":'';
			}
			echo $say2;
			$com_sql = "SELECT coms.*,users.firstname,users.lastname,profilepics.profilepic FROM coms
			INNER JOIN users on users.username = coms.username INNER JOIN profilepics ON profilepics.username = coms.username WHERE coms.upid='$id' ORDER BY stamp limit $start,5";
			if(isset($_GET['viewall']))
			{
				$com_sql = "SELECT coms.*,users.firstname,users.lastname,profilepics.profilepic FROM coms
			INNER JOIN users on users.username = coms.username INNER JOIN profilepics ON profilepics.username = coms.username WHERE coms.upid='$id' ORDER BY stamp  ";
			}
			$query = $connection->query($com_sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$image = file_validity('../'.$r['profilepic'].'tumb.jpg');
				echo "<div style='border-bottom:1px solid #cab'>
					<img src='$image' height='20px' width='20px'> <a href='profile.php?view=$r[username]'>$r[firstname] $r[lastname]</a>
					<br />
					<span style='padding-left:15px;word-wrap:break-word;whitespace:pre-line;'>$r[comment]</span>
					<br /><small class='text-muted'>$r[disp]</small>
				</div>";
			}
			if($query->rowCount()==0)
			{
				echo "Be the first to comment on this";
			}
		?>
	</div>
	<div style="background:#eee;padding:5px;"><table width='100%'><tr><form method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'><td width="90%">
		<textarea spellcheck="true" placeholder='What do you think?' name='com' style='width:100%;height:30px;resize:none;border:1px solid #aaa;'></textarea></td><td>
		&nbsp;</td><td><button class='btn btn-danger' style='padding:3px;margin:0px;'>Comment</button></td></form></tr></table>
	</div>
</div>
<?php

	include 'includes/footer.inc';

?>
