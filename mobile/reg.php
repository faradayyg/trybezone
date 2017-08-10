<?php
  session_start();
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	?>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1.0" />
		<meta name="description" content="" />
		<meta name="author" content="Friday Godswill U" />
		<link rel='stylesheet' href='css/bootstrap.css' />
		<link rel='stylesheet' href='css/styles.css' />
	</head>
	<body style='max-width:600px;margin:auto;background:#888;background-size:100% 100%;'>
		<div style='background:#cb2222;border-bottom:3px solid #aa2222;color:#eee;margin:0px;padding:1px'><h4 align='center'>SignUp</h4></div>
		<div style='background:#fff;padding:15px;margin:0px;border-bottom:4px solid #ddd;'>
			<br />
			<span class='text-muted pull-right'>Already a member? <a  class='text-danger' href='login.php'>Login Here</a></span>

			<?php
			if(!isset($_GET['phone']))
			{?>
		 <form>
			Phone Number<input type='phone' name='phone' class='form-large' placeholder='Your Phone number Here'>
			<!--input type='text' class='form-large' placeholder='Eg Faradman'>
			Phone<input type='text' class='form-large' placeholder='Eg 08138317490'>
			Password<input class='form-large' placeholder='Password: At least 6 characters long '-->
			 <br />&nbsp;<button class='btn btn-danger pull-right form-control'>Continue</button><br />&nbsp;
		 </form>
		 <?php }
     else
     {
       $number = sanitize($_GET['phone']);
     	if(strlen($number) <10)
     	{
     		echo "<span class='text-danger'> <b>Phone number is not long enough</b><br /></span>";
     		exit();
     	}
     	$sql = "SELECT phone FROM bank WHERE phone = '$number' ORDER BY id DESC LIMIT 1";
     	try
     	{
     		$query = $connection -> query($sql);
     		if($query->rowCount()!=0)
     		{
     			$sql2 = "SELECT phone FROM users WHERE phone = '$number'  LIMIT 1";
          $query2 = $connection -> query($sql2);
          if($query2->rowCount()!=0)
          {
            echo "<h2 class='text-muted'>Seems this phone number is
            already active on the site, please login if this is you. <a href='login.php'> Login Here </a></h2>";
          }
          else
          {
            echo "okoriee";
          }
     		}
     		if($query->rowCount()==0)
     		{
     			echo "free";
     		}
     	}
     	catch(PDOException $e)
     	{
     	}
     }
     ?>
		</div>
		<center><span style='color:#bbb'>About  &#8227; Contact &#8227; Privacy </span></center>
	</body>
</html>
