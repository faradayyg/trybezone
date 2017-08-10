<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$user = sanitize($_POST['user_to_load']);
	$token = sanitize($_POST['token']);
	$start = sanitize($_POST['start']);
	$stop = sanitize($_POST['stop']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	$sql = "SELECT * FROM feeds WHERE username='$user' AND type !='ppic' ORDER BY stamp DESC LIMIT $start,$stop";
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
				$img = advanced_file_validity('../'.$go['profilepic'].'tumb.jpg',$go['profilepic'].'tumb.jpg');
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
				$delete = ($po['username']==$username) ? '<span onclick="show_post_options('.$r["uploadid"].',\'status\',\''.'status'.$r['uploadid'].'\')"
				class="text-muted pointer glyphicon glyphicon-chevron-down"></span>':'';
				$sql2 = "SELECT id FROM dislikes WHERE type='status' AND upid = $r[uploadid]";
				$dislikes = $connection->query($sql2)->rowCount();
				$image = $po['profilepic'];
				$image = advanced_file_validity('../'.$image,$image);
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
				$p['picture'] = advanced_file_validity('../'.$p['picture'],$p['picture']);
				$p['profilepic'] = advanced_file_validity('../'.$p['profilepic'],$p['profilepic']);
				$gender = $p['sex'];
				if($gender == 'M')
				{
					$gender = 'Male';
				}
				else
				{
					$gender = 'Female';
				}
				$delete = ($p['user']==$username) ? '<span onclick="show_post_options('.$r["uploadid"].',\'picture\',\''.'pic'.$r['uploadid'].'\')" class="text-muted pointer glyphicon glyphicon-chevron-down"></span>':'';
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
