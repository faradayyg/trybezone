<?php
	session_start();
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/functions.inc');
	user('includes/easy_login.inc');
	include('includes/header.inc');
	include('includes/primary_nav.inc');

	$channel_name = sanitize($_GET['channel']);
	$username = $_SESSION['user'];

	if($_SERVER["REQUEST_METHOD"]=="POST")
	{
		//editing channel's picture
		if(isset($_POST['upload_btn']))
		{
			if($_FILES['pic']['type'] == "image/jpeg" || $_FILES['pic']['type'] == "image/jpg")
			{
				include('includes/resizer.class');
			}
			else
			{
				echo "<div class='my_popover alert-danger'><span class='my_popover_close'>&times;</span><b>Sorry, image must be a .jpg image</b></div>";
			}

		}

		//editing the channel's Name
		if(isset($_POST['name_btn']))
		{
			$new_name = sanitize($_POST['channel_name']);
			function do_now()
			{
				global $connection;
				global $channel_name;
				global $new_name;
				if(strlen(trim($new_name)) < 3)
				{
					echo "<div class='my_popover '><span class='my_popover_close'>&times;</span>Sorry The Channel Name is invalid</div>";
				}
				else
				{
					$sql = "UPDATE channels SET `channel` = '$new_name' WHERE `username` = '$channel_name'";
					$connection->exec($sql);
					echo "<div class='my_popover my_popover-danger'><span class='my_popover_close'>&times;</span>You have Renamed This channel</div>";
				}


			}
			$sql_admin = "SELECT * FROM channel_admin WHERE username = '$username' and channel = '$channel_name'";
			if($connection->query($sql_admin)->rowCount() == 0)
			{
				$sql_admin2 = "SELECT * FROM channels WHERE creator ='$username' and username = '$channel_name'";
				if($connection->query($sql_admin2)->rowCount() == 0)
				{
					echo "Sorry, You cannot make changes To this Cannel";
				}
				else
				{
					do_now();
				}
			}
			else
			{
				do_now();
			}

		}


			//handle the updating of the channel Description
			if(isset($_POST['description_btn']))
				{
					$new_name = sanitize($_POST['edit_description']);
					function do_now()
					{
						global $connection;
						global $channel_name;
						global $new_name;
						if(strlen(trim($new_name)) < 3)
						{
							echo "<div class='my_popover '><span class='my_popover_close'>&times;</span>Sorry The Channel Description is invalid</div>";
						}
						else
						{
							$sql = "UPDATE channels SET `info` = '$new_name' WHERE `username` = '$channel_name'";
							$connection->exec($sql);
							echo "<div class='my_popover my_popover-danger'><span class='my_popover_close'>&times;</span>You have successfully edited the description</div>";
						}


					}
					$sql_admin = "SELECT * FROM channel_admin WHERE username = '$username' and channel = '$channel_name'";
					if($connection->query($sql_admin)->rowCount() == 0)
					{
						$sql_admin2 = "SELECT * FROM channels WHERE creator ='$username' and username = '$channel_name'";
						if($connection->query($sql_admin2)->rowCount() == 0)
						{
							echo "Sorry, You cannot make changes To this Cannel";
						}
						else
						{
							do_now();
						}
					}
					else
					{
						do_now();
					}
		}

	}
