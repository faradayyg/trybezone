<?php
  session_start();
  include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';

?>

<div style="paddingpx;background:#fff">

<?php
  $username = sanitize($_GET['channel']);
  $sql = "SELECT * FROM followers WHERE follower='$_SESSION[user]' AND username='$username' AND type='group'";
  if($connection->query($sql)->rowCount()==0)
  {
    echo "<h3>Sorry You are not yet a Member of this group</h3>
    <br /> <a href='channels.php' class='btn btn-danger'>Go back</a>
    ";
    exit();
  }
  $sql = "SELECT * FROM channels WHERE username = '$username'";
  if($connection->query($sql)->rowCount()==0)
  {
    echo "<div class='panel-body text-danger' style='background:#fff'><h1 align='center'><i class='ion-android-sad'></i></h1>
    <center>Oops, Seems the group you are trying to view is no longer available
    or does not exist<span class='text-muted'><br />But dont worry, You can Find Simmilar Channels <a href='channels.php'>Here</a></span> </center></div>";
  }
  else
  {
    $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
    foreach($query as $r)
    {
      echo "<title>$r[channel] | ".SITE_NAME."</title><div class='panel panel-default' style='border:0px solid;padding:0px;margin:0px'>
      <div style='background:url(../$r[avatar]);background-size:100%;height:200px'>
      <div style='height:100%;background:rgba(0,0,0,0.4);padding-left:10px'>
      <br /><br />&nbsp;&nbsp;<h3 style='color:#fff'>$r[channel]<br /><small style='color:#fff'> $r[info]</small></h3></div></div>
      <div class='panel-body'>
      ";
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
        VALUES('$_SESSION[user]','$username','$time','$post')";
      $connection->exec($sql);
      $lastid = $connection->lastInsertId();$disp = disp_time();
      $sql = "INSERT INTO feeds (`uploadid`,`disp`,`username`,`visible`,`type`)
      VALUES('$lastid','$disp','$username','1','group')";
      $connection->exec($sql);
    }
    $limit = 5;
    $page = (isset($_GET['page'])) ? sanitize($_GET['page']) : 1 ;
    $page = ($page<1) ? 1 : $page;
    $start = ($page-1)*$limit;
    $sql = "SELECT channel_posts.*,profilepics.profilepic,users.firstname,users.lastname FROM channel_posts";
    $sql .= " INNER JOIN users ON users.username = channel_posts.username";
    $sql .= " INNER JOIN profilepics ON profilepics.username = channel_posts.username";
    $sql .= " WHERE visible = '1' AND channel = '$username'ORDER BY time DESC LIMIT $start,$limit ";
    $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
    foreach($query as $r)
    {
      $com_sql = "SELECT * FROM cp_comments WHERE p_id = '$r[id]'";
      $num_comments = $connection->query($com_sql)->rowCount();
      $img = file_validity('../'.$r['profilepic']);
      echo"<div class='panel panel-default' style='border-radius:0px;'>
      <div style='padding-left:10px;padding-right:10px;' >
        <small class='text-muted pull-right'>
        ".past($r['time'])."</small><img src='$img".'tumb.jpg'."' height='40px' width='40px'> <a href='profile.php?view=$r[username]'>".ucwords("$r[firstname] $r[lastname]")."</a>
        <center>$r[post]</center><br />
      <center>	<a href='c_p_v.php?view=$r[id]'>View post (in full)</a> | Comments: $num_comments </center>

      </div>
      </div>";
    }
    if($page>1)
    {
      $o_p = $page-1;
      echo "<div class='col-xs-6'><a href='?channel=$username&page=$o_p'>&larr; Previous Stories</a></div>";
    }
    if($query->rowcount()==$limit)
    {
      $n_page = $page+1;
      echo "<div class='col-xs-6' align='right'><a href='?channel=$username&page=$n_page'>More Stories &rarr; </a></div>";
    }
    echo"</div></div>";
  }
?>
</div>

<?php
  include 'includes/footer.inc';
 ?>
