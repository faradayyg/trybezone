<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
  $user= $_SESSION['user']
?>
<title>Notifications | <?php echo SITE_NAME ?></title>
<div class="" style="padding:10px;background:#fff">
	<span class='pull-right'><a href='?all_is_read'>Mark all as read</a></span>
  <b>Notifications</b>
  <?php
	if(isset($_GET['all_is_read']))
	{
		$sql = "UPDATE notifications SET seen = '1' WHERE participant = '$user'";
		$connection->exec($sql);
	}
  echo "<div align='center' style='background:#fee'><a href='birthdays.php' style='color:#000'><img src='images/people.png' height='40px'> There are ".num_birthdays($user)."
	birthdays Today <small class='text-muted'> (Click to see)</small></a></div>";
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
      $body = "<br />$a a post you also Commented on";
      $extra = "href='view_post.php?view=$r[cid]&type=post'";
    }
    elseif ($r['type']=='created') {
      $icon = "<i style='font-size:1.43em'class='ion-quote pull-right text-muted'></i>";
      $body = "<br />$a Your Post";
      $extra = "href='view_post.php?view=$r[cid]&type=post'";
    }
    elseif ($r['type']=='pic_created') {
      $icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
      $body = "<br />$a on a picture you Uploaded";
      $extra = "href=\"photo_view.php?view=$r[cid]\" class='pointer'";
    }
    elseif ($r['type']=='pic') {
      $icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
      $body = "<br />$a a picture you also commented on";
      $extra = "href=\"photo_view.php?view=$r[cid]\" class='pointer'";
    }
    elseif ($r['type']=='pic_like') {
      $icon = "<i style='font-size:1.43em'class='ion-images pull-right text-muted'></i>";
      $body = "<br />$a a picture you Liked";
      $extra = "href=\"photo_view.php?view=$r[cid]\" class='pointer'";
    }
    elseif ($r['type']=='com_like') {
      $icon = "<i style='font-size:1.43em'class='ion-quote pull-right text-muted'></i>";
      $body = "<br />$a a post you Liked";
      $extra = "href='view_post.php?view=$r[cid]&type=post'";
    }
    elseif ($r['type']=='follow') {
      $icon = "<i style='font-size:1.43em'class='ion-person-stalker pull-right text-muted'></i>";
      $body = "<br />Started Following you";
      $extra = "onclick='mark_notif_read($r[id],\"follow\")' class='pointer'";
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
    echo "<a $extra style='color:#444'><div onclick='this.style.background=\"#ededed\"' style='background:$background;padding:10px;margin:5px;'>
    <img src='$image' style='border-radius:30px' height='40px' width='40px'> ".get_name($r['latest'])."$icon
     $body <br /><small class='text-muted'>".past($r['stamp'])."</small></div></a>";
  }

  if($query->rowCount() == 0)
  {
    echo "<h3 align='center'> No Notifications at the moment </h3> ";
  }
   ?>
</div>
<?php
include 'includes/footer.inc';
 ?>
