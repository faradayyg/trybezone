<?php
  $logged = $_SESSION['user'];
 ?>
<div style='background:#ddd;padding:10px'>
<table>
  <tr>
    <td>
      <a href='profile.php?view=<?php echo $logged; ?>'><img src='<?php echo file_validity('../'.get_ppic($logged,$connection).'tumb.jpg') ?>' height='45px' width='45px' style='padding:3px;background:#fff;'></a>&nbsp;&nbsp;&nbsp;
    </td>
    <td>
      <b><a href='profile.php?view=<?php echo $logged; ?>'><?php echo get_name($logged); ?></a></b> <br />
      <a href='profile.php?view=<?php echo $logged; ?>'>Profile</a>  | <a href='photos.php?view=<?php echo $logged; ?>'> Pictures</a>
    </td>
  </tr>
</table>
<br/>
<ul class='nav nav-pills'>
  <b><li><a href='index.php'>Home</a></li>
  <li><a href='messages.php'>Messages</a></li>
  <li><a href='notif.php'>Notifications</a></li>
  <li><a href='birthdays.php'>Birthdays</a></li>
  <li><a href='online.php'>Online <b><?php
  $onln = (online_number($_SESSION['user']) ==0) ? '':"( ".online_number($_SESSION['user'])." )";
  echo $onln;
  ?></b></a></li></b>
</ul>
</div>
