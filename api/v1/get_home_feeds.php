<?php
include('include.inc');
$username = sanitize($_POST['username']);
$token = sanitize($_POST['token']);
$response = array();
$data = array();
$use = array();
$count = 0;
if($token == transform($username))
{
	$response = ["status"=>OK];
	$sql = "SELECT subscriptions.subs_to,feeds.* FROM subscriptions INNER JOIN feeds ON subscriptions.subs_to =
	feeds.username WHERE subscriptions.username='$username' ORDER BY feeds.stamp DESC LIMIT 0,20";
	$query = $connection->query($sql);
	foreach ($query as $r) 
	{
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
				$sql3 = "SELECT id FROM likes WHERE type='group' AND upid = $r[uploadid]";
				$sql31 = "SELECT id FROM likes WHERE type='group' AND upid = $r[uploadid] AND username='$username'";
				$com_sql = "SELECT * FROM cp_comments WHERE p_id = '$go[aid]'";
				$num_comments = $connection->query($com_sql)->rowCount();
				$likes = $connection->query($sql3)->rowCount();
				$likes_c = $connection->query($sql31)->rowCount();
				$do = 'rem';
				$tip = 'Unlike';
				if($likes_c == 0)
				{
					$tip = 'Like ';
					$do = 'add';
				}
				$img = advanced_file_validity('../../'.$go['profilepic'].'tumb.jpg',$go['profilepic'].'tumb.jpg');
				$temp = array();
				$temp['type'] = $r['type'];
				$temp['id'] = $go['id'];
				$temp['ppic'] = $img;
				$temp['likes'] = $likes;
				$temp['comments'] = $num_comments;
				$temp['tip'] = $tip;
				$temp['do'] = $do;
				$temp['content'] = $go['post'];
				$temp['username'] = $go['username'];
				$temp['channel'] = $go['chan'];
				$temp['time'] = short_past($go['time']);
				$temp['name'] = $go['firstname']." ".$go['lastname'];
				array_push($use,$temp);
				unset($temp);
			}
		}
		if($r['type'] == 'status')
		{
			$sql2 = "SELECT posts.*,users.firstname,users.lastname,
			profilepics.profilepic FROM posts";
			$sql2 .= " INNER JOIN users on users.username = posts.username ";
			$sql2 .= " INNER JOIN profilepics on users.username = profilepics.username ";
			$sql2 .= " WHERE posts.id = '$r[uploadid]'  AND posts.visible = '1'";
			$pq = $connection ->query($sql2);
			$pq ->setFetchMode(PDO::FETCH_ASSOC);

			foreach($pq as $po)
			{
				//get number of comments
				$coms = $connection->query("SELECT coms.*FROM coms WHERE upid='$r[uploadid]' ")->rowCount();
				//get likes for each post
				$sql3 = "SELECT id FROM likes WHERE type='status' AND upid = $r[uploadid]";
				$sql31 = "SELECT id FROM likes WHERE type='status' AND upid = $r[uploadid] AND username='$username'";
				$likes = $connection->query($sql3)->rowCount();
				$likes_c = $connection->query($sql31)->rowCount();
				$do = 'rem';
				$tip = 'Unlike';
				if($likes_c == 0)
				{
					$tip = 'Like ';
					$do = 'add';
				}

				$image = $po['profilepic'];
				$image = advanced_file_validity('../../'.$image,$image);
				$temp = array();
				$temp['name'] = $po['firstname']." ".$po['lastname'];
				$temp['type'] = $r['type'];
				$temp['time'] = short_past($po['time']);
				$temp['ppic'] = $image."tumb.jpg";
				$temp['comments'] = $coms;
				$temp['id'] = $po['id'];
				$temp['tip'] = $tip;
				$temp['do'] = $do;
				$temp['username'] = $po['username'];
				$temp['content'] = $po['content'];
				$temp['likes'] = $likes;
				array_push($use,$temp);
				unset($temp);
			}
		}
		if($r['type'] == 'picture')
		{
			$sql3 = "SELECT id FROM likes WHERE type='picture' AND upid = $r[uploadid]";
			$sql31 = "SELECT id FROM likes WHERE type='picture' AND upid = $r[uploadid] AND username='$username'";
			$likes = $connection->query($sql3)->rowCount();
			$likes_c = $connection->query($sql31)->rowCount();
			$do = 'rem';
			$tip = 'Unlike';
			if($likes_c == 0)
			{
				$do = 'add';
				$tip = 'Like';
			}
			$num_comments = $connection->query("SELECT upid FROM uploadcomments WHERE upid = '$r[uploadid]'")->rowCount();
			$sql2 = "SELECT uploads.*,profilepics.profilepic,users.firstname,users.lastname,users.department,users.level,users.sex 
			FROM uploads INNER JOIN users ON users.username = uploads.user INNER JOIN profilepics ON users.username = profilepics.username 
			WHERE uploads.upid = '$r[uploadid]'";
			$pq = $connection ->query($sql2);
			$pq ->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($pq as $p)
			{
				$temp['type'] = $r['type'];
				$temp['id'] = $r['uploadid'];
				$temp['username'] = $r['username'];
				$temp['name'] = $p['firstname']." ".$p['lastname'];
				$temp['likes'] = $likes;
				$temp['comments'] = $num_comments;
				$temp['tip'] = $tip;
				$temp['do'] = $do;
				$temp['picture'] = advanced_file_validity('../../'.$p['picture'],$p['picture']);
				$temp['ppic'] = advanced_file_validity('../../'.$p['profilepic'],$p['profilepic'].'tumb.jpg');
				$temp['content'] = $p['content'];
				array_push($use, $temp);
				unset($temp);
			}
		}
	}
	if($query->rowCount() == 0)
	{
		$response['number'] = "not_zero";
	}
	else
	{
		$response["data"] = $use;
	}
}

else
{
	$response = ["status"=>ERR];
}

echo json_encode($response);
?>
