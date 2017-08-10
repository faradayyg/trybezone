<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
	$view = $_SESSION['user'];
	if(isset($_GET['view']))
	{
		$view = sanitize($_GET['view']);
	}
	$user = $view;
?>

		<?php
		$size = 12;
		if(!isset($_GET['page']))
		{
			$start=0;
			$page = 1;
		}
		else
		{
			$page = sanitize($_GET['page']);
			if ($page<0 || ! ctype_digit($page))
			{
				$page = 1;
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
						echo "<br /><a href='edit.php'>Edit Profile</a> ";
					}
					echo "<br />".my_birthday($view)."<br />
				</span>
			</div>";
		?>
	</div>
	<div>
		<div>
		<div class='col-xs-4' style='background:#e74c3c;padding:10px;'align='center'>
			<a href='profile.php?view=<?php echo $user ?>' style='color:#fff'><b>Posts</b></a>
		</div>
		<div class='col-xs-4' style='background:#c0332b;padding:10px' align='center'>
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
		<a href='profile.php?view=$user' style='color:#fff'><b>Posts</b></a>
	</div>
	<div class='col-xs-6' style='background:#e74c3c;padding:10px' align='center'>
		<a href='about.php?view=$user' style='color:#fff'><b>About</b></a>
	</div>";
}
	?>
<div>
<div class="row" style="max-width:600px;margin:0px auto;background:#fff;padding:10px">
<?php
	if(!get_name($view))
	{
		echo "<h3>Sorry, seems like the User does not exist, or has deleted their Account</h3></div>";
		include 'includes/footer.inc';
		exit();
	}
?>
<div class='col-xs-12'><b>Photos of <?php echo get_name($view) ?></b></div>
<title>Photos of <?php echo get_name($view).' | '.SITE_NAME ?></title>
	<?php
		$start =  $size*($page-1);

	$sql = "SELECT * FROM uploads  WHERE uploads.user = '$view' ORDER BY stamp DESC LIMIT $start,$size ";
	$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
	foreach ($query as $r)
	{
		$pic=file_validity('../'.$r['picture']);
		echo"
			<a href='photo_view.php?view=$r[upid]'><div class='col-xs-4' style='height:120px;width:120px;overflow:hidden;border:1px solid #aaa;padding:5px;margin:10px;'>
				<img src='$pic".'tumb.jpg'."' width='110px' style=''>
			</div></a>
		";
	}
	if($query->rowCount()>11)
	{
		$new_page = $page+1;
		echo "<center><a href='photos.php?view=$view&page=$new_page' class='btn btn-danger form-control'>View More</a></center>";
	}
	elseif($page!=1)
	{
		$new_page = $page-1;
		echo "<center><a href='photos.php?view=$view&page=$new_page' class='btn btn-default form-control'>View Previous</a></center>";
	}

	?>
</div>
</div>
<?php
	include 'includes/footer.inc';
?>
