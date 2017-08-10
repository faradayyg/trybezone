<?php
  session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
  include '../includes/functions.inc';
	include '../includes/pic_resize.class';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
  if($_SERVER['REQUEST_METHOD'] == "POST")
  {
      if($_FILES['picture']['type']=="image/jpeg"||$_FILES['picture']['type']=="image/jpg"||
      $_FILES['picture']['type']=="image/png"||$_FILES['picture']['type']=="image/gif")
      {
      	//naming
      	$rand= rand(1000,9999);
      	$name="../uploads/".$_SESSION['user']."/trybezone.com".$rand.time().'.jpg';
      	$insname="uploads/".$_SESSION['user']."/trybezone.com".$rand.time().'.jpg';
      	move_uploaded_file($_FILES['picture']['tmp_name'],$name);
      	//image resize
      	$img = new SimpleImage();
      	$img->load($name);

      	if($_FILES['picture']['size']>1500000)
      		{
      			$img->scale(60);
      		}
      	else if($_FILES['picture']['size']>900000 && $_FILES['picture']['size'] <1500000)
      		{
      			$img->scale(70);
      		}
      	else if($_FILES['picture']['size']<900000)
      		{
      			$img->scale(80);
      		}
      	else
      		{
      			$img->scale(95);
      		}
      	$img->save($name);

      	//thumbnail creation
      	list($w,$h) = getimagesize($name);
      	if($w>$h and $h>200)
      	{
      		$img->resizeToWidth(300);
      	}
      	elseif($h<200)
      	{
      		$img->resizeToHeight(200);
      	}
      	elseif($w<200)
      	{
      		$img->resizeToWidth(200);
      	}
      	else
      	{
      		$img->resizeToHeight(300);
      	}
      	$img->save($name."tumb.jpg");
      	$sc = imagecreatefromjpeg($name."tumb.jpg");

      	list($w,$h) = getimagesize($name."tumb.jpg");

      	$rr=$r2 = 200;
      	if($w<200)
      	{
      		$rr = $w;

      	}
      	elseif($h<200)
      	{
      		$rr = $h;

      	}

      	$imaaa = imagecreatetruecolor($rr,$rr);
      	$srcx = $srcy = 0;
      	imagecopy($imaaa,$sc,0,0,0,0,$w,$h);
      	imagejpeg($imaaa,$name.'tumb.jpg',100);
      	$tumbnail = $insname.'tumb.jpg';
      	//if file exists.. insert DB
      	if(file_exists($name))
      	{
      		try
      		{
      			$sql = "INSERT INTO uploads (`picture`,`user`) VALUES ('$insname','$_SESSION[user]')";
      			$connection->exec($sql);
      			$last_id = $connection -> lastInsertId();
      			$disp = disp_time();
      			$sql2 = "INSERT INTO feeds(uploadid, type, disp, username) VALUES ('$last_id','picture','$disp','$_SESSION[user]')";
      			$connection->exec($sql2);
            $last_id = $connection->lastInsertId();

            $sql = "UPDATE `profilepics` SET `profilepic`='$insname',`upid`='$last_id' WHERE username = '$_SESSION[user]'";
      			$connection->exec($sql);
            wowed_on('pic');
          }
          catch(PDOException $e)
          {
            echo "e->getMessage();";
          }
        }
      }
    
      $year = sanitize($_POST['year']);
      $month = sanitize($_POST['month']);
      $day = sanitize($_POST['day']);
      $sql = "UPDATE `users` SET `year`='$year',`month`='$month',`day`='$day' WHERE `username`='$_SESSION[user]'";
      $connection->exec($sql);
      wowed_on('dob');


      $department = sanitize($_POST['department']);
      $sql = "UPDATE users SET department = '$department' WHERE username='$_SESSION[user]'";
  		$connection->exec($sql);
  		log_action($_SESSION['user'],'changed department to '.$department,$connection);
      wowed_on('department');

      $email = sanitize($_POST['email']);
      $sql = "UPDATE `users` SET `email`='$email' WHERE `username`='$_SESSION[user]'";
      $connection->exec($sql);
      wowed_on('email');
  }
  $count = 0;
  ?>
  <title>Welcome to <?php echo SITE_NAME ?> | getting started guide</title>

     <form enctype="multipart/form-data" name="pic" id='pic_form' method="post" action=''>
