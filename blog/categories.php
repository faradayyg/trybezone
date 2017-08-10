<?php
  session_start();
  include 'includes/header.inc';
  $id = sanitize($_GET['view']);
?>
<div class="container wrapper">
  <div class='col-sm-8'>
  <?php
    $sql = "SELECT * FROM `news` WHERE `category`='$id'";
    $query = $connection->query($sql);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($query as $r)
    {
      $pic = verify_blog_pic($r['path']);
      echo"
          <div class='col-sm-4'>
            <h4>$r[topic]</h4>
            By: ".get_name($r['by'])."<br /><small class='text-muted'> $r[time] </small><br />&nbsp;
              <center>
                <div style='max-height:150px;overflow:hidden'>
                  <a href='news.php?view=$r[id]' title='Views and comments'>
                    <img src='$pic' style='max-width:100%'>
                  </a>
                </div>
              </center><br />
              ".htmlspecialchars(substr($r['news'],0,50))."...<hr />
            <b><a href='news.php?view=$r[id]' title='Views and comments'>Read More >> </a></b>
            <div><small class='text-muted '>$r[time]</small></div>
          </div>
        ";
    }
   ?>
 </div>
<?php include 'includes/footer.inc';
