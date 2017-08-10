<?php
	$username = $_SESSION['user'];
	//set randomizer then return a value

	keep_online($username);

	//check for photo upload lapses.. call for action
	function call_for_upload($username)
	{
		global $connection;
		$sql = "SELECT * FROM profilepics WHERE username = '$username'";
		$query = $connection->query($sql);
		foreach ($query as $r) {
			$ppic = $r['profilepic'];
		}

		if($ppic == 'images/ppic.png' || $query->rowCount()==0)
		{
			echo "<center><i class='ion-android-camera text-primary' style='font-size:3em'></i></center><br />

			<form method='post' enctype='multipart/form-data' id='c2a_form' >
				<input type='file' name='picture' ><br /><button onclick=\"event.preventDefault();call_to_upload_pic()\" class='btn btn-primary form-control'>Upload</button>
			</form>

			<br />
			<center class='text-info'>Start by Uploading a profile picture so your contacts can see what you look like</center>";
		}
		else
		{
			return 'false';
		}
	}

call_for_friends($username);
	//invite friends..

	//set items
	function call_to_set_items()
	{
		//pass @todo.. think of what the function should do first
	}
	//suggest friends
	function call_for_friends($username)
	{
		global $connection;
		$u = new userInfo;
		$u->user = $username;
		$u->connection = $connection;
		$name = $u->get_first_name();
		$following = $u->get_following();
		if($following>700)
		{
			return 'false';
			exit();
		}

		echo "<center><i class='ion-android-people text-danger' style='font-size:3em'></i></center><br />
			Hey there $name, we noticed you are following $following persons, did you know you can find more? Click below
			to find even more!
			<button class='btn btn-danger form-control' onclick=\"$('#call_to_show_people').toggle()\">Find more People</button>
			<div id='call_to_show_people' style='max-height:400px;display:none;overflow:auto;padding-top:5px'>
		";
		$counter = 0;
		$sql = "SELECT users.username FROM users WHERE username !='$username' ORDER BY RAND() LIMIT 30";
		$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
		foreach ($query as $r) {
			$person[$counter] = $r['username'];
			$counter++;
		}

		foreach ($person as $r) {
			$sql2 = "SELECT username FROM followers WHERE username='$r' and follower = '$username' and username!='$username' AND type='user'";
			if($connection->query($sql2)->rowCount()==0)
			{
				$usr = new userInfo;
				$usr->user = $r;
				$usr->connection = $connection;
				$usr_name = $usr->get_name();
				$ppic = get_ppic($r,$connection);
				$ppic = file_validity($ppic.'tumb.jpg');
				echo "<div class='panel'><button onclick=\"follow('$r','user',this)\" class='btn btn-info pull-right' style='padding:2px'>
					<i class='icon ion-android-person-add'></i>
				</button>
				<img src='$ppic' height='40px' width='40px'>
				<a href='profile.php?view=$r'>$usr_name</a>
				</div>";
			}
		}

		echo"</div>";
	}

	//suggest channels
	function call_for_channels($username)
	{
		global $connection;
		$u = new userInfo;
		$u->user = $username;
		$u->connection = $connection;
		$name = $u->get_first_name();
		$following = $u->get_channels();
		$num = get_number_of_channels();
		/*if($following>$num * 3/4)
		{
			return 'false';
			exit();
		}*/

		echo "<center><i class='ion-ios-color-filter text-primary' style='font-size:3em'></i></center><br />
			Hey there $name, we noticed you are following $following channels / groups, did you know there are more groups? Click below
			to find even more groups!
			<button class='btn btn-warning form-control' onclick=\"$('#call_to_show_people').toggle()\">Find more Groups</button>
			<div id='call_to_show_people' style='max-height:400px;display:none;overflow:auto;padding-top:5px'>
		";
		$counter = 0;
		$sql = "SELECT username FROM channels ORDER BY RAND() LIMIT 30";
		$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
		foreach ($query as $r) {
			$person[$counter] = $r['username'];
			$counter++;
		}

		foreach ($person as $r) {
			$sql2 = "SELECT username FROM followers WHERE username='$r' and follower = '$username' AND type='channel'";
			if($connection->query($sql2)->rowCount()==0)
			{
				$usr = new channelInfo;
				$usr->channel_name = $r;
				$usr->connection = $connection;
				$usr_name = $usr->get_name();
				$channel_info = $usr->get_info();
				echo "<div class='panel panel-info'><button onclick=\"follow('$r','channel')\" class='btn btn-info pull-right ttip' title='follow channel' style='padding:2px'>
					<i class='icon ion-android-person-add'></i>
				</button>
				<a href='channels.php?view=$r' class=' ttip' title='$channel_info'>$usr_name</a>
				</div>";
			}
		}

		echo"</div>";

	}



?>
<script>
	function call_to_upload_pic(){
		$('#loading').show(400)
		tt = document.getElementById('c2a_form')
		$.ajax({
		url:"handles/upload.php",
		type:"POST",
		data: new FormData(tt),
		contentType:false,
		cache:false,
		processData:false,
		success:  function(data)
		{
			$('#img').val('');
			$('#dummy').html(data);
			load_feeds();
		},
		complete:  function()
		{
			$('#loading').hide(400);
		},
		error:  function()
		{
			$('#loading').hide(400);
			show_alert('Unable to upload file, network is unavailable at the moment, try again latter')
		},

		})
	}
</script>
