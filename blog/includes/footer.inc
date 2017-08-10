<div class="col-sm-2" style="border-left:1px solid #ddd">
    <h3>Categories</h3>
    <ul class='nav nav-stacked'>
    <?php
      $cats = return_news_categories();
      $count =0;
      foreach ($cats as $r) {
        echo "<li><a href='categories.php?view=$count'>$r</a></li>";
        $count++;
      }
    ?>
  </ul>
</div>
<div class='col-sm-2 account_info'>
  <?php
    if(isset($_SESSION['user']))
    {
      $user = $_SESSION['user'];
      $name = get_name($user);
      $ppic = get_ppic($user,$connection);
      echo " <img src='../$ppic".'tumb.jpg'."' style='border-radius:50%;height:100px' /><div> $name</div>";
      echo "<div> Messages: 0</div>";
      echo "<div> Notifications: 0</div>";
      echo "&nbsp;&nbsp;<a href='../' class='btn btn-danger no-round'><i class='glyphicon glyphicon-user'></i> Back to Social</a> ";
    }
  ?>
</div>
</div>
<div class='row my_footer'>
  <div class="col-sm-4">
    &copy; Trybezone.com
  </div>
</div>
</body>

</html>
