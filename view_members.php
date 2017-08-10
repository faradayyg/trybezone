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
				<center>Oops, Seems the group you are trying to view is no longer available
				or does not exist<span class='text-muted'><br />But dont worry, You can Find Simmilar Channels <a href='channels.php'>Here</a></span> 
        </center></div>";
				exit();
			}
			$sql = "SELECT * FROM followers WHERE follower='$_SESSION[user]' AND username='$channel_name' AND type='group'";
			if($connection->query($sql)->rowCount()==0)
			{
				echo "<h3>Sorry You are not yet a Member of this group</h3>
				<br /> <a href='channels.php' class='btn btn-danger'>Go back</a>
				";
				exit();
			}
			$sql = "SELECT * FROM channels WHERE username = '$channel_name'";
			if($connection->query($sql)->rowCount()==0)
			{
				echo "<div class='panel-body text-danger' style='background:#fff'><h1 align='center'><i class='ion-android-sad'></i></h1>
				<center>Oops, Seems the group you are trying to view is no longer available
				or does not exist<span class='text-muted'><br />But dont worry, You can Find Simmilar Channels <a href='channels.php'>Here</a></span> 
        </center></div>";
			}
			else
			{
  				$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
  				foreach($query as $r)
  				{

  					$options = "<div class='col-xs-6 centered_text'>
  									<a href='view_channel.php?channel=$channel_name'> << Channel</a>
  								</div>
  								<div class='col-xs-6 centered_text pointer' onclick=\"leave_channel('$channel_name','$_SESSION[user]')\">
  									<i class='ion ion-minus-circled' ></i> Leave Channel
  								</div>
  						";

  					$admin_options = "<div class='col-xs-4 centered_text'>
                    <a href='view_channel.php?channel=$channel_name'> << Channel</a>
                  </div>
                  <div class='col-xs-4 centered_text'>
  									<a href='edit_channel.php?channel=$channel_name'><i class='ion ion-settings'></i> Edit Channel</a>
  								</div>
  								<div class='col-xs-4 centered_text pointer' onclick=\"leave_channel('$channel_name','$_SESSION[user]')\">
  									<i class='ion ion-minus-circled'></i> Leave Channel
  								</div>";

  					$sql_admin = "SELECT * FROM channel_admin WHERE username = '$username' and channel = '$channel_name'";
  					if($connection->query($sql_admin)->rowCount() == 0)
  					{
  						$sql_admin2 = "SELECT * FROM channels WHERE creator ='$username' and username = '$channel_name'";
  						if($connection->query($sql_admin2)->rowCount() > 0)
  						{
  							$options = $admin_options;
  						}
  					}
  					else
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
  				}
        echo "<div class='panel panel-default no-round'>";
        $sql = "SELECT * FROM followers WHERE username='$channel_name' AND type='group' AND follower != '$_SESSION[user]'";
        $query = $connection->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        foreach($query as $r)
        {
          $name = get_name($r['follower']);
          $ppic = file_validity(get_ppic($r['follower'],$connection))."tumb.jpg";
          $follow = check_if_i_follow($r['follower']);
          if($follow=='true')
          {
            $prompt = "Unfollow";
          }
          else {
            $prompt='Follow';
          }

          echo"
            <div class='ll list-group-item'>
              <span class='btn btn-danger pull-right'>
                <span  onclick=\"follow('$r[follower]','user',this)\">$prompt</span>
              </span>
                <a href='profile.php?view=$r[follower]'>
              <img src='$ppic' height='40px' class='round_item'> $name</a>
            </div>
          ";
        }

				if($query->rowCount()==0)
				{
					echo"<h3>&nbsp;&nbsp; There are no members to view.</h3>";
				}

        echo "</div>";
      }
		?>
  </div></div>
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
