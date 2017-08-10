<?php
  include 'includes/header.inc';
 ?>
    <div class='container wrapper'>
      <div class="col-sm-8">
        <h2><?php echo SITE_NAME ?> blog</h2>
          <?php
            $sql = "SELECT * FROM `news` WHERE visible='0' ORDER BY stamp DESC LIMIT 20";
            $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
            foreach($query as $r)
            {
              $pic = verify_blog_pic($r['path']);
              $options = (isset($_SESSION['press'])) ? '<div class="btn-group">
                <button type="button" class="btn btn-info" onclick=\'hide_blog_post('.$r['id'].')\'>Hide</button>
                <a href="edit_post.php?id='.$r["id"].'" class="btn btn-warning">Edit</a>
                <button type="button" class="btn btn-danger" onclick=\'delete_blog_post('.$r['id'].')\'>Delete</button>
              </div>':"";
              echo"
                <div class='col-md-4'>
                	<h4>$r[topic]</h4>
                	By: ".get_name($r['by'])."<br /><small class='text-muted'> $r[time] </small><br />&nbsp;
                  	<center>
	                    <div style='max-height:150px;overflow:hidden'>
		                    <a href='news.php?view=$r[id]' title='Views and comments'>
		                      <img src='$pic' style='max-width:100%'>
		                    </a>
	                    </div>
                  	</center><br />
                    ".htmlspecialchars(substr($r['news'],0,50))."...
                  <b><a href='news.php?view=$r[id]' title='Views and comments'><br />Read More >> </a></b>
                  <div><small class='text-muted '>$r[time]</small></div>$options<hr />
                </div>
              ";
            }
           ?>
      </div>
      <?php
        include 'includes/footer.inc';
       ?>
