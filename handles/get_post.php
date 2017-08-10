<?php
  session_start();
  include('../includes/constants.inc');
  include('../includes/db.inc');
  include('../includes/functions.inc');
  $user = sanitize($_POST['user']);
  $type = sanitize($_POST['type']);
  $id = sanitize($_POST['id']);
  if($user!=$_SESSION['user'])
  {
    echo "error";
    exit();
  }
  else
  {
    if($type=='status')
    {
      $sql = "SELECT * FROM `posts` WHERE `id`='$id'";
      $query = $connection->query($sql);
      foreach ($query as $r)
      {
        if($r['username'] != $user)
        {
          echo "error";
          log_action($user,'tried to edit post '.$id,$connection);
          exit();
        }
        echo $r['content'];
      }
    }
    elseif ($type=='picture')
    {
      $sql = "SELECT * FROM `uploads` WHERE `upid` = '$id'";
      $query = $connection->query($sql);
      foreach ($query as $r)
      {
        if($r['user'] != $user)
        {
          echo "error";
          log_action($user,'tried to edit post '.$id,$connection);
          exit();
        }
        echo $r['content'];
      }
    }
    elseif ($type=='group')
    {
      echo "group";
    }
  }
 ?>
