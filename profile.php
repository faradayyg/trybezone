<?php
	ob_start();
	session_start();
	include('includes/functions.inc');
	include('includes/header.inc');
	include('includes/constants.inc');
	include('includes/db.inc');
	user('includes/easy_login.inc');
	keep_online($_SESSION['user']);
	$user = $_SESSION['user'];
	if(isset($_GET['view']))
	{
		$user = sanitize($_GET['view']);
	}

	$userInfo = new userInfo;
	$userInfo->user = $user;
	$userInfo->connection = $connection;
	if($userInfo->get_name() == '')
	{
		$userInfo = new userInfo;
		$user = $_SESSION['user'];
		$userInfo->user = $user;
	}
?>

<title><?php echo $userInfo->get_name(); ?>'s Profile</title>
<style>
	.menu-item-active
	{
		border-bottom:3px solid #faa;
	}

	.menu-item:hover
	{
		-webkit-animation: myfirst 1s; /* Chrome, Safari, Opera */
    	animation: myfirst 1s;
    	border-bottom:3px solid #faa;
    	box-shadow: 0 0 4px rgba(0,0,0,0.5);
    	width: 110%;
	}
</style>
<body style=''>
	<?php
	require('includes/primary_nav.inc');
	?>

	<div class='container transparent' >
		<div class='col-md-9' style=';padding:0px'>
			<div style="display:table;"> 
			  <div class="col-md-7" style="background:#fff;padding-left:0px;box-shadow:0 0 9px rgba(0,0,0,0.5)">
				<div class="col-md-5" style="padding-left:0px">
					<img src='<?php echo get_ppic($user,$connection) ?>tumb.jpg' height='180px' style='border:0px'> 
				</div>
				<div class='col-md-7'>
					<h3 style='line-height:40px;'>
					<?php echo $userInfo->get_name().'<br /><span style="color:#888;font-size:0.8em">'.$userInfo->get_department().'<br />'.$userInfo->get_relationship().' </span>'; ?> </h3> 
				</div>
			  </div>
				<div class='col-md-5' style='margin:0px;padding:0px;'>
				
				<div style="padding-top:18px">
					<div style='font-size:1.3em'>
					  <div class="col-xs-6">
						<div class='profile_info_btn pointer menu-item h_item' onclick="$('.main_area').hide();$('.preview').show();$('#main_area_pictures').show();$('#picture_preview').hide();" 
						id='selector_pictures'>&nbsp;&nbsp;<span class='step size-64'>
						<span class='icon ion-images'></span></span> <span class='hidden-sm'>Pictures</span> &nbsp;&nbsp;</div>
					  </div>

					  <div class="col-xs-6">
						<div class='profile_info_btn pointer menu-item h_item' id='selector_profile' onclick="$('.main_area').hide();$('.preview').show();$('#main_area_profile').show();$('#profile_preview').hide();">
						<span class='icon ion-ios-contact' style=''></span> <span class='hidden-sm'>Profile</span> &nbsp;&nbsp;</div>
					  </div>

					  <div class="col-xs-6">
					  	<a href='follow.php?user=<?php echo $user ?>&view=following'><div class='profile_info_btn pointer menu-item h_item' >
					  		<i class='ion-android-people'></i> <span >Following</span>
					  	</div></a>
					  </div>

					  <div class="col-xs-6">
					  	<a href='follow.php?user=<?php echo $user ?>&view=followers'><div class='profile_info_btn pointer menu-item h_item' >
					  		<i class='ion-android-people'></i> <span >Followers</span>
					  	</div></a>
					  </div>

					  <div class="col-xs-6">
						<div class='profile_info_btn pointer menu-item-active h_item' onclick="$('.main_area').hide();$('.preview').show();$('#main_area_timeline').show();">
						<span class='icon ion-android-archive' style=''></span> <span class='hidden-xs hidden-sm '>Timeline</span>  &nbsp;&nbsp;</div>
					  </div>

					  	<?php
						  	$interact = new usersInteract;
							$interact->user = $_SESSION['user'];
							$interact->other = $user;
							$interact->connection = $connection;

							$btn = "class='pointer profile_info_btn menu-item h_item' onclick=\"follow('$user','user',this)\"";
						?>

						<div class="col-xs-6">
							<?php
							if($user != $_SESSION['user'])
							{
								if($interact->check_if_i_follow()=='true')
								{

									echo '<div '.$btn.' cass="btn btn-default pull-right"><span class="step size-64"><i class="ion-ios-minus"></i></span> Unfollow </div>';
								}
								else
								{
									echo '<div '.$btn.' clss="btn btn-default pull-right"><span class="step size-64"><i class="icon ion-person-add"></i></span> Follow </div>';
								}
							}
							?>
						</div>
						

					</div>
				</div>
			</div>
			</div>
			<br />

			<!-- Div for update or relationship.. -->
			<div class='panel panel-default' id='frrrtvjbfnmv' style='margin-bottom:10px'>
				<span class='pointer close hint--info hint--bottom hint--rounded hint--bounce' data-hint='close this panel' onclick='$("#frrrtvjbfnmv").slideUp()'>&times;</span>
			  <div class='panel-body' style='color:#444;padding-bottom:0px;border-color:#fff'>
				<?php
					if($user == $_SESSION['user'])
					{
						echo "<table width='100%'><tr><td width='99%''>
						<textarea style='width:100%;outline:none;resize:none;border:2px solid #fff;height:100%' placeholder='Start typing to make a post. . . '></textarea></td><td><button class='btn btn-danger' style='border-radius:200px;height:40px;width:40px;'><i class='icon ion-android-send'></i>
						</button><br />&nbsp;</td></tr></table>";
					}
					else
					{
						$r = new userInfo;
						$r->user = $user;
						$r->connection = $connection;
						echo "<span class='text-danger'>Last Seen: ".$r->get_last_seen()."</span><br />";
						$interact = new usersInteract;
						$interact->user = $_SESSION['user'];
						$interact->other = $user;
						$interact->connection = $connection;
						echo "Followed by: ".$interact->other_followed();
						echo "<br/> Following: ".$interact->other_following();
						$btn = "onclick=\"follow('$user','user',this)\"";
						if($interact->check_if_im_followed() == 'true')
						{
							echo", Including You.";
						}
						//echo '<br />You and '.$interact->get_name().' both follow '.$interact->mutual().' people mutually';
						echo "<br />  ".my_birthday($user)."<br />";

					}
				?>
			  </div></div>
			  <?php
			  if($user != $_SESSION['user'])
			  {
			  	echo "<button onclick='init_chat(\"$user\")' class='form-control btn btn-default' style='margin-bottom:10px'><i class='icon ion-chatbubble-working' style='font-size:1.4em'></i> Start Chat</button>";
			  }
			  ?>


			<!-- area for updates and stuff -->
			<div class='row'>
				<div class='col-sm-7'>
					<div id='main_area_timeline' class='main_area' style='padding:10px;background:#fefefe;'>
						<h3 class='text-danger'><i class='icon ion-clipboard'></i> Posts by <?php echo $userInfo->get_name() ?></h3>
						<?php
							$sql = "SELECT * FROM feeds WHERE username='$user' AND type !='ppic' ORDER BY stamp DESC LIMIT 7";
							$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
							foreach ($query as $r)
							{
								$delete = '';
								if($r['type'] == 'group')
								{
									$gsql = "SELECT *,channels.channel as chan,channel_posts.username as uname,channel_posts.id as aid FROM channel_posts ";
									$gsql .= " INNER JOIN users ON users.username = channel_posts.username";
									$gsql .= " INNER JOIN channels ON channels.username = channel_posts.channel";
									$gsql .= " INNER JOIN profilepics ON profilepics.username = channel_posts.username";
									$gsql .= " WHERE channel_posts.id = '$r[uploadid]' AND channel_posts.visible='1'";
									$gquery = $connection->query($gsql);$gquery->setFetchMode(PDO::FETCH_ASSOC);
									foreach ($gquery as $go)
									{
										$delete = ($go['username']==$username) ? '<span onclick="show_post_options('.$r["uploadid"].',\'group\')" class="text-muted pointer glyphicon glyphicon-chevron-down"></span>':'';
										$com_sql = "SELECT * FROM cp_comments WHERE p_id = '$go[aid]'";
										$num_comments = $connection->query($com_sql)->rowCount();
										$img = file_validity('../'.$go['profilepic'].'tumb.jpg',$go['profilepic'].'tumb.jpg');
										$time = past($go['time']);

										$sql3 = "SELECT id FROM likes WHERE type='group' AND upid = $r[uploadid]";
										$sql31 = "SELECT id FROM likes WHERE type='group' AND upid = $r[uploadid] AND username='$_SESSION[user]'";
										$likes = $connection->query($sql3)->rowCount();
										$likes_c = $connection->query($sql31)->rowCount();
										$do = 'rem';
										$tip = 'Unlike';
										if($likes_c == 0)
										{
											$tip = 'Like ';
											$do = 'add';
										}

										echo "<div class='panel panel-default update'><div class='panel-body'>
										<table width='100%'><tr><td width='43px'>
										<img src='$img' height='40px' width='40px'></td><td>
										<span class='label label-danger pull-right pointer hint--right' data-hint=\"$tip\" action='$do' onclick= \" like('likesCha$r[uploadid]',$likes,'cha','$do',this)\"><i class='pointer glyphicon glyphicon-thumbs-up'></i>
										<span id='likesCha$r[uploadid]'>$likes</span></span>
										<b><a href='profile.php?view=$go[uname]'>".$go['firstname'],"
										$go[lastname]</a></b> $delete <br /><small class=''> posted in $go[chan]</small>
										</td></tr></table><br />
										<center>$go[post]</center><small class='text-muted'>$time</small>
										</div><div id='channel_$go[aid]' class='panel-body comments' style='display:none'>
											<div align='center'><span><img src='images/32.gif' style='border:0px;'/></span></div>
										</div>
										<div class='panel-footer'>
											<span class='pointer hint--top' data-hint='Click to view comments'
											onclick=\"show_comments('cha','channel_$go[aid]',$go[aid])\">Comments: <span id='com_count_cha$r[uploadid]'>$num_comments</span></span>
											<table width='100%'><tr><td>
											<input type='text' class='form-control' id='grp$r[uploadid]' onkeyup=\"click_on_enter(event,'btn_grp_$r[uploadid]')\" placeholder=\"Comment on $go[firstname]'s Post (enter to send) \"/></td><td>
											<button class='btn btn-danger' id='btn_grp_$r[uploadid]' onclick=\"comment(document.getElementById('grp$r[uploadid]'),$r[uploadid],'group',user,token,'com_count_cha$r[uploadid]','channel_$go[aid]',0)\">Comment</button></td></tr></table>
										</div>
										</div>
											" ;
									}
								}
								if($r['type'] == 'status')
								{
									$sql2 = "SELECT posts.*,users.firstname,users.lastname,users.sex,users.level,users.department,profilepics.profilepic FROM posts INNER JOIN users on users.username = posts.username INNER JOIN profilepics on users.username = profilepics.username WHERE posts.id = '$r[uploadid]'  AND posts.visible = '1'";
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
										$delete = ($po['username']==$user) ? '<span onclick="show_post_options('.$r["uploadid"].',\'status\',\''.'status'.$r['uploadid'].'\')"
										class="text-muted pointer glyphicon glyphicon-chevron-down"></span>':'';
										$sql2 = "SELECT id FROM dislikes WHERE type='status' AND upid = $r[uploadid]";
										$dislikes = $connection->query($sql2)->rowCount();
										$image = $po['profilepic'];
										$image = file_validity($image);
										echo"
											<div id=\"status$r[uploadid]\" class='panel panel-default update' style='box-shadow: 0px 2px rgba(0, 0, 0, 0.05), 0px 0px 1px rgba(0, 0, 0, 0.2);'>
												<div class='panel-body'>
												<div style='position:relative;right:0px;' align='right' class='pointer' >
													<div class='label label-danger pointer hint--right  hint--bounce' data-hint = '$tip' action='$do' onclick= \" like('likesSta$r[uploadid]',$likes,'sta','$do',this)  \">
														<span class='glyphicon glyphicon-thumbs-up pointer'></span>
														<span id='likesSta$r[uploadid]' > $likes</span>
													</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</div>
												<img src='$image".'tumb.jpg'."' height='40px' />
												<div class='name'><a href='profile.php?view=$r[username]' class='hint--right  hint--bounce' data-hint='$po[department] department, $po[level] level, $gender'><b>".ucwords("$po[firstname] $po[lastname]</b>")."</a>  $delete<br /><small class='text-muted'>$r[disp]</small></div>
												<span style=\"white-space:pre-line;word-wrap:break-word;background:transparent;padding:0px;border-color:transparent;overflow:initial;word-break:break-all;word-wrap:break-word; \">
												\n &para; $po[content] </span>
												<br />
												<br />

												</div>
												<div id='coms_sta_$r[uploadid]' class='panel-body comments' style='display:none;'>
												<div align='center'><span><img src='images/32.gif' style='border:0px;'/></span></div>
												</div>
												<div class='panel-footer'>
												<small class=' text-muted hint--top  hint--bounce' data-hint='Click to View Comments'><a style='color:#888	' onclick=\"show_comments('sta','coms_sta_$r[uploadid]',$r[uploadid])\" href='javascript:'>Comments: <span id='com_count_sta$r[uploadid]'>$coms</span></a>
												</small>
												<table width='100%'><tr><td>
												<input type='text' class='form-control' id='text$r[uploadid]' onkeyup=\"click_on_enter(event,'btn_sta_$r[uploadid]')\" placeholder=\"Comment on $po[firstname]'s Post (enter to send) \"/></td><td>
												<button class='btn btn-danger' id='btn_sta_$r[uploadid]' onclick=\"comment(document.getElementById('text$r[uploadid]'),$r[uploadid],'post',user,token,'com_count_sta$r[uploadid]','coms_sta_$r[uploadid]',0)\">Comment</button></td></tr></table>
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
									$sql2 = "SELECT id FROM dislikes WHERE type='picture' AND upid = $r[uploadid]";
									$dislikes = $connection->query($sql2)->rowCount();
									$sql2 = "SELECT uploads.*,profilepics.profilepic,users.firstname,users.lastname,users.department,users.level,users.sex FROM uploads INNER JOIN users ON users.username = uploads.user INNER JOIN profilepics ON users.username = profilepics.username WHERE uploads.upid = '$r[uploadid]'";
									$pq = $connection ->query($sql2);
									$pq ->setFetchMode(PDO::FETCH_ASSOC);
									foreach ($pq as $p)
									{
										$p['picture'] =file_validity($p['picture']);
										$p['profilepic'] =file_validity($p['profilepic']);
										$gender = $p['sex'];
										if($gender == 'M')
										{
											$gender = 'Male';
										}
										else
										{
											$gender = 'Female';
										}
										$delete = ($p['user']==$user) ? '<span onclick="show_post_options('.$r["uploadid"].',\'picture\',\''.'pic'.$r['uploadid'].'\')" class="text-muted pointer glyphicon glyphicon-chevron-down"></span>':'';
										echo"
											<div class='panel panel-default update' id='pic$r[uploadid]'>
												<div class='panel-body'>
													<div style='position:relative;right:0px;' align='right' class='pointer' >
														<div class='label label-danger pointer hint--left hint--bounce ' data-hint=\"$say \" action='$do' onclick= \" like('likesPic$r[uploadid]',$likes,'pic','$do',this) \">
															<span class='glyphicon glyphicon-thumbs-up pointer'></span>
															<span id='likesPic$r[uploadid]' > $likes</span>
														</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</div>
													<img src='$p[profilepic]".'tumb.jpg'."' height='40px'>
													<div class='name'><a href='profile.php?view=$r[username]' class='hint--right  hint--bounce' data-hint = '$p[department] department, $p[level] level, $gender'>
														<b>".ucwords("$p[firstname] $p[lastname]")."</b></a> $delete<br /><small class='text-muted'> $r[disp]</small><br />
													</div>
													<p></p><p>$p[content]</p>
													<div align='center' style='width:100%;overflow:hidden'><span class='pointer hint--top  hint--bounce' data-hint='Click to enlarge picture'><img src='$p[picture]' onclick=\"pic_dialog($r[uploadid])\" class='pointer'  style='max-height:300px;'></span></div>
												</div>
												<div class='panel-body comments' id='coms_view_pic_$r[uploadid]' style='display:none;'><img src='images/32.gif' style='border:0px;'></div>
												<div class='panel-footer'>
												<small class='text-muted'>  <span class='pointer hint--top hint--bounce ' onclick=\"show_comments('pic','coms_view_pic_$r[uploadid]',$r[uploadid])\" data-hint='Click to view comments'>Comments</span><span id='coms_pic_$r[uploadid]'> $num_coms</span></small>
													<table width='100%'><tr><td><input type='text' class='form-control' id='pic_text$r[uploadid]' placeholder=\"Comment on $p[firstname]'s Post (enter to send) \" onkeyup=\"click_on_enter(event,'sta_btn_$r[uploadid]')\"/></td><td>
													<button class='btn btn-danger' id='sta_btn_$r[uploadid]' onclick=\"comment(document.getElementById('pic_text$r[uploadid]'),$r[uploadid],'pic_post',user,token,'coms_pic_$r[uploadid]','coms_view_pic_$r[uploadid]')\">Comment</button></td></tr></table>
												</div>
											</div>
										";
									}
								}
							}
						?>
						<div id='append_area_timeline'></div>
						<?php if($query->rowCount()!=0){ ?><button class='btn btn-danger form-control' onclick='load_more_timeline("append_area_timeline",5,this,"<?php echo $user;?>");this.innerHTML="<i class=\"glyphicon glyphicon-refresh\"></i> loading. . . "'>Load More </button>
					<?php } else{echo "There are No posts by ".$userInfo->get_name();}?></div>

					<!-- pictures div -->
					<div id='main_area_pictures'  class='main_area' style='padding:10px;background:#fefefe;display:none;'>

						<h3 class='text-info'>
							<i class='icon ion-images'></i> Pictures by <?php echo $userInfo->get_name() ?>
						</h3>
						<div class='row' style='margin:auto'>
							<?php
								$sql = "SELECT * FROM uploads WHERE user='$user' ORDER BY stamp DESC limit 12";
								foreach($connection->query($sql) as $r)
								{
									$r['picture'] = file_validity($r['picture'],$r['picture']);
									echo "<div class='col-sm-3 pointer' onclick='pic_dialog($r[upid])' style='padding:3px;border:1px solid #ddd;border-radius:3px;width:150px;margin:5px;overflow:hidden;max-height:130px' >
										<img src='$r[picture]tumb.jpg' width='150PX' style=''/>
									</div>";
								}
							?>
							<div id='append_area_pictures'></div><br />
							<?php if($connection->query($sql)->rowCount() > 11 ){ ?>
							<button class='btn btn-default form-control' id='ppicviewer' onclick='load_more_pic("append_area_pictures",8,this,"<?php echo $user;?>");this.innerHTML="<i class=\"glyphicon glyphicon-refresh\"></i> loading. . . "'>Load More </button>
							<?php }?>
						</div>
					</div>

					<!-- user's profile -->
					<div id='main_area_profile'  class='main_area' style='display:none;padding:10px;background:#fefefe'>
						<?php if($user == $_SESSION['user']){ echo "<span class='pull-right pointer'><i class='icon ion-edit'></i> Edit</span>";} ?>
						<h3  class='text-info'>
							<i class='icon ion-android-contact'></i> Profile: <?php echo $userInfo->get_name() ?>
						</h3>
						<div class='row'>
							<div class='col-sm-6'>
								<?php
									$pic = get_ppic($user,$connection);
									$pic = file_validity($pic);
									echo "<center><img src='$pic' style='max-width:100%'></center>";
								?>
							</div>
							<div class='col-sm-5' style='font-size:1em;word-wrap:break-word;background:rgba(0,0,0,0.03)'>
								<b class='text-primary'><i class='icon ion-pin'></i> Basic</b><br />
								Name: <?php echo $userInfo->get_name(); ?> <br />
								Gender: <?php echo $userInfo->get_gender(); ?><br />
								Birthday: <?php echo $userInfo->get_birth_date()."<sup>th</sup> ".$userInfo->get_birth_month();?><br />
								Username: <?php echo $user ?> <br/><br />

								<b class='text-primary'><i class='icon ion-ios-help-outline'></i> Contact</b><br />
								Phone: <?php echo $userInfo->get_phone(); ?> <br />
								Email: <?php echo $userInfo->get_email(); ?><br /><br>

								<b class='text-primary'><i class='icon ion-university'></i> Education </b> <br />
								Department: <?php echo $userInfo->get_department() ?> <br />
								Level: <?php echo $userInfo->get_level() ?> <br /><br />

								<b class='text-danger'><i class='icon ion-heart'></i> <?php echo $userInfo->get_relationship(); ?> </b> <br />

							</div>
						</div>
					</div>
				</div>

				<div class='col-sm-5' >
					<div class='panel panel-danger preview' id='profile_preview' style='margin-right:0px;'>
						<div class='panel-heading'>
							<span class='pull-right pointer' onclick="$('#selector_profile').trigger('click')">
								<i class='glyphicon glyphicon-user'></i>
								<u>View Profile</u>
							</span> Summary
						</div>
						<div class='panel-body' style='padding:5px;'>
							<?php
								$userInfo = new userInfo;
								$userInfo->user = $user; $userInfo->connection = $connection;
								echo " ".$userInfo->get_name().",<br /> Studies  ".$userInfo->get_department().", ".
								$userInfo->get_gender().".<br> Following ".$userInfo->get_following().", Followed By ".$userInfo->get_followers()."";
							?>
						</div>
					</div>

					<div class='panel panel-danger preview' id='picture_preview'  class='preview' style='margin-right:0px;'>
						<div class='panel-heading'><span class='pull-right pointer' onclick="$('#selector_pictures').trigger('click')"><i class='icon ion-image'></i> <u>View all Pictures</u></span>Pictures</div>
						<div class='panel-body' style='padding:5px'>
							<div class='row' style='margin:auto'>
							<?php
								$sql = "SELECT * FROM uploads WHERE user='$user' ORDER BY RAND() limit 12";
								foreach($connection->query($sql) as $r)
								{
									$r['picture'] = file_validity($r['picture'],$r['picture']);
									echo "<div class='col-sm-4 pointer' onclick='pic_dialog($r[upid])' style='padding:0px;max-height:90px;overflow:hidden;margin:4px;max-width:80px'>
										<img src='$r[picture]tumb.jpg' height='80px' style='padding:3px;border:1px solid #ddd;border-radius:3px;'/>
									</div>";
								}
							?>
						</div>
						</div>
					</div>
					<div style='background:#fff;padding:20px;line-height:25px;'><?php include 'includes/call_to_action.php' ?> </div>
				</div>
			</div>
		</div>
		<div class='col-md-3'><?php include 'includes/online.inc'; ?></div>
	</div>

