<?php
  session_start();
  include '../includes/functions.inc';
  include '../includes/constants.inc';
  include '../includes/db.inc';
  include '../includes/resizer.class';


  if(!isset($_SESSION['press']))
  {
    echo "<h3>Sorry You are not logged in</h3>
    <a href='../admin.php'>Login here</a>
    ";
    exit();
  }

  if(isset($_POST['post_news']))
		{
			$newstopic=htmlspecialchars($_POST['topic']);
			$newstopic=addslashes($newstopic);
			$newstopic=trim($newstopic);
			$content=trim($_POST['news_content']);
			$content  = addslashes($content);
			$content  = htmlspecialchars($content);
			$newscaster=$_SESSION['press'];
			$error="";
			$errortext="";
			if($newstopic=="")
				{
					$error=1;
					$errortext='EMPTY TOPIC<br />';
				}
			if($content=="")
				{
					$error=1;
					$errortext=$errortext.'EMPTY CONTENT<br />';
				}
			if($newscaster=="")
				{
					$error=1;
					$errortext=$errortext.'Please Enter Your Name<br />';
				}
			if($error==1)
					{
						echo $errortext;
					}
		if ($error!=1)
			{
				if($_FILES['picture']['type']=='image/jpeg'||$_FILES['picture']['type']=='image/gif'||$_FILES['picture']['type']=='image/png')
					{
						$rand=rand(1000,9999);
						$name="news_pic".time().$rand.'.jpg';
						move_uploaded_file($_FILES['picture']['tmp_name'],'newspic/'.$name);
						$path='newspic/'.$name;
					}

                                $image = new SimpleImage(); $image->load($path);
                                $image->resizeToWidth(500);
                                $image->save($path);

                                $image = new SimpleImage(); $image->load($path);
                                $image->resize(200,200);
                                $image->save($path.'tumb.jpg');

				// handle picture 1
				if($_FILES['pic1']['type']=='image/jpeg'||$_FILES['pic1']['type']=='image/gif'||$_FILES['pic1']['type']=='image/png')
					{
						$rand=rand(1000,9999);
						$name="news_pic1".time().$rand.'.jpg';
						move_uploaded_file($_FILES['pic1']['tmp_name'],'newspic/'.$name);
						$path1='newspic/'.$name;
					}

					// resize futher 1 image
                    $image = new SimpleImage(); $image->load($path1);
                    $image->resizeToWidth(400);
                    $image->save($path1);

				// handle picture 2
				if($_FILES['pic2']['type']=='image/jpeg'||$_FILES['pic2']['type']=='image/gif'||$_FILES['pic2']['type']=='image/png')
					{
						$rand=rand(1000,9999);
						$name="news_pic2".time().$rand.'.jpg';
						move_uploaded_file($_FILES['pic2']['tmp_name'],'newspic/'.$name);
						$path2='newspic/'.$name;
					}

					// resize futher 2 image
                    $image = new SimpleImage(); $image->load($path2);
                    $image->resizeToWidth(400);
                    $image->save($path2);

				// handle picture 3
				if($_FILES['pic3']['type']=='image/jpeg'||$_FILES['pic3']['type']=='image/gif'||$_FILES['pic3']['type']=='image/png')
					{
						$rand=rand(1000,9999);
						$name="news_pic3".time().$rand.'.jpg';
						move_uploaded_file($_FILES['pic3']['tmp_name'],'newspic/'.$name);
						$path3='newspic/'.$name;
					}

					// resize futher 3 image
                    $image = new SimpleImage(); $image->load($path3);
                    $image->resizeToWidth(400);
                    $image->save($path3);

				// handle picture 4
				if($_FILES['pic4']['type']=='image/jpeg'||$_FILES['pic4']['type']=='image/gif'||$_FILES['pic4']['type']=='image/png')
					{
						$rand=rand(1000,9999);
						$name="news_pic4".time().$rand.'.jpg';
						move_uploaded_file($_FILES['pic4']['tmp_name'],'newspic/'.$name);
						$path4='newspic/'.$name;
					}

					// resize futher 4 image
                    $image = new SimpleImage(); $image->load($path4);
                    $image->resizeToWidth(400);
                    $image->save($path4);

				// handle picture 5
				if($_FILES['pic5']['type']=='image/jpeg'||$_FILES['pic5']['type']=='image/gif'||$_FILES['pic5']['type']=='image/png')
					{
						$rand=rand(1000,9999);
						$name="news_pic5".time().$rand.'.jpg';
						move_uploaded_file($_FILES['pic5']['tmp_name'],'newspic/'.$name);
						$path5='newspic/'.$name;
					}

					// resize futher 5 image
                    $image = new SimpleImage(); $image->load($path5);
                    $image->resizeToWidth(400);
                    $image->save($path5);

				date_default_timezone_set("Africa/Lagos");
        $time = date('D, d M Y h:i:s a');
				$sql = "INSERT INTO `news` (`topic`,`news`,`path`,`by`,`time`,`further1`,`further2`,
          `further3`,`further4`,`further5`,`category`) VALUES
          ('$newstopic','$content','$path','$newscaster','$time','$path1','$path2','$path3','$path4','$path5','$_POST[categories]')";
        $connection->exec($sql);
				$_POST['newstopic']=""; $_POST['poster']=""; $_POST['content']="";
			}
		echo "News has been added";
		}

  ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale , max-scale=1.0">
    <link rel="stylesheet" href="css/w3.css" media="screen" title="no title" charset="utf-8">
    <script src='js/script.js'></script>
    <style>
      div
      {
        overflow:hidden;
      }
    </style>
  </head>
  <body>
    <div class="w3-topnav w3-red">
      <center>
        <a href='index.php'>Back to blog</a> 
        <a href='../'>Back to social</a>
        <a href="view_hidden.php">View Hidden News</a> 
        <a href="../logout.php">Logout</a>
      </center>
    </div>
    <div class='w3-continer'>
      <div class="w3-half  w3-card-8">
        
        <form method='post' enctype='multipart/form-data' class='w3-container w3-padding-16 w3-border-right'>
          <center><h4>Add News</h4></center>
          <input onkeyup="topic_thing(this)" type="text" class='w3-input' name='topic' placeholder="News Topic">
          <br />
          <textarea name='news_content' onkeyup="news_thing(this)" class='w3-input' placeholder="News Content"></textarea>
          <br />
          Featured Picture:<br />
          <input name='picture' accept='image/*' onchange="picture_thing(event,'image')" type="file" class="w3-input"><br />
          Categories:<br /> <select name='categories' class='w3-select'><?php $cats = return_news_categories();
          $count=0;
          foreach($cats as $r)
          {
            echo "<option value='$count'>$r</option>";
            $count++;
          }
           ?></select><br /><h4>Further Pictures</h4>
           Picture 1:
          <input name='pic1' type="file" class="w3-input"><br />
          Pic 2:
          <input name='pic5' type="file" class="w3-input"><br />
          Pic 3:
          <input name='pic2' type="file" class="w3-input"><br />
          Pic 4:
          <input name='pic3' type="file" class="w3-input"><br />
          Pic 5:
          <input name='pic4' type="file" class="w3-input"><br />

          <button name='post_news' class='w3-btn w3-red'>Submit</button>
        </form>
      </div>
      <div class="w3-half w3-container w3-padding-16 ">
        <h4 align='center'> News Preview</h4>
        <img  src="" id='image' style="max-width:80%">
        <h3 id='news_topic'></h3>
        <div style='white-space:pre-line' id='news_body'></div>
      </div>
    </div>

  </body>
</html>