<div style='background:#fff;padding:5px'>

  <h3>Welcome to the Trybe!!</h3>
  Hello <?php echo $_SESSION['user']." and welcome to ".SITE_NAME.".com" ?> we are happy to have you.
  <br />Below are a few things to get you started...<br />
  <?php
  $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='pic'";
  if($connection->query($sql)->rowCount()==0)
  { ?>

  <div class='panel panel-default' id='ft_pic_div'>
    <div class="panel-body">

      <h4>Upload a Profile Picture</h4><br />
        <table >
          <tr>
            <td>
              <input
              style='border:1px solid #999;outline:none;background:#eee;padding:5px'
              type="file" id='ft_pic' name='picture'>
              </td>
            <td>
            </td>
            <td>&nbsp; &nbsp;</td>
          </tr>
        </table>
    </div>
  </div>
  <?php }
  else{
    $count++;
  }
  ?>
  <?php
  $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='dob'";
  if($connection->query($sql)->rowCount()==0)
  { ?>
  <div class="panel panel-default" id='ft_dob_div'><div class="panel-body">
  <h4>Date Of Birth:<br /><small> This will not be displayed or shared  with others</small></h4><br />
    <table width='100%'>
      <tr>
        <td>
          <select type='select' name='day' class='special_input' placeholder='Day'>
            <?php
              for($i=1;$i<32;$i++)
              {
                echo "<option value='$i'>$i</option>";
              }
            ?>
          </select>
        </td>
        <td>
          <select name='month' class='special_input'>
            <option value='January'>January</option>
            <option value='Febuary'>Febuary</option>
            <option value='March'>March</option>
            <option value='April'>April</option>
            <option value='May'>May</option>
            <option value='June'>June</option>
            <option value='July'>July</option>
            <option value='August'>August</option>
            <option value='September'>September</option>
            <option value='October'>October</option>
            <option value='November'>November</option>
            <option value='December'>December</option>
          </select>
        </td>
        <td>
          <select name='year' class='special_input'>
            <?php
              for($i=1980;$i<2015;$i++)
              {
                echo "<option value='$i'>$i</option>";
              }
            ?>
          </select>
        </td>
        <td>
        </td>
      </tr>
    </table>
  </div>
  </div>
  <?php }
  else{
    $count++;
  }?>
  <?php
  $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='department'";
  if($connection->query($sql)->rowCount()==0)
  { ?>
    <div id='ft_dept_div' class="panel panel-default"><div class="panel-body">
    <h4>Select Your Department</h4>
    <table>
      <tr>
        <td>
          <select name='department' class="special_input">
          <?php
            $departments = return_departments();
            foreach ($departments as $key => $value) {
              echo "<option value='$key'>$value</option>";
            }
           ?>
         </select>
       </td>
       <td>
       </td>
     </tr>
   </table></div>
 </div>

 <?php }
 else{
   $count++;
 }
 $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='email'";
  if($connection->query($sql)->rowCount()==0)
  { ?>
<div id='ft_dept_div' class="panel panel-default"><div class="panel-body">
    <h4>Enter Email address</h4>

    <input type="email" class="form-control" name="email" placeholder="Your Email Address Here" REQUIRED>
</div></div>
<?php
}

 if($count >= 3)
 {
   echo "<div class='panel panel-default'><div class='panel-body'><h4>It Seems you have completed Your \"Baby Steps\" You can now continue to the
   site and have the full social experience</h4> <a class='btn btn-danger' href='index.php?show_suggestions'>Continue</a></div></div>";
   header('location:index.php');
 }
 else
 {
  echo "<button  name='pic_button' class='btn btn-danger'>Continue </button>";
 }
 ?>

</div>

     </form>
<?php
  include 'includes/footer.inc';
 ?>