?>

	<div class="container">
		<div class='col-sm-7' >
			<?php
				$sql = "SELECT * FROM channels WHERE username = '$channel_name'";
				if($connection->query($sql)->rowCount()==0)
				{
					echo "<div class='panel-body text-danger' style='background:#fff'><h1 align='center'><i class='ion-android-sad'></i></h1>
					<center>Oops, Seems the group you are trying to view is no longer available
					or does not exist<span class='text-muted'><br />But dont worry, You can Find Simmilar Channels <a href='channels.php'>Here</a></span> </center></div>";
					exit();
				}
				
					$sql_admin2 = "SELECT * FROM channels WHERE creator ='$username' and username = '$channel_name'";
					if($connection->query($sql_admin2)->rowCount() == 0)
					{
						echo "
						<div class='panel-body text-danger' style='background:#fff'>
							<h1 align='center'><i class='ion-android-sad'></i></h1>
							<h4 align='center' class='text-danger'>
								You must be an admin to edit this Channel.<span class='text-muted'>
								<br />
							</h4>
						</div>";
					log_action($username,"Tried to edit channel ",$connection);
					exit();
					}
				$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
				foreach($query as $r)
				{

					$admin_options = "<div class='col-xs-4 centered_text'>
									<a href='view_channel.php?channel=$channel_name'> << Channel</a>
								</div>
								<div class='col-xs-4 centered_text'>
									<a href='view_members.php?channel=$channel_name'><i class='ion ion-ios-people'></i> View Members</a>
								</div>
								<div class='col-xs-4 centered_text pointer' onclick=\"leave_channel('$channel_name','$_SESSION[user]')\">
									<i class='ion ion-minus-circled'></i> Leave Channel
								</div>";

					echo "<title>$r[channel] | ".SITE_NAME."</title><div class='panel panel-default'>
					<div style='background:url($r[avatar]);background-size:100%;height:200px'>
					<div style='height:100%;background:rgba(0,0,0,0.4);padding-left:10px'>
					<br /><br />&nbsp;&nbsp;<h3 style='color:#fff'>$r[channel]<br /><small style='color:#fff'> $r[info]</small></h3></div></div>
					<div style='padding:5px;background:#dedede'>
					  <div class='row'>
						$admin_options
					  </div>
					</div>
					<div class='panel-body'>
					<h3>Edit Channel Information</h3>

					";?>
					<div data-toggle='channel_name' class='edit_panel'>
						<i class='ion ion-android-create medium_icon_size'></i>&nbsp;&nbsp; Channel Name
					</div>
					<div id='channel_name' class='panel panel-default hide_'>
						<div class='panel-body'>
						  <form method='post'>
							<table width='100%'>
								<tr>
									<td>
										<input name='channel_name' class='form-control' type='text' placeholder='New Channel Name' REQUIRED />
									</td>
									<td>
										<button name='name_btn' class='btn btn-danger'>Edit</button>
									</td>
								</tr>
							</table></form>
						</div>
					</div>
					<div data-toggle='channel_desc' class='edit_panel'>
						<i class='ion ion-android-list medium_icon_size'></i>&nbsp;&nbsp;  Channel Description
					</div>
					<div id='channel_desc' class='panel panel-default hide_'>
						<div class='panel-body'>
						<form method='post' action=''>
							<table width='100%'>
								<tr>
									<td>
										<textarea name='edit_description' class='form-control' type='text' placeholder='Update Channel Description' REQUIRED></textarea>
									</td>
									<td>
										<button class='btn btn-danger' name='description_btn'>Edit</button>
									</td>
								</tr>
							</table>
						</form>
						</div>
					</div>
					<div data-toggle='channel_photo' class='edit_panel'>
						<i class='ion ion-images medium_icon_size'></i>&nbsp;&nbsp;  Update Photo
					</div>
					<div id='channel_photo' class='panel panel-default hide_'>
						<div class='panel-body'>
						<form enctype='multipart/form-data' method='post' action=''>
							<table width='100%'>
								<tr>
									<td>
										<input class='form-control' name='pic' type='file' placeholder='New Channel Picture'/>
									</td>
									<td>
										<button name='upload_btn' class='btn btn-danger'>Upload</button>
									</td>
								</tr>
							</table>
						</form>
						</div>
					</div>

					<div class='edit_panel'>
						<i class='ion ion-person medium_icon_size'></i>&nbsp;&nbsp;  Make Admin
					</div>
			<?php
				}

			?>
		</div>
	</div>
<?php
	include 'includes/footer.inc';
?>
<script>
	$('.edit_panel').click(function(){
		id = this.getAttribute('data-toggle')
		$("#"+id).slideToggle()
	})
</script>
