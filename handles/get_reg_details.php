<?php
  include('../includes/constants.inc');
  include('../includes/db.inc');
  include('../includes/functions.inc');
  $number = sanitize($_POST['number']);
  $sql = "SELECT * FROM `users` WHERE `phone` = '$number' LIMIT 1";
  $query = $connection->query($sql);
  $query->setFetchMode(PDO::FETCH_ASSOC);
  if($query->rowCount()>0)
  {
    echo "<h4>Sorry, but this account is already active on ".SITE_NAME." You can retrieve Your password if you have forgotten</h4>";
    exit();
  }
  $sql = "SELECT * FROM `bank` WHERE `phone` = '$number' ORDER BY id DESC LIMIT 1";
  $query = $connection->query($sql);
  $query->setFetchMode(PDO::FETCH_ASSOC);
  foreach($query as $r)
  {
    $sex = ($r['sex']=='M') ? 'Male':'Female';

    echo "<h4 class='text-danger'>Welcome<br /> $r[firstname] $r[lastname],</h4>";
    echo "<h5> If this is you, please select a new Username and Password to secure your account.<br/></h5><br />
    <label>Choose a Username </label>
    <input id='reg_username' placeholder='Choose a Username' type='text' class='form-control'>
    <label>Choose a Password </label>
    <input id='reg_password' placeholder='Choose a new password' type='password' class='form-control'>
    <button class='btn btn-danger' onclick=\"activate_user($r[id],$('#reg_username').val(),$('#reg_password').val())\">Continue</button>";
  }
 ?>
