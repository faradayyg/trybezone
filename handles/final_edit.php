<?php
	session_start();
  include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
  $username = $_SESSION['user'];
  $text = sanitize($_POST['text']);
  $id = sanitize($_POST['id']);
	$type = sanitize($_POST['type']);

  if($type=='status')
  {
    $sql = "SELECT * FROM `posts` WHERE `id`='$id' and `username`='$username'";
    if($connection->query($sql)->rowCount()!=0)
    {
      $sql = "UPDATE `posts` SET `content` = '$text' WHERE `id`='$id'";
      $connection->exec($sql);
      echo "success";
    }
    else
    {
      echo "You do not own the Post You are trying to edit ";
    }
  }
  elseif ($type=='picture')
  {
    $sql = "SELECT * FROM `uploads` WHERE `upid` = '$id'  and `user`='$username'";
    if($connection->query($sql)->rowCount()!=0)
    {
      $sql = "UPDATE `uploads` SET `content` = '$text' WHERE `upid`='$id'";
      $connection->exec($sql);
      echo "success";
    }
    else
    {
      echo "You do not own the Post You are trying to edit ";
    }
  }
  ?>
