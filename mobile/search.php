<?php
  session_start();
  include '../includes/constants.inc';
  include '../includes/functions.inc';
  include '../includes/db.inc';
  include 'includes/header.inc';
  include 'includes/nav.inc';
  echo "<div class='' stye='background:#fff'><div class='row'>";
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
  $term = (isset($_GET['term'])) ? sanitize($_GET['term'])  : '' ;
  $usrs = array();
  $count = 0;
  $sql = "SELECT userid FROM `users` WHERE match(`lastname`) AGAINST ('$term') AND username != '$_SESSION[user]'";
  foreach ($connection->query($sql) as $r) {
    $usrs[$count] = $r['userid'];
    $count++;
  }
  $sql = "SELECT userid FROM `users` WHERE match(`firstname`) AGAINST ('$term') AND username != '$_SESSION[user]'";
  foreach ($connection->query($sql) as $r) {
    $usrs[$count] = $r['userid'];
    $count++;
  }
  $sql = "SELECT userid FROM `users` WHERE match(`username`) AGAINST ('$term') AND username != '$_SESSION[user]'";
  foreach ($connection->query($sql) as $r) {
    $usrs[$count] = $r['userid'];
    $count++;
  }
  $sql = "SELECT * FROM `users` WHERE match(`lastname`) AGAINST ('$term')";

  if($count == 0)
  {
    $lenarry = explode(" ", $term);
    foreach($lenarry as $term)
    {
      if(strlen($term) > 2)
      {
        $sql ="SELECT userid FROM `users` WHERE username LIKE '%$term%' AND username != '$_SESSION[user]'";
        foreach ($connection->query($sql) as $r) {
          $usrs[$count] = $r['userid'];
          $count++;
        }
        $sql ="SELECT userid FROM `users` WHERE firstname LIKE '%$term%' AND username != '$_SESSION[user]'";
        foreach ($connection->query($sql) as $r) {
          $usrs[$count] = $r['userid'];
          $count++;
        }
        $sql ="SELECT userid FROM `users` WHERE lastname LIKE '%$term%' AND username != '$_SESSION[user]'";
        foreach ($connection->query($sql) as $r) {
          $usrs[$count] = $r['userid'];
          $count++;
        }
      }
    }
  }

  foreach ($usrs as $r) {
    $sql = "SELECT users.username,users.firstname,users.lastname,
    users.department,users.level,profilepics.profilepic FROM users INNER JOIN profilepics ON users.username = profilepics.username
    WHERE users.userid = '$r' ORDER BY firstname ";
    foreach ($connection->query($sql) as $e) {
      $chk = compare_users($e['username'],$_SESSION['user']);
      if($chk == 'true')
      {
        $btn = "<button onclick=\"follow('$e[username]',this,'user')\" class='btn btn-default' stye='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-minus-circled'></i> Unfollow</button>";
      }
      else
      {
        $btn = "<button class='btn btn-danger' onclick=\"follow('$e[username]',this,'user')\" stle='padding:3px;font-weight:bold;margin-top:50px;float:right'><i class='ion-ios-personadd'></i> Follow </button>";
      }
      if($_SESSION['user']==$e['username'])
      {
        $btn = '';
      }
      $e['level'] = ($e['level']=='100?') ? 'Not set' : $e['level'] ;
      $img = file_validity('../'.$e['profilepic'].'tumb.jpg');
            echo "<div class='col-sm-6'><div class='panel panel-default'>
              <div style='background:url($img);background-size:100%;height:150px'>

              </div>
              <div class='panel-body' style='padding-top:0px'>
                <b><a href='profile.php?view=".urlencode($e['username'])."'>".ucwords("$e[firstname] $e[lastname]")."</a></b><br />
                <small class='text-muted'>$e[username]</small><br/>
                <span class='text-muted'>$e[department]<br />$e[level]</span><br />
                  $btn
              </div>
            </div></div>
            ";
    }
  }

  if($count == 0)
  {
    echo "<div class='alert alert-warning'> Sorry no result was found for your search, please try again below</div><br />";

    echo "<form><input class='form-control input-lg' type='text' name='term' placeholder='start search here . . .'>
    <button class='btn btn-default input-lg form-control'><i class='ion-search'></i> search</button></form>";
  }
}
echo"</div></div>";

include 'includes/footer.inc';
 ?>
