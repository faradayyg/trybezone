<?php
  session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
  $logged = $_SESSION['user'];
  $limit = 15;
  $page = 1;
  if(isset($_GET['page']))
  {
    $page = sanitize($_GET['page']);
  }
  if($page < 1)
  {
    $page = 1;
  }
  $start = ($page-1)*$limit;
  //determine weather to show suggestions
  $sql_for_follow = "SELECT * FROM `followers` WHERE `follower`='$_SESSION[user]'";
  if($connection->query($sql_for_follow)->rowCount()<30)
  {
      $_GET['show_suggestions'] = 'True';
  }
?>
    <title>Home </title>
		<div style='min-height:350px;padding:5px;background:#dcc'>
      <?php
      if(strtolower($_SERVER["REQUEST_METHOD"])=='post')
      {
        $time = cur_time();
        $disp = disp_time();
        $username = $logged;
        $post = sanitize($_POST['post']);
        if(!empty($post))
        {
          $sql = "INSERT INTO posts (`username`,`content`,`time`,`disp`) VALUES ('$username','$post','$time','$disp')";
          $connection->exec($sql);
          $last_id = $connection->lastInsertId();
          $sql2 = "INSERT INTO feeds (`type`,`uploadid`,`disp`,`username`) VALUES ('status','$last_id','$disp','$username')";
          $connection->exec($sql2);
          notify($username,'created',$last_id);
          log_action($username,'Updated from mobile homepage',$connection);
        }
        else {
          echo "<div class='alert alert-warning' style='border-radius:0px'><b>Sorry,
          your post seemed to be empty and you cannot make empty posts. Please try again</b></div>";
        }
      }
       ?>
       <table><tr><td></td></tr><tr><td><a href='upload.php' ><img src='images/cam.png' height="25px"></a></td><td>
         <a href='upload.php' style="padding-top:15px"><span  style='color:#333'> Add Photos</span></a></td></tr></table>
			<table width='100%' style='background:#fff'>
				<tr>
				<td>
          <?php $image = get_ppic($logged,$connection); $image=file_validity('../'.$image.'tumb.jpg'); ?>
					<a href='profile.php'><img src='<?php echo $image ?>' height='40px' width='40px' style='padding:3px;background:#fff;'></a>
				</td>
        <form method="post">
				<td width='100%'>
				<textarea class='special_input' name='post' placeholder='What are you thinking ?' style='height:40px;padding:10px;'></textarea>
				</td>
				<td>
					<button class='btn btn-danger' style='height:40px;'>Post</button>
				</td></form>
				</tr>

			</table>


      <?php
      echo "<div align='center' style='background:#fee'><a href='birthdays.php' style='color:#000'><img src='images/people.png' height='40px'> There are ".num_birthdays($logged)."
    	birthdays Today <small class='text-muted'> (Click to see)</small></a></div>";
      $sql = "SELECT * FROM notifications WHERE `participant`='$logged' AND latest !='' AND latest !='$logged' AND following='1' AND seen='0' ORDER BY stamp DESC LIMIT 3";
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
          $body = " $a a post you also Commented on";
          $extra = "href='view_post.php?view=$r[cid]&type=post'";
        }
        elseif ($r['type']=='created') {
          $icon = "<i style='font-size:1.43em'class='ion-quote pull-right text-muted'></i>";
          $body = "$a Your Post";
          $extra = "href='view_post.php?view=$r[cid]&type=post'";
        }
        elseif ($r['type']=='pic_created') {
          $icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
          $body = "$a on a picture you Uploaded";
          $extra = "href=\"photo_view.php?view=$r[cid]\" class='pointer'";
        }
        elseif ($r['type']=='pic') {
          $icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
          $body = "$a a picture you also commented on";
          $extra = "href=\"photo_view.php?view=$r[cid]\" class='pointer'";
        }
        elseif ($r['type']=='pic_like') {
          $icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
          $body = "$a a picture you Liked";
          $extra = "href=\"photo_view.php?view=$r[cid]\" class='pointer'";
        }
        elseif ($r['type']=='com_like') {
          $icon = "<i style='font-size:1.43em'class='ion-quote pull-right text-muted'></i>";
          $body = "$a a post you Liked";
          $extra = "href='view_post.php?view=$r[cid]&type=post'";
        }
        elseif ($r['type']=='follow') {
          $icon = "<i style='font-size:1.43em'class='ion-person-stalker pull-right text-muted'></i>";
          $body = "Started Following you";
          $extra = "href='notif.php?all_is_read' onclick='mark_notif_read($r[id],\"follow\")' class='pointer'";
        }
        else
        {
          $icon ='';
          $body = '';
          $extra = '';
        }
        $background="#eee";
        if($r['seen']==0)
        {
          $background = "#fcc";
        }
      $image = advanced_file_validity('../'.get_ppic($r['latest'],$connection).'tumb.jpg','../'.get_ppic($r['latest'],$connection).'tumb.jpg');
        echo "<a $extra style='color:#000'><div onclick='this.style.background=\"#ededed\"' style='background:$background;padding:5px;margin:5px;'>
        <img src='$image' style='border-radius:30px' height='40px' width='40px'> <b>".get_name($r['latest'])."</b>
         $body <small class='text-muted'>".past($r['stamp'])."</small></div></a>";
      }
      if($query->rowCount() != 0)
      {
        echo "<center><a href='notif.php'>View All Notifications</a></center>";
      }
      if(isset($_GET['show_suggestions']))
      {
        echo"
        <div class='panel panel-default'>
          <div class='panel-body'>
            ";
            $username = $_SESSION['user'];
            $u = new userInfo;
            $u->user = $_SESSION['user'];
            $u->connection = $connection;
            $name = $u->get_first_name();
            $following = $u->get_following();

            $counter = 0;
            $pcount = 0;
            $sql = "SELECT users.username FROM users WHERE username !='$username' ORDER BY RAND() LIMIT 4";
            $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
            foreach ($query as $r) {
              $person[$counter] = $r['username'];
              $counter++;
            }

            foreach ($person as $r) {
              $sql2 = "SELECT username FROM followers WHERE username='$r' and follower = '$username' and username!='$username' AND type='user'";
              if($connection->query($sql2)->rowCount()==0)
              {
                $pcount++;
                $usr = new userInfo;
                $usr->user = $r;
                $usr->connection = $connection;
                $usr_name = $usr->get_first_name();
                $ppic = get_ppic($r,$connection);
                $ppic = file_validity('../'.$ppic.'tumb.jpg');
                echo "<div class='col-xs-3' style='padding:5px'>
                <img src='$ppic' height='40px' width='40px'><br />
                <a href='profile.php?view=$r'>$usr_name</a><br />
                <button onclick=\"follow('$r',this,'user')\"  style='padding:2px'>
                  Follow
                </button>
                </div>";
              }
            }
            $sm = ($pcount==0) ? '':"<a href='follow_friend.php'>show More</a>";
            echo"$sm
          </div>
        </div>
        ";
      }
      get_mobile_home($logged,$start,$limit,$page);
       ?>
		</div>


		<?php
    include 'includes/footer_menu.inc';
		include 'includes/footer.inc';
		 ?>
