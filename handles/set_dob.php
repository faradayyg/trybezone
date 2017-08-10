<?php
  session_start();
  include('../includes/constants.inc');
  include('../includes/db.inc');
  include('../includes/functions.inc');
  $year = sanitize($_POST['year']);
  $month = sanitize($_POST['month']);
  $day = sanitize($_POST['day']);
  $sql = "UPDATE `users` SET `year`='$year',`month`='$month',`day`='$day' WHERE `username`='$_SESSION[user]'";
  $connection->exec($sql); $connection->commit();
?>
