<?php
  session_start();
  include 'includes/header.inc';
  $id = sanitize($_GET['view']);
  if(isset($_SESSION['user']))
  {
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
      $name = $_SESSION['user'];
      $comment = addslashes($_POST['comment']);
      $comment = htmlspecialchars($comment);

      if(empty($comment))
      {
        echo "
        <div class='alert alert-danger' style='
        width:300px;z-index:3000;top:60px;position:fixed;left:20%;font-weight:bold;
        box-shadow:0 3px 15px #444;border-radius:0px;background:rgba(200,0,0,0.8);color:#fff;
        '>
        <span class='close' data-dismiss='dialog'>&times;</span>
          Sorry, comment cannot be empty
        </div>
        ";
      }
      else 
      {
        $time = cur_time();
        $sql = "INSERT INTO `news_comments` (`c_id`,`username`,`comment`,`stamp`) VALUES ('$id','$name','$comment','$time')";
        $connection->exec($sql);
        echo "
        <div class='alert alert-danger' style='
        width:300px;z-index:3000;top:60px;position:fixed;left:20%;font-weight:bold;
        box-shadow:0 3px 15px #444;border-radius:0px;background:rgba(0,200,0,0.8);color:#fff;
        '>
        <span class='close' data-dismiss='dialog'>&times;</span>
          Comment has been Posted!
        </div>
        ";
      }
    }
  }
 ?>
    <div class='container'>
      <div class="col-sm-8">

          <?php
            $sql = "SELECT * FROM `news` LEFT JOIN `news_categories` ON news.category = news_categories.id WHERE visible='0' AND news.id='$id' ORDER BY stamp DESC LIMIT 100";
            $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
            foreach($query as $r)
            {
              $pic = verify_blog_pic($r['path']);
              $cat = ($r['name']) ? "<a href='categories.php?cat=$r[category]'>".$r['name']."</a>" : '<a href="categories.php?cat=0">General</a>';
              echo"  <h2>$r[topic]</h2>
              By: ".get_name($r['by'])." <br />Category: $cat <small class='text-muted pull-right'> $r[time] </small><br />&nbsp;
                <div>

                  <center>
                    <img src='$pic' style='max-width:100%'>
                    <h3>$r[topic]</h3>
                  </center><br />
                    <div style='white-space:pre-line'>".$r['news']."</div><hr />

                  <div><small class='text-muted '>$r[time]</small></div>
                </div>
              ";
              for($i=1;$i<6;$i++)
              {
                if(empty($r['further'.$i]))
                {

                }
                else {
                  $key = 'further'.$i;
                  $pic = verify_blog_pic($r[$key]);
                  echo "<br /><center><img src=\"$pic\" style='max-width:100%;margin:5px;'></center>";
                }
              }
            }
           ?>
      <h3>Comments</h3>
      <?php
        if(isset($_SESSION['user']))
        {
       ?>
       <form method="post">
         <textarea name='comment'
         style='width:70%;min-width:250px;border:1px solid #aaa;resize:vertical;height:70px;padding:5px'
          placeholder="What do you think?"></textarea>
       <br /> <button  class="btn btn-danger">Comment</button>
       </form>
       <?php
        }
        else {
          echo "<h4 class='text-muted'> Please Login / Register to post a comment</h4>";
          include('../includes/easy_login.inc');
        }

        $sql = "SELECT news_comments.*,users.firstname,users.lastname,profilepics.profilepic FROM news_comments";
        $sql .= " INNER JOIN users ON users.username = news_comments.username ";
        $sql .= " INNER JOIN profilepics ON news_comments.username = profilepics.username ";
        $sql .= " WHERE news_comments.c_id = '$id' ORDER BY stamp DESC";
        $query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
        foreach($query as $r)
        {
          $image = file_validity('../'.$r['profilepic'].'tumb.jpg');
          echo "
          <div class='comment' style='word-wrap:break-word;'>
            <table width='100%'>
              <tr>
                <td width='44px'>
                  <img src='$image' class='circle' height='40px' width='40px'></td><td align='left'>
                  <a href='../mobile/profile.php?view=$r[username]'>$r[firstname] $r[lastname]</a> <br />
                  <small class='text-muted'>".past($r['stamp'])."</small>
                </td>
              </tr>
              <tr>
                <td></td>
                <td style='word-wrap:break-word;'>
                  <div style='white-space:pre-line'>$r[comment]</div>
                </td>
              </tr>
            </table>
          </div>";
        }

        $user = (isset($_SESSION['user'])) ? $_SESSION['user'] : "Not logged" ;

        increment_news_views($_GET['view'],$user);
        ?>
   </div>
      <?php
        include 'includes/footer.inc';
       ?>
<script type="text/javascript">
    document.getElementsByClassName('close')[0].addEventListener('click',closeAlert)
    function closeAlert()
    {
      document.getElementsByClassName('alert')[0].style.display='None'
    }
</script>
