<?php
	//start session if not started already
	if(!isset($_SESSION))
	{
		session_start();
	}

	function online_number($user)
	{
		global $connection;
		$time = cur_time();
		$sql = "SELECT * FROM lastseen  WHERE $time - lastseen < 600 AND username !='$user' AND username !='' LIMIT 20";
		return $connection->query($sql)->rowCount();
	}

	function wowed_on($field)
	{
		global $connection;
	  $sql = "INSERT INTO `wowed`(`username`,`field`) VALUES ('$_SESSION[user]','$field')";
	  $connection->exec($sql);
	  log_action($_SESSION['user'],"Wowed on $field",$connection);
	}

	function keep_online($user,$where='web')
	{
		global $connection;
		$time = cur_time();
		$sql = "SELECT * FROM lastseen WHERE username = '$user'";
		if($connection->query($sql)->rowCount()==0)
		{
			$sql = "INSERT INTO lastseen (`username`,`lastseen`) VALUES ('$user','$time')";
			$connection->exec($sql);
		}
		$sql = "UPDATE `lastseen` set `lastseen` = '$time',`where` = '$where' WHERE `username`='$user'";
		$connection->exec($sql);
	}
	function sanitize($data)
	{
		$data = htmlspecialchars(addslashes(trim($data)));
		return $data;
	}

	function get_department($object)
	{
		global $connection;
		$ret = "Undetermined ";
		if(ctype_alpha($object) || strlen($object)>2)
		{
			$sql = "SELECT `department` FROM `users` WHERE `username` = '$object' ";
			foreach ($connection->query($sql) as $r) 
			{
				$ret = get_department($r['department']);
			}
		}
		else
		{
			$sql = "SELECT dept FROM departments WHERE id = '$object'";
			foreach ($connection->query($sql) as $r) 
			{
				$ret = $r['dept'];
			}
		}

		return $ret;
	}

	function show_new_messages($username)
	{
		echo "<b>Newest Messages</b>";
		global $connection;
		$sql = "SELECT messages.messageid,messages.sender,messages.message,messages.time FROM messages";
		$sql .= " WHERE messages.receiver = '$username' AND messages.read=0 ORDER BY stamp DESC LIMIT 15";
		foreach ($connection->query($sql) as $key) {
			$i = new userInfo;
			$i->connection = $connection;
			$i->user = $key['sender'];
			$pic = advanced_file_validity('../'.get_ppic($key['sender'],$connection).'tumb.jpg',get_ppic($key['sender'],$connection).'tumb.jpg');
			echo "
			<div style='border-bottom:1px solid #eee;line-height:20px;margin-bottom:2px;padding:3px;background:#fcc;box-shadow:0 0 2px #aaa;'>
				<table class='pointer' width='100%' onclick=\"dialog_message_view($key[messageid])\">
					<tr style='padding:5px;'>
						<td style='width:50px'>
							<img src='$pic' height='40px' width='40px'>
						</td>
						<td align='left'>
							<b><small>".$i->get_name()."</small></b><br /><small class='text-muted'>".substr($key['message'], 0,20)."... </small>
						</td>
						<td align='right'>
							<small class='text-danger'>".short_past($key['time'])."</small>
						</td>
					</tr>
				</table>
			</div>";
		}


		if($connection->query($sql)->rowCount()==0)
		{

			$sql = "SELECT messages.messageid,messages.sender,messages.message,time,receiver FROM messages";
			$sql .= " WHERE messages.receiver = '$username' OR messages.sender = '$username' ORDER BY stamp DESC LIMIT 8 ";

			foreach ($connection->query($sql) as $key) {
				$tick = '<i class="ion-arrow-return-left"></i>';
				$name = $key['sender'];
				if($key['sender']==$username)
				{
					$tick = '<i class="ion-android-done-all"></i>';
					$name = $key['receiver'];
				}
				$i = new userInfo;
				$i->connection = $connection;
				$i->user = $name;
				$pic = advanced_file_validity('../'.get_ppic($name,$connection).'tumb.jpg',get_ppic($name,$connection).'tumb.jpg');
				echo "
				<div  style='border-bottom:1px solid #ddd;line-height:20px;margin-bottom:2px;padding:3px;background:#fdfdfd;'>
					<table class='pointer' width='100%'  onclick=\"dialog_message_view($key[messageid])\">
						<tr style='padding:5px;'>
							<td width='50px'>
								<img src='$pic' height='40px' width='40px'>
							</td>
							<td align='left'>
								<b><small>".$i->get_name()."</small></b><br /><small class='text-muted'>".substr($key['message'], 0,20)."... </small>
							</td>
							<td align='right'>
								<small class='text-muted'>".short_past($key['time'])."&nbsp;&nbsp;$tick</small>
							</td>
						</tr>
					</table>
				</div>";
			}
		}
	}
	function past($time)
	{
		// use just enter ago(time)
		$timediff = time()-$time;

		$months = intval($timediff/2592000);
		$remain= $timediff%2592000;

		$weeks = intval($remain/604800);
		$remain= $remain%604800;

		$days = intval($remain/86400);
		$remain= $remain%86400;

		$hours = intval($remain/3600);
		$remain = $remain % 3600;

		$mins = intval($remain/60);
		$secs = $remain%60;

		if($secs>=0)
		{
			$ec = $secs."seconds ago";
		}
		if($mins>=0)
		{
			$ec = $mins." mins ".$secs."seconds ago";
		}
		if($hours>0)
		{
			$ec = $hours." hours, ".$mins." mins ago";
		}
		if($days>0)
		{
			$ec = $days." days, ".$hours." hours ago";
		}
		if($weeks>0)
		{
			$ec = $weeks." weeks, ".$days." days ago";
		}
		if($months>0)
		{
			$ec = $months." months, ".$weeks." weeks ago";
		}
			return $ec;
	}
	function short_past($time)
	{
		// use just enter ago(time)
		$timediff = time()-$time;

		$months = intval($timediff/2592000);
		$remain= $timediff%2592000;

		$weeks = intval($remain/604800);
		$remain= $remain%604800;

		$days = intval($remain/86400);
		$remain= $remain%86400;

		$hours = intval($remain/3600);
		$remain = $remain % 3600;

		$mins = intval($remain/60);
		$secs = $remain%60;

		if($secs>=0)
		{
			$ec = $secs."secs";
		}
		if($mins>=0)
		{
			$ec = $mins." min ";
		}
		if($hours>0)
		{
			$ec = $hours." hr";
		}
		if($days>0)
		{
			$ec = $days." d";
		}
		if($weeks>0)
		{
			$ec = $weeks." wk";
		}
		if($months>0)
		{
			$ec = $months." mth";
		}
			return $ec;
	}


	function user($path_to_easylogin)
	{
		if(!isset($_SESSION['user']))
		{
			include($path_to_easylogin);
			exit();
		}
	}

	function file_validity($file)
	{
		if(file_exists($file) ){
			return $file;
		}
		else
		{
			$file = str_replace('../','',$file);
			$file = str_replace('uploads','',$file);
			$alp = substr($file,1,1);
			return "images/cctumb/$alp.png";
		}
	}

	function advanced_file_validity($path,$file)
	{
		//this is for file checking in relative paths order than the root folder, first variable is the relative path
		//of the file, second is actual path that will be fed back to the user
		if(file_exists($path))
		{
			return $file;
		}

		else
		{
			$file = str_replace('../','',$file);
			$file = str_replace('uploads','',$file);
			$alp = substr($file,1,1);
			return "images/cctumb/$alp.png";
		}
	}

	function get_profile_badge($username,$connection)
	{
		//for a badge we need name; gender, department,level,username,profilepic path,

		$sql = "SELECT users.username,users.firstname,users.lastname,users.level,users.sex,users.department,profilepics.*
		FROM users INNER JOIN profilepics ON users.username = profilepics.username WHERE users.username = '$username'";
		try
		{
			$query = $connection ->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$return = array('firstname'=> $r['firstname'],'lastname'=>$r['lastname'],'username'=>$r['username'],'level'=>$r['level']
				,'gender'=>$r['sex'],'ppic'=>$r['profilepic'],'uploadid'=>$r['upid'],'department'=>$r['department']);
				return $return;
			}
		}
		catch(PDOException $e)
		{
			echo "<div class='alert alert-warning'>We are having problems retreiving information at the moment. . .</div>";
		}
		$connection = null;
	}

	function transform($text)
	{
		$text = sha1($text."faraday");
		return $text;
	}

	function cur_time()
	{
		date_default_timezone_set("Africa/Lagos");
		return time();
	}

	function disp_time()
	{
		date_default_timezone_set("Africa/Lagos");
		return date('D, d M Y h:i:sa');
	}

	function get_ppic($user,$connection)
	{
		global $connection;
		$sql = "SELECT profilepic FROM profilepics WHERE username = '$user'";
		try
		{
			$i = '';
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$i = $r['profilepic'];
			}
		}
		catch(PDOException $e)
		{

		}
		return $i;
	}

	function log_action($user,$action,$connection)
	{
		$t = cur_time();
		$sql = "INSERT INTO logs (`username`,`action`,`time`) VALUES ('$user','$action','$t')";
		try
		{
			$connection->exec($sql);
		}
		catch(PDOException $e)
		{
		}
	}

	/*
	*function used to compare two users mutual followees, followed by and following
	*/
	class usersInteract
	{
		public $user;
		public $other;
		public $connection;
		//function to get the people i follow
		function user_following()
		{
			$username = $this->user;
			$con = $this->connection;
 			$sql = "SELECT follower FROM followers WHERE follower='$username'";
 			return $con->query($sql)->rowCount();
		}

		//function to check who is following me
		function user_followed()
		{
			$username = $this->user;
			$con = $this->connection;
 			$sql = "SELECT username FROM followers WHERE username='$username'";
 			return $con->query($sql)->rowCount();
		}

		//function to count who othe ris following
		function other_followed()
		{
			$username = $this->other;
			$con = $this->connection;
 			$sql = "SELECT username FROM followers WHERE username='$username'";
 			return $con->query($sql)->rowCount();
		}
		//function to get the people the other person is following
		function other_following()
		{
			$username = $this->other;
			$con = $this->connection;
 			$sql = "SELECT follower FROM followers WHERE follower='$username'";
 			return $con->query($sql)->rowCount();
		}

		//get who we both are following
		function mutual()
		{
			$username = $this->user;
			$other = $this->other;
			$con = $this->connection;
			$sql = "SELECT username FROM followers WHERE follower='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);

			$other_following = array();
			$count = $counter = 0;
			foreach ($query as $r)
			{
				$other_following[$count] = $r['username'];
				$count +=1;
			}
			$len = count($other_following);
			for ($i=0; $i<$len; $i++)
			{
				$sql3 = "SELECT username FROM followers WHERE follower='$username'";
				$query = $con->query($sql3);
				$query->setFetchMode(PDO::FETCH_ASSOC);
				foreach ($query as $key)
				{
					if($other_following[$i] == $key['username'])
					{
						$counter +=1;
					}

				}

			}
			return $counter;
		}

		//check if user is following other
		function check_if_i_follow()
		{
			$username = $this->user;
			$other = $this->other;
			$con = $this->connection;
			$sql = "SELECT * FROM followers WHERE username = '$other' AND follower='$username' AND type='user'";
			if($con->query($sql)->rowCount() == 0)
			{
				return 'false';
			}
			else
			{
				return 'true';
			}
		}

		//check if other is following user
		function check_if_im_followed()
		{
			$username = $this->user;
			$other = $this->other;
			$con = $this->connection;
			$sql = "SELECT * FROM followers WHERE username = '$username' AND follower='$other' AND type='user'  ";
			if($con->query($sql)->rowCount() == 0)
			{
				return 'false';
			}
			else
			{
				return 'true';
			}
		}

		function get_name()
		{
			$other = $this->other;
			$con = $this->connection;
			$sql = "SELECT firstname,lastname FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[firstname] $e[lastname]");
			}
		}
	}

    class userInfo
    {
    	public $user;
    	public $connection;

    	function get_first_name()
    	{
    		$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT firstname,lastname FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[firstname]");
			}
    	}
    	function get_last_name()
    	{
    		$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT firstname,lastname FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[lastname]");
			}
    	}
		function get_name()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT firstname,lastname FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[firstname] $e[lastname]");
			}
		}

		function get_department()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT department FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) 
			{
				return get_department("$e[department]");
			}
		}


		function get_department_id()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT department FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) 
			{
				return $e['department'];
			}
		}

		function get_level()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT level FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				if($e['level']=='100?')
				{
					return "Not set";
					exit();
				}
				return ucwords("$e[level]");
			}
		}

		function get_phone()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT value FROM settings WHERE username='$other' AND setting = 'show_phone'";
			$query = $con->query($sql);
			foreach ($query as $r) {
				if($r['value'] == 0)
				{
					return "Hidden";
					exit();
				}
			}
			$sql = "SELECT phone FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[phone]");
			}
		}


		function get_birth_date()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT day FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[day]");
			}
		}


		function get_birth_month()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT month FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[month]");
			}
		}

		function get_email()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT value FROM settings WHERE username='$other' AND setting = 'show_email'";
			$query = $con->query($sql);
			foreach ($query as $r) {
				if($r['value'] == 0)
				{
					return "Hidden";
					exit();
				}
			}
			$sql = "SELECT email FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				return ucwords("$e[email]");
			}
		}

		function get_gender()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT sex FROM users WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) {
				if(strtolower($e['sex'])=='m')
				{
					return "Male";
				}
				else
				{
					return "Female";
				}
			}
		}

		function get_relationship()
		{
			$other = $this->user;
			$con = $this->connection;
			$sql = "SELECT relationship FROM relationship WHERE username='$other'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e) 
			{

				return $e['relationship'];
			}

			if($query->rowCount() == 0)
			{
				return "Single and Searching";
			}
		}
		function get_following()
		{
			$username = $this->user;
			$con = $this->connection;
 			$sql = "SELECT follower FROM followers WHERE follower='$username' and type='user'";
 			return $con->query($sql)->rowCount();
		}

		function get_last_seen()
		{
			$username = $this->user;
			$con = $this->connection;
			$sql = "SELECT * FROM lastseen WHERE username='$username'";
			$query = $con->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $e)
			{
				return past($e['lastseen'])." "."<small class='text-muted'><i class='ion-android-globe'></i> $e[where]</small>";
			}
		}
		function get_followers()
		{
			$username = $this->user;
			$con = $this->connection;
 			$sql = "SELECT username FROM followers WHERE username='$username' and type='user'";
 			return $con->query($sql)->rowCount();
		}

		function get_channels()
		{
			$username = $this->user;
			$con = $this->connection;
 			$sql = "SELECT username FROM followers WHERE follower='$username' and type='channel'";
 			return $con->query($sql)->rowCount();
		}
	}

	class channelInfo
	{
		public $channel_name;
		public $connection;

		function get_name()
		{
			$username = $this->channel_name;
			$con = $this->connection;
 			$sql = "SELECT channel FROM channels WHERE username='$username'";
 			foreach ($con->query($sql) as $r) {
 				return $r['channel'];
 			}
		}

		function get_info()
		{
			$username = $this->channel_name;
			$con = $this->connection;
 			$sql = "SELECT info FROM channels WHERE username='$username'";
 			$info = "$username Has no description ";
 			foreach ($con->query($sql) as $r) {
 				$info = $r['info'];
 			}
 			return $info;
		}
	}

	function get_number_of_channels()
	{
		global $connection;
		$sql  = "SELECT channel FROM channels";
		return $connection->query($sql)->rowCount();
	}

	function get_new_messages($user)
	{
		global $connection;
		$sql = "SELECT * FROM messages WHERE `receiver`='$user' AND `read` = 0 ";
		$num = $connection->query($sql)->rowCount();
		if($num > 20)
		{
			$num = '20+';
		}
		elseif($num==0)
		{
			$num = "";
		}
		return $num;
	}
	function get_new_notifications($user)
	{
		global $connection;
		$sql = "SELECT * FROM notifications WHERE `participant`='$user' AND `seen` = 0 AND latest !='' AND latest !='$user' AND `following`=1 ";
		$num = $connection->query($sql)->rowCount();
		if($num > 20)
		{
			$num = '20+';
		}
		elseif($num==0)
		{
			$num = "";
		}
		return $num;
	}

	function get_name($other)
	{
		global $connection;
		$sql = "SELECT firstname,lastname FROM users WHERE username='$other'";
		$query = $connection->query($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		foreach ($query as $e) {
			return ucwords("$e[firstname] $e[lastname]");
		}
	}

		function show_new_notifications($user)
		{
			echo "<b>Newest Notifications</b>";
			global $connection;
			$sql = "SELECT * FROM notifications WHERE `participant`='$user' AND latest !='' AND latest !='$user' AND following='1' ORDER BY stamp DESC LIMIT 30";
			$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $r) {
				$sql2 = "SELECT type FROM notifications WHERE `participant`='$r[latest]' AND cid='$r[cid]'";
				$query2 = $connection->query($sql2);$query2->setFetchMode(PDO::FETCH_ASSOC);
				foreach ($query2 as $r2)
				{
					$action = $r2['type'];
					if($r2['type']=='COM')
					{
						$a = "Commented on";
					}
					if($r2['type']=='pic')
					{
						$a = "Commented on";
					}
					if($r2['type']=='pic_like')
					{
						$a = "Liked";
					}
					if($r2['type']=='com_like')
					{
						$a = "Liked";
					}
				}
				if($r['type']=='COM' )
				{
					$icon = "<i style='font-size:1.43em'class='ion-chatboxes pull-right text-muted'></i>";
					$body = "$a a post you also Commented on";
					$extra = "href='view.php?id=$r[cid]&type=post'";
				}
				elseif ($r['type']=='created') {
					$icon = "<i style='font-size:1.43em'class='ion-quote pull-right text-muted'></i>";
					$body = "$a Your Post";
					$extra = "href='view.php?id=$r[cid]&type=post'";
				}
				elseif ($r['type']=='pic_created') {
					$icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
					$body = "$a on a picture you Uploaded";
					$extra = "onclick=\"pic_dialog($r[cid])\" class='pointer'";
				}
				elseif ($r['type']=='pic') {
					$icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
					$body = "$a a picture you also commented on";
					$extra = "onclick=\"pic_dialog($r[cid])\" class='pointer'";
				}
				elseif ($r['type']=='pic_like') {
					$icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
					$body = "$a a picture you Liked";
					$extra = "onclick=\"pic_dialog($r[cid])\" class='pointer'";
				}
				elseif ($r['type']=='com_like') {
					$icon = "<i style='font-size:1.43em'class='ion-quote pull-right text-muted'></i>";
					$body = "$a a post you Liked";
					$extra = "href='view.php?id=$r[cid]&type=post'";
				}
				elseif ($r['type']=='follow') {
					$icon = "<i style='font-size:1.43em'class='ion-person-stalker pull-right text-muted'></i>";
					$body = "Started Following you";
					$extra = "onclick='mark_notif_read($r[id],\"follow\")' class='pointer'";
				}
				else
				{
					$icon ='';
					$body = '';
					$extra = '';
				}
				$background="#fdfdfd";
				if($r['seen']==0)
				{
					$background = "#fcc";
				}
			$image = advanced_file_validity('../'.get_ppic($r['latest'],$connection),get_ppic($r['latest'],$connection));
				echo "<a $extra style='color:#444'><div onclick='this.style.background=\"#ededed\"'
				style='background:$background;padding:5px;margin:0px;border-bottom:1px solid #ddd;'><table><tr><td>
				<img src='$image".'tumb.jpg'."' style='border-radius:30px' height='40px' width='40px'>&nbsp;&nbsp;</td><td><small> ".get_name($r['latest'])."$icon
				 $body </small><br /><small class='text-muted'>".past($r['stamp'])."</small></td></tr></table></div></a>";
			}
		}

		function compare_users($user,$follower)
		{
			global $connection;
			$sql = "SELECT * FROM followers WHERE username = '$user' AND follower='$follower' AND type='user'";
			if($connection->query($sql)->rowCount() == 0)
			{
				return 'false';
			}
			else
			{
				return 'true';
			}
		}

		function notify($username,$type,$id)
		{
			global $connection;
			$sql = "SELECT * FROM notifications WHERE cid = '$id' AND participant = '$username' ";
			$tuse = cur_time();
			if($connection->query($sql)->rowCount()==0)
				{
					$sql = "INSERT INTO notifications (cid,type,participant,stamp) VALUES('$id','$type','$username','$tuse')";
					$connection->exec($sql);
					$sql = "UPDATE notifications set `seen`=0 , `latest`='$username',`stamp`='$tuse' WHERE cid='$id'";
					$connection->exec($sql);
					$sql = "UPDATE notifications set `seen`=0 , `latest`='$username',`stamp`='$tuse', `type`='$type' WHERE cid='$id' AND participant = '$username'";
					$connection->exec($sql);
				}
			else
				{
					$sql = "UPDATE notifications set `seen`=0 , `latest`='$username',`stamp`='$tuse' WHERE cid='$id' ";
					$connection->exec($sql);
					$sql = "UPDATE notifications set `seen`=0 , `latest`='$username',`stamp`='$tuse', `type`='$type' WHERE cid='$id' AND participant = '$username'";
					$connection->exec($sql);
				}
		}

		function check_channel_follow($user,$channel)
		{
			global $connection;
			$query = "SELECT `type` FROM `followers` WHERE `username`='$channel'  AND follower = '$user' AND `type`='group'";
			return $connection->query($query)->rowCount();
		}

		function num_birthdays($username)
		{
			global $connection;
			$day_today = date("d");
			$month_today = date("F");
			$query = "SELECT `firstname` FROM users WHERE day='$day_today' and month = '$month_today'";
			return $connection->query($query)->rowCount();
		}

		function my_birthday($user)
		{
			global $connection;
			$day_today = date("d");
			$month_today = date("F");
			$query = "SELECT `firstname` FROM users WHERE day='$day_today' and month = '$month_today' and username = '$user'";
			$result =  ($connection->query($query)->rowCount()==0) ? '':'Has Birthday Today';
			return $result;
		}

	function get_mobile_home($logged,$start,$limit,$page)
	{
	  global $connection;
      $sql = "SELECT subscriptions.subs_to,feeds.* FROM subscriptions ";
      $sql .= " INNER JOIN feeds ON subscriptions.subs_to = feeds.username ";
      $sql .= " WHERE subscriptions.username='$logged' ";
      $sql .= " ORDER BY feeds.stamp DESC LIMIT $start,$limit";
      try
      {
        $query = $connection ->query($sql) ;
        $query->setFetchMode(PDO::FETCH_ASSOC);
        if($query->rowCount() == 0)
        {
          echo "
          <div style='padding:30px;line-height:35px;background:#fff'>
          <h2 align='center' style='line-height:35px;'><center><i style='font-size:2em' class='ion-android-sad'></i></center><br />There are No posts to View,<br /> Try <a href='follow_friend.php'>following</a>
          Some people to get started!<br />&nbsp;<br /><small> psssst... Did you know you can  make your own posts and they will be displayed here too? ^_^</h2>
          <a href='follow_friend.php'>click to find people to follow</a>
          </div>";
        }
        foreach($query as $r)
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
              $com_sql = "SELECT * FROM cp_comments WHERE p_id = '$go[aid]'";
              $num_comments = $connection->query($com_sql)->rowCount();
              $img = file_validity('../'.$go['profilepic'].'tumb.jpg');
              $time = past($go['time']);

              $sql3 = "SELECT id FROM likes WHERE type='group' AND upid = $r[uploadid]";
              $sql31 = "SELECT id FROM likes WHERE type='group' AND upid = $r[uploadid] AND username='$logged'";
              $likes = $connection->query($sql3)->rowCount();
              $likes_c = $connection->query($sql31)->rowCount();
              $do = 'rem';
              $tip = 'Unlike';
              if($likes_c == 0)
              {
                $tip = 'Like ';
                $do = 'add';
              }

              echo "<div class='panel panel-default update' style='border-radius:0px;margin:2px'><div class='panel-body' style='padding-bottom:3px'>
              <table width='100%'><tr><td width='43px'>
              <img src='$img' height='40px' width='40px'></td><td>
              <b><a href='profile.php?view=$go[uname]'>".$go['firstname'],"
              $go[lastname]</a></b> <br /><small class=''> posted in $go[chan]</small> <small class='text-muted'>$time</small>
              </td></tr></table><br />
              <center>$go[post]</center>
              <br /><center>
                <a  onclick=\"like('cha',$r[uploadid],this)\">$tip</a> |
              <a href='c_p_v.php?view=$r[uploadid]'>Comments: $num_comments</a> |
                <a href='likes.php?type=group&view=$r[uploadid]'>likes: $likes</a></center>
              </div>
              <!--div class='panel-footer'>
                <table width='100%'><tr><td>
                <input type='text' class='form-control' placeholder=\"Comment on $go[firstname]'s Post (enter to send) \"/></td><td>
                <button class='btn btn-danger' id='btn_grp_$r[uploadid]'>Comment</button></td></tr></table>
              </div-->
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
              $sql31 = "SELECT id FROM likes WHERE type='status' AND upid = $r[uploadid] AND username='$logged'";
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
              $sql2 = "SELECT id FROM dislikes WHERE type='status' AND upid = $r[uploadid]";
              $dislikes = $connection->query($sql2)->rowCount();
              $image = $po['profilepic'];
              $image = file_validity('../'.$image);
              echo"
                <div class='panel panel-default update'style='border-radius:0px;margin:4px'>
                  <div class='panel-body' style='padding-bottom:3px;'>
                  <table><tr><td width='43px'><img src='$image".'tumb.jpg'."' height='40px' /></td><td>
                  <a href='profile.php?view=$r[username]' ><b>".ucwords("$po[firstname] $po[lastname]</b>")."</a><br /><small class='text-muted'>$r[disp]</small></td></tr></table>
                  <span style=\"white-space:pre-line;word-wrap:break-word;background:transparent;padding:0px;border-color:transparent;overflow:initial;word-break:break-all;word-wrap:break-word; \">
                  \n &para; $po[content] </span>
                  <br />
                  <br />
                    <center><a onclick=\"like('sta',$r[uploadid],this)\">$tip</a> | <a href='view_post.php?view=$r[uploadid]'>Comments: $coms</a> | <a href='likes.php?type=sta&view=$r[uploadid]'>Likes: $likes</a></center>
                  </div>
                  <!--div class='panel-footer'>
                  </small>
                  <table width='100%'><tr><td>
                  <input type='text' class='form-control' placeholder=\"Comment on $po[firstname]'s Post \"/></td><td>
                  <button class='btn btn-danger'>Comment</button></td></tr></table>
                  </div-->
                </div>
              ";
            }
          }
          if($r['type'] == 'picture')
          {
            $sql3 = "SELECT id FROM likes WHERE type='picture' AND upid = $r[uploadid]";
            $sql31 = "SELECT id FROM likes WHERE type='picture' AND upid = $r[uploadid] AND username='$logged'";
            $likes = $connection->query($sql3)->rowCount();
            $likes_c = $connection->query($sql31)->rowCount();
              $do = 'rem';
              $tip = 'Unlike';
              if($likes_c == 0)
              {
                $do = 'add';
                $tip = 'Like';
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
              $p['picture'] = file_validity('../'.$p['picture']);
              $p['profilepic'] = file_validity('../'.$p['profilepic']);
              $gender = $p['sex'];
              if($gender == 'M')
              {
                $gender = 'Male';
              }
              else
              {
                $gender = 'Female';
              }
              echo"
                <div class='panel panel-default update' style='border-radius:0px;margin:4px'>
                  <div class='panel-body'>
                    <div style='position:relative;right:0px;' align='right' class='pointer' >
                    </div>
                    <table><tr><td width='43px'><img src='$p[profilepic]".'tumb.jpg'."' height='40px'></td><td>
                    <div class='name'><a href='profile.php?view=$r[username]'>
                      <b>".ucwords("$p[firstname] $p[lastname]")."</b></a><br /><small class='text-muted'> $r[disp]</small><br />
                    </div></td></tr></table>
                    <p></p><p>$p[content]</p>
                    <div align='center'><span >
                    <a href='photo_view.php?view=$r[uploadid]'><img src='$p[picture]tumb.jpg' class='pointer' height='200px' style='max-width:150%;min-width:200px;max-width:100%'></a></span></div>
                  </div><br />
                  <center><a onclick=\"like('pic',$r[uploadid],this)\">$tip</a> | <a href='photo_view.php?view=$r[uploadid]'>Comments: $num_coms</a> | <a href='likes.php?type=pic&view=$r[uploadid]'>Likes: $likes</a></center>
                  <!--div class='panel-footer'>
                  <small class='text-muted'>  <span class='pointer hint--top hint--rounded hint--bounce hint--info' onclick=\"show_comments('pic','coms_view_pic_$r[uploadid]',$r[uploadid])\" data-hint='Click to view comments'>Comments</span><span id='coms_pic_$r[uploadid]'> $num_coms</span></small>
                    <table width='100%'><tr><td><input type='text' class='form-control' id='pic_text$r[uploadid]' placeholder=\"Comment on $p[firstname]'s Post (enter to send) \" onkeyup=\"click_on_enter(event,'sta_btn_$r[uploadid]')\"/></td><td>
                    <button class='btn btn-danger' id='sta_btn_$r[uploadid]' onclick=\"comment(document.getElementById('pic_text$r[uploadid]'),$r[uploadid],'pic_post',user,token,'coms_pic_$r[uploadid]','coms_view_pic_$r[uploadid]')\">Comment</button></td></tr></table>
                  </div-->
                </div>
              ";
            }
          }
        }
        echo "<center>";
        $new_start = $start+$limit;

        $sql1 = "SELECT subscriptions.subs_to,feeds.* FROM subscriptions ";
        $sql1 .= " INNER JOIN feeds ON subscriptions.subs_to = feeds.username ";
        $sql1 .= " WHERE subscriptions.username='$logged' ";
        $sql1 .= " OR subscriptions.subs_to = '$logged' ";
        $sql1 .= " ORDER BY feeds.stamp DESC LIMIT $new_start,$limit";

        $next_page_count = $connection->query($sql1)->rowCount();
        if($next_page_count>0)
        {
          $new_page = $page+1;
          echo "<a href='?page=$new_page' class='btn btn-danger form-control text-danger'><b>Show More Stories</b></a>";
        }
        echo "</center>";
      }
      catch(PDOException $e)
      {
        echo DATABASE_ERROR_MSG;
        if(TESTING == 1)
        {
          echo $e->getMessage();
        }
      }
	}

		function return_news_categories()
		{
			global $connection;
			$sql = "SELECT * FROM `news_categories`";
			$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
			$array = array(0=>'General');
			foreach ($query as $r)
			{
				$array[$r['id']] = $r['name'] ;
			}
			return $array;
		}

		function return_departments()
		{
			global $connection;
			$sql = "SELECT * FROM `departments` ORDER BY `dept` ASC";
			$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
			$array = array(0 => 'Pre Degree' );
			foreach($query as $r)
			{
				$array[$r['id']] = $r['dept'];
			}
			return $array;
		}

		function check_if_i_follow($username)
		{
			$other = $username;
			$username = $_SESSION['user'];
			global $connection;
			$sql = "SELECT * FROM followers WHERE username = '$other' AND follower='$username' AND type='user'";
			if($connection->query($sql)->rowCount() == 0)
			{
				return 'false';
			}
			else
			{
				return 'true';
			}
		}


//################################################## Blog Functions #######################################################//

function verify_blog_pic($pic)
{
	if(file_exists($pic) == 0)
	{
		$pic = "../images/no_news_image.png";
	}
	elseif(filesize($pic)<5)
	{
		$pic = "../images/no_news_image.png";
	}

	return $pic;
}

function increment_news_views($id,$user)
{
	global $connection;
	$sql = "INSERT INTO `news_views` (`news_id`,`person`) VALUES('$id','$user')";
	$connection->exec($sql);
}
?>
