<?php
  $day_today = date("d");
  $month_today = date("F");
  $sql = "SELECT users.*,profilepics.profilepic FROM users INNER JOIN profilepics ON users.username = profilepics.username
   WHERE month='$month_today' and day = '$day_today' and users.username != '$_SESSION[user]'";
   $echo = ($connection->query($sql)->rowCount() ==0) ? '': "Birthdays Today";
   echo "<center><br />$echo </center>";
  foreach($connection->query($sql) as $r)
  {
    $img = file_validity($r['profilepic'].'tumb.jpg');
      echo "
      <div class='panel panel-info'>
          <img src='$img' height='40px' width='40px'>
          <button class='pull-right' onclick=\"init_chat('$r[username]')\"><span class='text-danger ion-chatboxes pull-right' ></span></button>
      <a href='profile.php?view=$r[username]'>$r[firstname]</a>
          </div>
      ";
  }
