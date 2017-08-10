<?php
  session_start();
  include '../includes/constants.inc';
  include '../includes/functions.inc';
  include '../includes/db.inc';
  include 'includes/header.inc';
  include 'includes/nav.inc';
	$username = sanitize($_GET['user']);
  if(isset($_GET['view']))
  {
    if($_GET['view']=='following')
    {
      echo"<h3>People who ".ucwords(get_name($username))." Follows <br /> <br />
      <small><a  href='profile.php?view=$username'> << Back to Profile </a></small> &nbsp; &nbsp; <a href='follow.php?user=$username&view=followers'><small class='btn btn-default'>View Followers</small></a></h3>";
      echo"<title>People Who ".ucwords(get_name($username))." Follows</title>";
      $sql = "SELECT followers.*,users.firstname,users.level,users.lastname,users.sex,users.department,profilepics.profilepic,users.username FROM followers ";
      $sql .="INNER JOIN users ON users.username = followers.username";
      $sql .= " INNER JOIN profilepics ON profilepics.username=users.username ";
      $sql .= " WHERE followers.follower='$username' ORDER BY users.firstname LIMIT 0,30";
      $query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
      foreach ($query as $e)
      {
        $chk = compare_users($e['username'],$_SESSION['user']);

        if($chk == 'true')
        {
          $btn = "<button onclick=\"follow('$e[username]','user',this)\" class='btn btn-default' style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-minus-circled'></i> Unfollow</button>";
        }
        else
        {
          $btn = "<button class='btn btn-warning' onclick=\"follow('$e[username]','user',this)\" style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-ios-personadd'></i> Follow </button>";
        }
        if($_SESSION['user']==$_GET['user'])
        {
          $btn = '';
        }
        if ($_SESSION['user']==$e['username']) {
          $btn = "Me";
        }
        $e['level'] = ($e['level']=='100?') ? 'Not set' : $e['level'] ;
        $img = file_validity($e['profilepic'].'tumb.jpg');
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
        if($query->rowCount()==0)
        {
          echo "<div class='alert alert-info'>Not Following anyone yet</div>";
        }
      echo "<div id='f_append'></div>";
      if($query->rowCount()>29)
      {
    ?>
    <a href='javascript:'class='btn form-control btn-info' id='gghhtt' onclick='load_more_feeds("more_posts",10,this,"following");this.innerHTML="<i class=\"glyphicon glyphicon-refresh\"></i> loading. . . "'><i class='glyphicon glyphicon-download-alt'></i> Load More</a><br />
    <?php
  }
    }
    elseif($_GET['view']=='followers')
    {
      echo"<h3>People following ".ucwords(get_name($username))."<br /> <br />
      <small><a  href='profile.php?view=$username'> << Back to Profile </a></small> &nbsp; &nbsp; <a href='follow.php?user=$username&view=following'><small class='btn btn-default'>View Following</small></a></h3>";
      echo"<title>People following ".ucwords(get_name($username))."</title>";
      $sql = "SELECT followers.*,users.firstname,users.level,users.lastname,users.sex,users.department,profilepics.profilepic,users.username";
      $sql .=" FROM followers INNER JOIN users ON users.username = followers.follower";
      $sql .= " INNER JOIN profilepics ON profilepics.username=users.username ";
      $sql .= " WHERE followers.username='$username' ORDER BY users.firstname LIMIT 30";
      $query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
      foreach ($query as $e)
      {
        $chk = compare_users($e['username'],$_SESSION['user']);

        if($chk == 'true')
        {
          $btn = "<button onclick=\"follow('$e[username]','user',this)\" class='btn btn-default' style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-minus-circled'></i> Unfollow</button>";
        }
        else
        {
          $btn = "<button class='btn btn-warning' onclick=\"follow('$e[username]','user',this)\" style='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-ios-personadd'></i> Follow </button>";
        }
        if($_SESSION['user']==$_GET['user'])
        {
          $btn = '';
        }
        if ($_SESSION['user']==$e['username']) {
          $btn = "Me";
        }
        $e['level'] = ($e['level']=='100?') ? 'Not set' : $e['level'] ;
        $img = file_validity($e['profilepic'].'tumb.jpg');
          echo "<div class='col-sm-6'><div class='panel panel-default'>
            <div style='background:url($img);background-size:100%;height:150px'>
              $btn
            </div>
            <div class='panel-body' style='padding-top:0px'>
              <b><a href='profile.php?view=".urlencode($e['username'])."'>".ucwords("$e[firstname] $e[lastname]")."</a></b><br />
              <small class='text-muted'>$e[username]</small><br/>
              <span class='text-muted'>$e[department]<br />$e[level]</span>
            </div>
          </div></div>";
      }echo "<div id='f_append'></div>";
      if($query->rowCount()==0)
        {
          echo "<div class='alert alert-info'>Not being followed by anyone yet</div>";
        }
      echo "<div id='f_append'></div>";
      if($query->rowCount()>29)
      {
      ?>
      <a href='javascript:'class='btn form-control btn-info' id='gghhtt' onclick='load_more_feeds("more_posts",10,this,"following");this.innerHTML="<i class=\"glyphicon glyphicon-refresh\"></i> loading. . . "'><i class='glyphicon glyphicon-download-alt'></i> Load More</a><br />
      <?php
    }}
    else
    {
      echo "hiah ph";
    }
  }
?>
