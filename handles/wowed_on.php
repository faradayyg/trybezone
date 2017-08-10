<?php
  session_start();
  include('../includes/constants.inc');
  include('../includes/db.inc');
  include('../includes/functions.inc');
  $field = sanitize($_POST['field']);
  $sql = "INSERT INTO `wowed`(`username`,`field`) VALUES ('$_SESSION[user]','$field')";
  $connection->exec($sql);
  log_action($_SESSION['user'],"Wowed on $feild",$connection);
 ?>