</body>

<?php
	require('includes/footer.inc');
?>

<script type="text/javascript">
	$('.h_item').click(function(){
		$('.h_item').removeClass('menu-item-active')
		$('.h_item').addClass('menu-item')
		$(this).addClass('menu-item-active')
	})

	var feed_number_init = 7
	function load_more_timeline(div,how_many,btn,user_to_load)
	{
		start = feed_number_init
		feed_number_init +=how_many
		$.post('handles/load_more_timeline.php',{username:user,token:token,start:start,stop:how_many,user_to_load:user_to_load},function(data){
			$('#'+div).append(data)
		}).success(function(data){
			btn.innerHTML = " Load More Feeds"
			if (data=='') {
				btn.style.display = 'none'
			}
		}).error(function(){
			show_alert('Unable to fetch more news feeds')
			btn.innerHTML = " Load More Feeds"
		})
	}
	var active_tab='';
	var pic_number_init = 12
	function load_more_pic(div,how_many,btn,user_to_load)
	{
		start = pic_number_init
		pic_number_init +=how_many
		$.post('handles/load_more_pic.php',{username:user,token:token,start:start,stop:how_many,user_to_load:user_to_load},function(data){
			$('#'+div).append(data)
		}).success(function(data){
			btn.innerHTML = "<i class=\"glyphicon glyphicon-download-alt\"></i> Load More Feeds"
			if(data=='')
			{
				btn.style.display = 'none'
			}
		}).error(function(){
			show_alert('Unable to fetch more news feeds')
			btn.innerHTML = "<i class=\"glyphicon glyphicon-download-alt\"></i> Load More Feeds"
		})
	}
	if(active_tab=='pic')
	{
		load_on_scroll('ppicviewer');
	}
</script>
