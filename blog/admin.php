<?php
  session_start();
  include '../includes/functions.inc';
  include '../includes/constants.inc';
  include '../includes/db.inc';
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    $sql = "SELECT * FROM `press` WHERE `username`='$username' and `password` = '$password'";
    $query = $connection->query($sql);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    if($query->rowCount()==0)
    {
      echo "<h3 class='w3-disabled'>Sorry, please try again</h3>";
    }
    else
    {
      $_SESSION['press'] = $username;
      header('location:add_news.php');
    }
  }
  echo '<link rel="stylesheet" href="css/w3.css"
  media="screen" title="no title" charset="utf-8">';
  if(!isset($_SESSION['user']))
  {
    echo "<h3 class='text-muted'>Please Log into the site to continue</h3>";
    include '../includes/easy_login.inc';
  }
  else
  {
    echo "
    <div style='width:50%;min-width:300px;margin:auto;border:x solid #777;margin-top:30px' class='w3-container'>
      <form class='w3-container w3-card-8 w3-padding-16' method='post'>
      <center><h4 class=''>Login</h4></center>
        <div class='w3-group'>
          <input name='username' type='text' class='w3-input' required>
          <label style='color:#f33' class='w3-label'>Username</label>
        </div>
          <div class='w3-group'>
            <input name='password' type='password' class='w3-input' required>
            <label style='color:#f33' class='w3-label'>Password</label>
          </div>
          <button class='w3-btn w3-red'>Login</button>
      </form>
    </div>
    ";
  }
 ?>
