<?php
	session_start();
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/functions.inc');
  user('includes/easy_login.inc');
	include('includes/header.inc');
	include('includes/primary_nav.inc');
  if(!isset($_GET['view']))
  {
    header('location:index.php');
  }
  else
  {
    $id = sanitize($_GET['view']);
  }
?>
<div class='container'style="padding-top:5px">
  <div style='background:#fff' class='col-sm-6'>
    <?php
      $sql = "SELECT channel_posts.*,users.firstname,users.lastname,profilepics.profilepic FROM channel_posts";
      $sql .= " INNER JOIN users ON users.username = channel_posts.username ";
      $sql .= " INNER JOIN profilepics ON profilepics.username = channel_posts.username ";
      $sql .= " WHERE id = '$id'";
      $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
      foreach($query as $r)
      {
        $img = file_validity($r['profilepic'].'tumb.jpg');
        $tym = past($r['time']);
        echo "
        <a href='view_channel.php?channel=$r[channel]'><< Go back to channel's home page</a>
          <div class='panel panel-default' style='border:0px'>
            <div class='panel-body'>
            <small class='text-muted pull-right'>$tym</small>
              <img  src='$img' height='40px' width='40px'> <a href='profile.php?view=$r[username]'>$r[firstname] $r[lastname]</a>
              <center>$r[post]</center>
            </div>
          </div>
        ";
      }
      if($query->rowCount()==0)
      {
        echo "<h3 class='text-muted'>Sorry, Seems like the post you are looking for does not exist or has been deleted</h4>";
        require 'includes/footer.inc';
        exit();
      }
      if(strtolower($_SERVER['REQUEST_METHOD'])=='post')
      {
        $comment = sanitize($_POST['comment']);
        if(!empty($comment))
        {
          $today = disp_time();
          $sql = "INSERT INTO cp_comments (`username`,`comment`,`p_id`,`disp`) VALUES ('$_SESSION[user]','$comment','$id','$today')";
          $connection->exec($sql);
        }
        else {
          echo "<span class='text-danger'>Sorry, but you cannot make an empty comment</span><br />";
        }
      }
     ?>

     <b>Comments</b>
     <?php
      $sql = "SELECT cp_comments.*,users.firstname,users.lastname,profilepics.profilepic FROM cp_comments";
      $sql .= " INNER JOIN users on users.username = cp_comments.username";
      $sql .= " INNER JOIN profilepics on profilepics.username = cp_comments.username";
      $sql .= " WHERE p_id = '$id'";
      $query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
      foreach($query as $r)
      {
        //print_r($r);
        $img = file_validity($r['profilepic'].'tumb.jpg');
        echo "
          <div style='background:#fee;border-bottom:1px solid #faa;'>
            <table>
              <tr>
                <td valign='top'>
                  <img src='$img' height='40px'> &nbsp;&nbsp;&nbsp;
                </td>
                <td>
                  <a href='profile.php?view=$r[username]'>$r[firstname] $r[lastname]</a> &nbsp;&nbsp;<div style='word-wrap:break-word'>$r[comment]</div>
                  <small class='text-muted'>$r[disp]</small>
                </td>
              </tr>
            </table>
          </div>
        ";
      }
      ?>
      <form action='' method='post'><br />
        <input type="text" name="comment" style="height:35px;width:70%;border:1px solid #bbb;outline:none;" placeholder="What do you think?"><button class='btn btn-danger'>Comment</button>
      </form>
  </div>
</div>
<?php
include 'includes/footer.inc';
 ?>
