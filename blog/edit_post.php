<?php
  session_start();
  include '../includes/functions.inc';
  include '../includes/constants.inc';
  include '../includes/db.inc';
  if(!isset($_SESSION['press']))
  {
  	$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : "unlogged";
  	log_action($user,'tried to edit news without logon',$connection);
  	exit();
  }
 ?>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale , max-scale=1.0">
    <link rel="stylesheet" href="css/w3.css" media="screen" title="no title" charset="utf-8">
    <script src='js/script.js'></script>
  </head>
  <body>
    <div class="w3-topnav w3-red">
      <center>
        <a href='index.php'>Back to blog</a> 
        <a href='add_news.php'>Add News</a> 
        <a href='../'>Back to social</a>
        <a href="view_hidden.php">View Hidden News</a> 
        <a href="../logout.php">Logout</a>
      </center>
    </div>

    <?php
    	if(!isset($_GET['id']))
    	{
    		header('location:index.php');
    	}
    	else
    	{
    		$id = sanitize($_GET['id']);
    		$sql = "SELECT * FROM news WHERE id = '$id'";
    		$query = $connection->query($sql);
    		$query->setFetchMode(PDO::FETCH_ASSOC);
    		if($query->rowCount() == 0)
    		{
    			echo "
    			<center>
    				<h3>Sorry, news You are trying to edit does not exist or has been deleted</h3> 
    				Select one of the options above to continue
    			</center>";
    			exit();
    		}
    		else
    		{
    			if($_SERVER["REQUEST_METHOD"] == "POST")
    			{
    				$topic = sanitize($_POST['news_topic']);
    				$news = sanitize($_POST['news']);
    				if(strlen($topic) < 4 && strlen($news) < 20)
    				{
    					echo "Error Editing news";
    				}
    				else
    				{
    					$sql = "UPDATE `news` SET `topic`='$topic', `news`='$news' WHERE `id` = '$id'";
    					$connection->exec($sql);
    					echo "<h3>News has been updated</h3>";
    				}
    			}
    			$sql = "SELECT * FROM news WHERE id = '$id'";
	    		$query = $connection->query($sql);
	    		$query->setFetchMode(PDO::FETCH_ASSOC);
    			foreach ($query as $r) 
    			{
    				# code...
    				$topic = $r['topic'];
    				$news = $r['news'];
    			}

    		}
    	}
    ?>

    <center><h3>Edit News</h3></center>

    <form action="" method='post' ><center>
    	News Topic:
    	<input type="text" name='news_topic' class='w3-input' style="width:50%;min-width:300px;padding:15px" value="<?php echo $topic ?>">

    	News<br />
    	<textarea name="news" style="width:50%;min-width:300px;padding:15px"><?php echo $news ?></textarea><br />
    	<button class="w3-btn w3-red" style="width:50%;min-width:300px;padding:15px">Edit</button>
    </center>
    </form>
   </body>

  </html>