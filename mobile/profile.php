<?php
	session_start();
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
	include '../includes/login_required.inc';
	$user = $_SESSION['user'];
	if(isset($_GET['view']))
	{
		$user = sanitize($_GET['view']);
	}
	else
	{
		include('includes/unlogged_profile.php');
	}
	if(!isset($_GET['page']))
	{
		$_GET['page']=1;
	}
	$size = 10;
	$page = sanitize($_GET['page']);
	$start = $size * ($page-1);
	$view = $user;

	?>

<title>Posts by <?php echo get_name($user) ?> </title>
		<?php
		if(strtolower($_SERVER['REQUEST_METHOD'])=='post')
		{
			$type = sanitize($_POST['type']);
			$comment = sanitize($_POST['comment']);
			$id = sanitize($_POST['id']);
			if($type=='status')
			{
				$time = disp_time();
				$username= $_SESSION['user'];

				$sql = "INSERT INTO `coms` (`username`,`comment`,`disp`,`upid`) VALUES ('$username','$comment','$time','$id')";
				$connection->exec($sql);
				header('location:'.$_SERVER['REQUEST_URI']."#sta$id");
			}
			else
			{
				# code...
			}
		}
		if($page==1)
		{

			echo "<div style='background:#c0332b;padding:10px'>";
		 $u = new usersInteract;
		 $u->user = $_SESSION['user'];
		 $u->connection = $connection;
		 $u->other = $user;
		 $img = get_ppic($user,$connection);
		 $img  = file_validity('../'.$img.'tumb.jpg');
		 echo"<div align='center'>
				<img src='$img' height='140px' width='140px' style='border-radius:90px;color:#fff'>
				<h4 style='color:#eee'>".get_name($user)."</h4>
				<span style='color:#eee'>
					Following : ".$u->other_following()."
					<br />Followed By : ".$u->other_followed()."
					";if($_SESSION['user']!=$user)
					{echo "<br />Mutual : ".$u->mutual();}
					else
					{
						echo "<br /><a href='edit.php' style='color:#878'>Edit Profile</a> ";
					}
					echo "<br />".my_birthday($view)."<br />
				</span>
			</div>";
		?>
	</div>
	<div>
	<div class='col-xs-4' style='background:#c0332b;padding:10px;'align='center'>
		<a href='profile.php?view=<?php echo $user ?>' style='color:#fff'><b>Posts</b></a>
	</div>
	<div class='col-xs-4' style='background:#e74c3c;padding:10px' align='center'>
		<a href='photos.php?view=<?php echo $user ?>' style='color:#fff'><b>Photos</b></a>
	</div>
	<div class='col-xs-4' style='background:#e74c3c;padding:10px' align='center'>
		<a href='about.php?view=<?php echo $user ?>' style='color:#fff'><b>About</b></a>
	</div>
	</div>
	<?php
		if($_SESSION['user']!=$user)
		{ ?>
	<a href='create_message.php?user=<?php echo $user ?>' class='col-xs-6 btn btn-default align='center' style='border-right:2px solid #aaa'>
		Send A Message
	</a>
	<a onclick="follow('<?php echo $user ?>',this,'user')" class='col-xs-6 btn btn-default align='center'>
		<?php
			$check = check_if_i_follow($user);
			if($check=='true')
			{
				echo "Unfollow";
			}
			else {
				echo "Follow";
			}
		 ?>
	</a>
	<?php
}}
else {
	echo "<div class='col-xs-6' style='background:#e74c3c;padding:10px' align='center'>
		<a href='photos.php?view=$user' style='color:#fff'><b>Photos</b></a>
	</div>
	<div class='col-xs-6' style='background:#e74c3c;padding:10px' align='center'>
		<a href='about.php?view=$user' style='color:#fff'><b>About</b></a>
	</div>";
}
	?>
	<div style='padding:10px;background:#fff'><hr>
		<?php
			$sql = "SELECT * FROM feeds WHERE username='$user' AND type !='ppic' ORDER BY stamp DESC LIMIT $start,$size";
			$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $r)
			{
				if($r['type'] == 'status')
				{
					$sql2 = "SELECT posts.*,users.firstname,users.lastname,users.sex,users.level,users.department,profilepics.profilepic
					FROM posts INNER JOIN users on users.username = posts.username INNER JOIN profilepics on
					users.username = profilepics.username WHERE posts.id = '$r[uploadid]' and posts.visible = '1'";
					$pq = $connection ->query($sql2);
					$pq ->setFetchMode(PDO::FETCH_ASSOC);
					foreach($pq as $po)
					{

						//get number of comments
						$coms = $connection->query("SELECT coms.*FROM coms WHERE upid='$r[uploadid]' ")->rowCount();
						//get likes for each post
						$sql3 = "SELECT id FROM likes WHERE type='status' AND upid = $r[uploadid]";
						$sql31 = "SELECT id FROM likes WHERE type='status' AND upid = $r[uploadid] AND username='$_SESSION[user]'";
						$likes = $connection->query($sql3)->rowCount();
						$likes_c = $connection->query($sql31)->rowCount();
						$do = 'rem';
						$tip = 'Unlike';
						if($likes_c == 0)
						{
							$tip = 'Like ';
							$do = 'add';
						}
						$gender = $po['sex'];
						if($gender == 'M')
						{
							$gender = 'Male';
						}
						else
						{
							$gender = 'Female';
						}
						$image = $po['profilepic'];
						$image = file_validity('../'.$image);
						echo"
						<div class='panel panel-default update' id='sta$r[uploadid]' style='border-radius:0px;'>
							<div class='panel-body'>
								<img src='$image".'tumb.jpg'."' height='40px' />
								<div class='name'><a href='profile.php?view=$r[username]' class='hint--right hint--info hint--rounded hint--bounce' data-hint='$po[department] department, $po[level] level, $gender'><b>".ucwords("$po[firstname] $po[lastname]</b>")."</a><br /><small class='text-muted'>$r[disp]</small></div>
								<span style=\"white-space:pre-line;word-wrap:break-word;background:transparent;padding:0px;border-color:transparent;overflow:initial;word-break:break-all;word-wrap:break-word; \">
								 &para; $po[content] </span>
									<br />
									<br />
								<center><small class='text-muted'><a href='view_post.php?view=$po[id]'>Comments: $coms</a> | <a href='likes.php?type=sta&view=$po[id]'>Likes: $likes</a> <span id='tip$po[id]'></span> |</small>  <a id='$po[id]' onclick=\" like('sta',$po[id],$po[id])\" >$tip</a></center>
								 </div>
									<div class='panel-footer'>
								<table width='100%'>
									<tr><form action=''  method='Post'>
										<td>
											<input name='comment' placeholder = 'Tell $po[firstname] what you think...'
											type='text' class='form-control' style='border-radius:0px;box-shadow:0 0 0;outline:none'/>
											<input type='hidden' name='type' value='status'>
											<input type='hidden' value='$r[uploadid]' name='id'>
										</td>
										<td style='padding-left:2px;'>
											<button class='btn btn-danger' style='padding:3px'>Comment</button>
										</td>
									</form></tr>
								</table>
							</div>
						</div>
						";
					}
				}
				if($r['type'] == 'picture')
				{
					$sql3 = "SELECT id FROM likes WHERE type='picture' AND upid = $r[uploadid]";
					$sql31 = "SELECT id FROM likes WHERE type='picture' AND upid = $r[uploadid] AND username='$_SESSION[user]'";
					$likes = $connection->query($sql3)->rowCount();
					$likes_c = $connection->query($sql31)->rowCount();
						$do = 'rem';
						$say = 'Unlike';
						if($likes_c == 0)
						{
							$do = 'add';
							$say = 'Like';
						}
					//get number of comments
					$num_coms = $connection->query("SELECT upid FROM uploadcomments WHERE upid = '$r[uploadid]'")->rowCount();
					$sql2 = "SELECT uploads.*,profilepics.profilepic,users.firstname,users.lastname,users.department,users.level,users.sex
					FROM uploads INNER JOIN users ON users.username = uploads.user INNER JOIN profilepics ON users.username = profilepics.username
					WHERE uploads.upid = '$r[uploadid]'";
					$pq = $connection ->query($sql2);
					$pq ->setFetchMode(PDO::FETCH_ASSOC);
									foreach ($pq as $p)
					{
						$p['picture'] = file_validity('../'.$p['picture']);
						$p['profilepic'] = file_validity('../'.$p['profilepic']);
						$gender = $p['sex'];
						if($gender == 'M')
						{
						$gender = 'Male';
						}
						else
						{
							$gender = 'Female';
						}
						echo"
							<div class='panel panel-default update' style='border-radius:0px; box-shadow:0 0 0;'>
								<div class='panel-body'>
									<img src='$p[profilepic]".'tumb.jpg'."' height='40px'>
									<div class='name'><a href='profile.php?view=$r[username]' title = '$p[department] department, $p[level] level, $gender'>
										<b>".ucwords("$p[firstname] $p[lastname]")."</b></a><br /> $r[disp]<br />
									</div>
									<center>$p[content]</center>
									<div align='center'>
										<a href='photo_view.php?view=$r[uploadid]'><img src='$p[picture]tumb.jpg' onclick=\"pic_dialog($r[uploadid])\" class='pointer' height='200px' style='max-width:150%;min-width:200px;max-width:100%'></a>
										<br /><br /><center class='text-muted'><a id='pic$r[uploadid]' onclick=\"like('pic',$r[uploadid],'"."pic$r[uploadid]"."')\">$say</a> |
										<span ><a href='photo_view.php?view=$r[uploadid]'>Comments: $num_coms</a></span> | <a href='likes.php?type=pic&view=$r[uploadid]'>Likes: $likes</a></center>
									</div>
								</div>

								<div class='panel-footer'>
										<table width='100%'>
									<tr><form>
										<td>
										<input type='text' class='form-control' placeholder=\"What do you think about $p[firstname]'s Picture?  \" style='border-radius:0px;box-shadow:0 0 0; outline:none' name='comment'/>
										</td>
										<td>
											<button class='btn btn-danger' name='pic' value='$r[uploadid]' style='padding:3px'>Comment</button>
										</td></form>
									</tr>
									</table>
								</div>
							</div>
						";
					}
				}
			}
			if($query->rowCount() == $size)
			{
				echo "<a href='profile.php?view=$user&page=".($page+1)."' class='btn form-control btn-danger'>Show More</a>";
			}
			if($query->rowCount() == 0)
			{
				echo "<h3><br />Hmm. . .  Seems Like $user hasn't made any posts yet.</h3>";
			}
		?>
	</div>
<?php
	include 'includes/footer.inc'
?>
