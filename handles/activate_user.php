<?php
  session_start();
  include('../includes/constants.inc');
  include('../includes/db.inc');
  include('../includes/functions.inc');
  $id = sanitize($_POST['id']);
  $username = sanitize($_POST['username']);
  $password = sanitize($_POST['password']);
  $err = $error = '';
	if(!preg_match("/^[a-z][a-z0-9_]*$/", strtolower($username)))
	{
		$err=1;
		$error .="Sorry, Looks Like Your username either contains spaces or an invalid character<br />";
	}
	if (stripos($username, ' ')>0)
  {
		$error .= "Sorry, There can be <b>NO</b> spaces in your username <br />";
		$err =1;
	}

	if(strlen($username)<3 )
	{
		$error .= "Oops It seems your Username is too short. <br />";
		$err =1;
	}
	if(strlen($password)<3 )
	{
		$error .= "Oops It seems your Password is too short. <br /> ";
		$err =1;
	}

  $sql = "SELECT username FROM login WHERE username = '$username' ";

	$query = $connection -> query($sql);
	if($query->rowCount()!=0)
	{
		$error .= "Sorry ,that username \"$username\" is already taken <br />";
		$err=1;
	}

  if($err!=1)
	{

    //get previous info from bank
    $sql = "SELECT * FROM `bank` WHERE `id`='$id'";
    foreach ($connection->query($sql) as $r)
    {
      $firstname = $r['firstname'];
      $lastname = $r['lastname'];
      $phone = $r['phone'];
      $sex = $r['sex'];

    }
    $alpha = substr($firstname, 0,1);
		//do insertion
		$sql = "INSERT INTO users (`firstname`,`lastname`,`username`,`sex`,`phone`,`level`) VALUES
		('$firstname','$lastname','$username','$sex','$phone','100')";
		$sql2 = "INSERT INTO login (`username`,`password`) VALUES('$username','$password')";
    $sql3 = "INSERT INTO profilepics (`username`,`profilepic`) VALUES('$username','images/cctumb/$alpha.png')";
    $sql4 = "INSERT INTO `subscriptions` (`username`,`subs_to`,`type`) VALUES ('$username','$username','user')";
		try
		{
			mkdir("../uploads/$username");
			$connection-> beginTransaction();
			$connection -> exec($sql);
			$connection -> exec($sql2);
		    $connection -> exec($sql3);
		    $connection -> exec($sql4);
			$connection -> commit();
			$_SESSION['user'] = $username;
			echo "
			<script>window.location='first_time.php'</script>
			<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> Congratulations!!! Your Sign Up was successful <br /> if you are not automatically redirected,
			<a href='first_time.php' title='continue to site'> click here to continue</a></div>";
		}
		catch(PDOException $e)
		{

			if(TESTING == 1)
			{
				echo $e->getMessage();
			}
		}

	}
  else
  {
    echo "<script>show_alert('$error');$('#reg_main_body').slideDown()</script>";
  }
?>
