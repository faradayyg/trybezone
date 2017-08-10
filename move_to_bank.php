<?php
  session_start();
  include('includes/constants.inc');
  include('includes/db.inc');
  include('includes/functions.inc');
  include('includes/header.inc');
  $sql = "SELECT * FROM `users`";
  $query = $connection->query($sql);
  $query->setFetchMode(PDO::FETCH_ASSOC);
  $connection->beginTransaction();
  foreach($query as $r)
  {
    $phone = $r['phone'];
    $length = strlen($phone);
    $phone = substr($phone,$length-10,10);
    $phone = '0'.$phone;
    $sql2 = "INSERT INTO `bank`(`firstname`,`lastname`,`phone`,`sex`) VALUES ('$r[firstname]','$r[lastname]','$phone','$r[sex]')";
    $connection->exec($sql2);
  }
  $connection->commit();
 ?>
