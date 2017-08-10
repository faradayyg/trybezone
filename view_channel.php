<?php
	session_start();
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/functions.inc');
	user('includes/easy_login.inc');
	include('includes/header.inc');
	include('includes/primary_nav.inc');
?>
<div class='container pad-tp'>
	<div class='col-sm-6'>
		<?php
			$channel_name = sanitize($_GET['channel']);
			$username = $_SESSION['user'];
			$sql = "SELECT * FROM channels WHERE username = '$channel_name'";
			if($connection->query($sql)->rowCount()==0)
			{
				echo "<div class='panel-body text-danger' style='background:#fff'><h1 align='center'><i class='ion-android-sad'></i></h1>
				<center>Oops, Seems the channel you are trying to view is no longer available
				or does not exist<span class='text-muted'><br />But dont worry, You can Find Simmilar Channels <a href='channels.php'>Here</a></span> </center></div>";
				exit();
			}
			$sql = "SELECT * FROM followers WHERE follower='$_SESSION[user]' AND username='$channel_name' AND type='group'";
			if($connection->query($sql)->rowCount()==0)
			{
				echo "<h3>Sorry You are not yet a Member of this channel</h3>
				<br /> <a href='channels.php' class='btn btn-danger'>Go back</a>
				";
				exit();
			}
			$sql = "SELECT * FROM channels WHERE username = '$channel_name'";
			if($connection->query($sql)->rowCount()==0)
			{
				echo "<div class='panel-body text-danger' style='background:#fff'><h1 align='center'><i class='ion-android-sad'></i></h1>
				<center>Oops, Seems the channel you are trying to view is no longer available
				or does not exist<span class='text-muted'><br />But dont worry, You can Find Simmilar Channels <a href='channels.php'>Here</a></span> </center></div>";
			}
			else
			{
				$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
				foreach($query as $r)
				{

					$options = "<div class='col-xs-6 centered_text'>
									<a href=' view_members.php?channel=$channel_name'> <i class='ion ion-ios-people'></i> View Members</a>
								</div>
								<div class='col-xs-6 centered_text pointer' onclick=\"leave_channel('$channel_name','$_SESSION[user]')\">
									<i class='ion ion-minus-circled'></i> Leave Channel
								</div>
						";

					$admin_options = "<div class='col-xs-4 centered_text'>
									<a href='edit_channel.php?channel=$channel_name'><i class='ion ion-settings'></i> Edit Channel</a>
								</div>
								<div class='col-xs-4 centered_text'>
									<a href=' view_members.php?channel=$channel_name'> <i class='ion ion-ios-people'></i> View Members</a>
								</div>
								<div class='col-xs-4 centered_text pointer' onclick=\"leave_channel('$channel_name','$_SESSION[user]')\">
									<i class='ion ion-minus-circled'></i> <span class='text-info'>Leave Channel</span>
								</div>";

					
						$sql_admin2 = "SELECT * FROM channels WHERE creator ='$username' and username = '$channel_name'";
						if($connection->query($sql_admin2)->rowCount() > 0)
						{
							$options = $admin_options;
						}
					
					echo "<title>$r[channel] | ".SITE_NAME."</title><div class='panel panel-default'>
					<div style='background:url($r[avatar]);background-size:100%;height:200px'>
					<div style='height:100%;background:rgba(0,0,0,0.4);padding-left:10px'>
					<br /><br />&nbsp;&nbsp;<h3 style='color:#fff'>$r[channel]<br /><small style='color:#fff'> $r[info]</small></h3></div></div>
					<div style='padding:5px;background:#dedede'>
					  <div class='row'>
						$options
					  </div>
					</div>
					<div class='panel-body'>
					";

					$channelName = $r['channel'];
				}
				echo
				"<form method='post'>
				<textarea class='form-control' name='post' placeholder='Post to Channel . . .'
					style='resize:none;outline:none;border:1px solid #aaa;box-shadow:0 0 0;'></textarea>
					<button class='btn btn-danger' style='margin-bottom:5px'>Post</button>
				</form>
				";
				if($_SERVER['REQUEST_METHOD']=='POST')
				{
					$post = sanitize($_POST['post']);
					if(strlen($post) < 2)
					{
						echo "<b>Sorry Your post is too short, or was it empty? Please try again</b> ";
						exit();
					}
					$time = cur_time();
					$sql = "INSERT INTO `channel_posts` (`username`,`channel`,`time`,`post`)
						VALUES('$_SESSION[user]','$channel_name','$time','$post')";
					$connection->exec($sql);
					$lastid = $connection->lastInsertId();$disp = disp_time();
					$sql = "INSERT INTO feeds (`uploadid`,`disp`,`username`,`visible`,`type`)
					VALUES('$lastid','$disp','$channel_name','1','group')";
					$connection->exec($sql);
				}

				echo "<br ><h4>Recent Posts to \"$channelName\" </h4>";
				$sql = "SELECT channel_posts.*,profilepics.profilepic,users.firstname,users.lastname FROM channel_posts";
				$sql .= " INNER JOIN users ON users.username = channel_posts.username";
				$sql .= " INNER JOIN profilepics ON profilepics.username = channel_posts.username";
				$sql .= " WHERE visible = '1' AND channel = '$channel_name'ORDER BY time DESC LIMIT 0,20 ";
				$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
				foreach($query as $r)
				{
					$com_sql = "SELECT * FROM cp_comments WHERE p_id = '$r[id]'";
					$num_comments = $connection->query($com_sql)->rowCount();
					$img = file_validity($r['profilepic']);
					echo"<div class='panel panel-default'>
					<div style='padding-left:10px;padding-right:10px;' >
						<small class='text-muted pull-right'>
						".past($r['time'])."</small><img src='$img".'tumb.jpg'."' height='40px' width='40px'> <a href='profile.php?view=$r[username]'>".ucwords("$r[firstname] $r[lastname]")."</a>
						<center>".substr($r['post'],0,300)." ...</center><br />
					<center>	<a href='c_p_v.php?view=$r[id]'>View post (in full)</a> | Comments: $num_comments </center>

					</div>
					</div>";
				}
				if($query->rowCount()==0)
				{
					echo "<div><b>Sorry, There have been no posts to this channel yet.</b></div>";
				}
				echo"</div></div>";
			}
		?>
	</div>
	<div class='col-sm-3'>
		<?php include 'includes/call_to_action.php' ?>
		<?php include 'includes/online.inc' ?>
	</div>
	<div class='col-sm-3'>

	</div>
</div>
<?php
	include('includes/footer.inc');

?>
<script type="text/javascript">
	function do_append()
	{
		document.getElementById('append').innerHTML +="<div style='background:#fee;padding:10px;line-height:15px;border-bottom:1px solid #ddd'>"+
		"<table style='margin-bottom:5px;'><tr><td valign='top'><img src='images/no_pic.png' height = '40px' width='40px' ></td>"+
								"<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href='profile.php?view='<?php echo $_SESSION['user'] ?>'>"+
									"<b>Me </b></a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+document.getElementById('main_comment').value+
								"</td> </tr><tr><td></td><td><small class='text-muted'>&nbsp;&nbsp;&nbsp;&nbsp;just now</small></td>"+
							 "</tr></table></div>"
		document.getElementById('main_comment').value = ''
	}
</script>
