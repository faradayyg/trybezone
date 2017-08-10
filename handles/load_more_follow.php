<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$user = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$start = sanitize($_POST['start']);
	$stop = sanitize($_POST['stop']);
	$type = sanitize($_POST['type']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($user))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

			if($type=='following')
				{
					$sql = "SELECT followers.*,users.firstname,users.level,users.lastname,users.sex,users.department,profilepics.profilepic,users.username";
					$sql .=" FROM followers INNER JOIN users ON users.username = followers.follower";
					$sql .= " INNER JOIN profilepics ON profilepics.username=users.username ";
					$sql .= " WHERE followers.username='$username' ORDER BY users.firstname LIMIT $start,$stop";
					$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
					foreach ($query as $e) 
					{
						$chk = compare_users($e['username'],$_SESSION['user']);

						if($chk == 'true')
						{
							$btn = "<button onclick=\"follow('$e[username]','user')\" class='btn btn-default' style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-minus-circled'></i> Unfollow</button>";
						}
						else
						{
							$btn = "<button class='btn btn-warning' onclick=\"follow('$e[username]','user')\" style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-ios-personadd'></i> Follow </button>";
						}
						if($user==$username)
						{
							$btn = '';
						}
						if ($user==$e['username']) {
							$btn = "Me";
						}
						$e['level'] = ($e['level']=='100?') ? 'Not set' : $e['level'] ;
						$img = advanced_file_validity('../'.$e['profilepic'].'tumb.jpg',$e['profilepic'].'tumb.jpg');
							echo "<div class='col-sm-6'><div class='panel panel-default'>
								<div style='background:url($img);background-size:100%;height:150px'>
									$btn
								</div>	
								<div class='panel-body' style='padding-top:0px'>
									<b><a href='profile.php?view=".urlencode($e['username'])."'>".ucwords("$e[firstname] $e[lastname]")."</a></b><br />
									<small class='text-muted'>$e[username]</small><br/>
									<span class='text-muted'>$e[department]<br />$e[level]</span>
								</div>
							</div></div>
							";
					}
				}
		if($type=='followers')
				{
					$sql = "SELECT followers.*,users.firstname,users.level,users.lastname,users.sex,users.department,profilepics.profilepic,users.username FROM followers ";
					$sql .="INNER JOIN users ON users.username = followers.follower";
					$sql .= " INNER JOIN profilepics ON profilepics.username=users.username ";
					$sql .= " WHERE followers.username='$username' ORDER BY users.firstname LIMIT $start,$stop";
					$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
					foreach ($query as $e) 
					{
						$chk = compare_users($e['username'],$_SESSION['user']);

						if($chk == 'true')
						{
							$btn = "<button onclick=\"follow('$e[username]','user')\" class='btn btn-default' style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-minus-circled'></i> Unfollow</button>";
						}
						else
						{
							$btn = "<button class='btn btn-warning' onclick=\"follow('$e[username]','user')\" style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-ios-personadd'></i> Follow </button>";
						}
						if($user==$username)
						{
							$btn = '';
						}
						if ($user==$e['username']) {
							$btn = "Me";
						}
						$e['level'] = ($e['level']=='100?') ? 'Not set' : $e['level'] ;
						$img = advanced_file_validity('../'.$e['profilepic'].'tumb.jpg',$e['profilepic'].'tumb.jpg');
							echo "<div class='col-sm-6'><div class='panel panel-default'>
								<div style='background:url($img);background-size:100%;height:150px'>
									$btn
								</div>	
								<div class='panel-body' style='padding-top:0px'>
									<b><a href='profile.php?view=".urlencode($e['username'])."'>".ucwords("$e[firstname] $e[lastname]")."</a></b><br />
									<small class='text-muted'>$e[username]</small><br/>
									<span class='text-muted'>$e[department]<br />$e[level]</span>
								</div>
							</div></div>
							";
					}
				}