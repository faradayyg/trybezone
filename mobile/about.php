<?php
	session_start();
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	user('../includes/easy_login.inc');
	include 'includes/header.inc';
	include 'includes/nav.inc';
	$view = $_SESSION['user'];
	if(isset($_GET['view']))
	{
		$view = sanitize($_GET['view']);
	}
	$user = $view;
	$userInfo = new userInfo;
	$userInfo->user = $user;
	$userInfo->connection = $connection;
?>
<div style='background:#c0332b;padding:10px'>
		<?php

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
						echo "<br /><a href='edit.php'>Edit Profile</a>";
					}
					echo"
				</span>
			</div>";
		?>
	</div>
	<div>
	<div class='col-xs-4' style='background:#e74c3c;padding:10px;'align='center'>
		<a href='profile.php?view=<?php echo $user ?>' style='color:#fff'><b>Posts</b></a>
	</div>
	<div class='col-xs-4' style='background:#e74c3c;padding:10px' align='center'>
		<a href='photos.php?view=<?php echo $user ?>' style='color:#fff'><b>Photos</b></a>
	</div>
	<div class='col-xs-4' style='background:#c0332b;padding:10px' align='center'>
		<a href='about.php?view=<?php echo $user ?>' style='color:#fff'><b>About</b></a>
	</div>
	</div>
<div>
<div class="row" style="max-width:600px;margin:0px auto;background:#fff;padding:0px"><div style="background:#eee;padding:10px;border-bottom:3px solid #bbb;">
<b class='text-primary'><i class='icon ion-pin'></i> Basic</b><br />
								Name: <?php echo $userInfo->get_name(); ?> <br />
								Gender: <?php echo $userInfo->get_gender(); ?><br />
								Birthday: <?php echo $userInfo->get_birth_date()."<sup>th</sup> ".$userInfo->get_birth_month();?><br />
								Username: <?php echo $user ?> <br/><br /></div>

								<div style="background:#fff;padding:10px"><b class='text-primary'><i class='icon ion-ios-help-outline'></i> Contact</b><br />
								Phone: <?php echo $userInfo->get_phone(); ?> <br />
								Email: <?php echo $userInfo->get_email(); ?><br /><br></div>

								<div style="background:#eee;padding:10px;border-top:3px solid #ccc;"><b class='text-primary'><i class='icon ion-university'></i> Education </b> <br />
								Department: <?php echo $userInfo->get_department() ?> <br />
								Level: <?php echo $userInfo->get_level() ?> <br /><br />
								<b >Relationship: <i class='icon ion-heart'></i> </b><?php echo $userInfo->get_relationship(); ?></div>

</div>

<?php
	include 'includes/footer.inc';
?>
