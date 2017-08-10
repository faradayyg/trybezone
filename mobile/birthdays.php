<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
  user('../includes/easy_login.inc');
	include 'includes/header.inc';
	include 'includes/nav.inc';
?>
<div  style='padding:10px;background:#fff'>
  <b>Birthdays</b><br />
  <div style='padding:10px;background:#fee' align='center'><img src='images/people.png' height='30px'>  Number of Birthdays Today:<b> <?php echo num_birthdays($_SESSION['user']); ?></b></div>
  <br />
  <?php
    $day_today = date("d");
    $month_today = date("F");
    $sql = "SELECT users.*,profilepics.profilepic FROM users INNER JOIN profilepics ON users.username = profilepics.username
     WHERE month='$month_today' and day = '$day_today'";
    foreach($connection->query($sql) as $r)
    {
      $img = file_validity('../'.$r['profilepic'].'tumb.jpg');
      $level = $r['level'];
      if($level = '100?')
      {
        $level = 'Not set';
      }
        echo "
        <div class='panel panel-danger'>
          <div class='panel-heading'>
            <a href='profile.php?view=$r[username]' style='color:#A94442'> <b>$r[firstname] $r[lastname] </b></a>
          </div>
          <div class='panel-body'>
          <span class='text-muted'>
            $r[department]<br />
            $level Level <br />
            <a href='profile.php?view=$r[username]'>View Profile</a>
          </span>
            <center><img src='$img' style='max-height:200px'><br /><br />

            <a href='create_message.php?user=$r[username]' class='btn btn-danger'>Wish $r[firstname] a happy birthday</a></center>
          </div>
        </div>
        ";
    }
    if($connection->query($sql)->rowCount()==0)
    {
      echo "<div>Sorry, Seems like there are no members with birthdays today</div>";
    }
   ?>
</div>
<?php
include 'includes/footer.inc';
 ?>
