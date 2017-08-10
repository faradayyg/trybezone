<?php
  session_start();
  include('../../includes/constants.inc');
  include('../../includes/db.inc');
  include('../../includes/functions.inc');
  echo '<meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1.0" />';
  if($_SERVER["REQUEST_METHOD"]=="POST")
  {
    if(isset($_POST['second_continue']))
    {
      $phone = sanitize($_GET['phone']);
      $username = sanitize(strtolower($_POST['username']));
      $password = sanitize($_POST['password']);
      $fname = sanitize(ucwords($_POST['fname']));
      $sex = sanitize($_POST['gender']);
      $error=$err='';
      $names = explode(' ',$fname);
      $lastname = '';
      $len = count($names);
      if($len > 1)
      {
        $firstname = $names[0];
        for ($i=1; $i <$len ; $i++) {
          $lastname .= " ".$names[$i];
        }
      }
      else {
        $firstname = $names[0];
        $lastname = $firstname;
      }
      $lastname = trim($lastname);
      if(!preg_match("/^[a-z][a-z0-9_]*$/", $username))
    	{
    		$err=1;
    		$error .="Username can only contain letters, numbers and Underscores<br />";
    	}
    	if (stripos($username, ' ')>0)
      {
    		$error .= "Sorry, your username cannot contain spaces <br />";
    		$err =1;
    	}

    	if(strlen($username)<3 )
    	{
    		$error .= "Oops It seems your Username is too short. <br />";
    	}
      if(strlen($password)<6)
      {
        $error .="Sorry but your password must be at least 6 characters long<br />";
        $err = 1;
      }

      $sql = "SELECT username FROM login WHERE username = '$username' ";

    	$query = $connection -> query($sql);
    	if($query->rowCount()!=0)
    	{
    		$error .= "Sorry ,that username \"$username\" is already taken <br />";
    		$err=1;
    	}

      if(strlen($fname)<5)
      {
        $error .="Please enter your full name<br />";
        $err = 1;
      }
      if($err == 1)
      {
        echo "<div style='width:50%;min-width:300px;max-width:600px;margin:auto;background:#f77;padding:10px'>$error</div>";
      }
      else
      {
        $alpha = substr($fname, 0,1).".png";
    		//do insertion
    		$sql = "INSERT INTO users (`firstname`,`lastname`,`username`,`sex`,`phone`,`level`) VALUES
    		('$firstname','$lastname','$username','$sex','$phone','100')";
    		$sql2 = "INSERT INTO login (`username`,`password`) VALUES('$username','$password')";
        $sql3 = "INSERT INTO profilepics (`username`,`profilepic`) VALUES('$username','images/cctumb/$alpha')";
        $sql4 = "INSERT INTO `subscriptions` (`username`,`subs_to`,`type`) VALUES ('$username','$username','user')";
    		try
    		{
    			mkdir("../../uploads/$username");
    			$connection-> beginTransaction();
    			$connection -> exec($sql);
    			$connection -> exec($sql2);
          $connection -> exec($sql3);
          $connection -> exec($sql4);
    			$connection -> commit();
    			$_SESSION['user'] = $username;
    			echo "
    			<script>window.location='../first_time.php'</script>
    			<div class='alert alert-success'>
            <span class='glyphicon glyphicon-ok'></span> 
            Congratulations!!! Your Sign Up was successful <br /> if you are not automatically redirected,
    			  <a href='../first_time.php' title='continue to site'> click here to continue</a>
          </div>";
    		}
    		catch(PDOException $e)
    		{

    			if(TESTING == 1)
    			{
    				echo $e->getMessage();
    			}
    		}
      }
    }
    if(isset($_POST['first_continue']))
    {
      $phone = sanitize($_GET['phone']);
      $username = sanitize(strtolower($_POST['username']));
      $password = sanitize($_POST['password']);
      $err = $error = '';
    	if(!preg_match("/^[a-z][a-z0-9_]*$/", $username))
    	{
    		$err=1;
    		$error .="Username can only contain letters, numbers and Underscores<br />";
    	}
    	if (stripos($username, ' ')>0)
      {
    		$error .= "Sorry, your username cannot contain spaces <br />";
    		$err =1;
    	}

    	if(strlen($username)<3 )
    	{
    		$error .= "Oops It seems your Username is too short. <br />";
    	}
      if(strlen($password)<6)
      {
        $error .="Sorry but your password must be at least 6 characters long<br />";
        $err = 1;
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
        $sql = "SELECT * FROM `bank` WHERE `phone`='$phone'";
        foreach ($connection->query($sql) as $r)
        {
          $firstname = $r['firstname'];
          $lastname = $r['lastname'];
          $phone = $r['phone'];
          $sex = $r['sex'];

        }
        $alpha = substr($firstname, 0,1).".png";
    		//do insertion
    		$sql = "INSERT INTO users (`firstname`,`lastname`,`username`,`sex`,`phone`,`level`) VALUES
    		('$firstname','$lastname','$username','$sex','$phone','100')";
    		$sql2 = "INSERT INTO login (`username`,`password`) VALUES('$username','$password')";
        $sql3 = "INSERT INTO profilepics (`username`,`profilepic`) VALUES('$username','images/cctumb/$alpha')";
        $sql4 = "INSERT INTO `subscriptions` (`username`,`subs_to`,`type`) VALUES ('$username','$username','user')";
    		try
    		{
    			mkdir("../../uploads/$username");
    			$connection-> beginTransaction();
    			$connection -> exec($sql);
    			$connection -> exec($sql2);
          $connection -> exec($sql3);
          $connection -> exec($sql4);
    			$connection->commit();
    			$_SESSION['user'] = $username;
    			echo "
    			<script>window.location='../first_time.php'</script>
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
        echo "<div style='width:50%;min-width:300px;max-width:600px;margin:auto;background:#f77;padding:10px'>$error</div>";
      }
    }
  }
?>
<link rel="stylesheet" href='w3.css' />
<style>
  *{
  font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
  }
  body
  {
    margin:auto;
    background:#ddd;
  }
</style>
<div class='w3-card-8' style="max-width:600px;background:#fff;padding:10px;margin:auto">
<?php
  if(isset($_GET['phone']))
  {
    $phone = sanitize($_GET['phone']);
    $phone = '0'.substr($phone,strlen($phone)-10,10);
    $sql = "SELECT * FROM `users` WHERE `phone` = '$phone' LIMIT 1";
    $query = $connection->query($sql);
    if($query->rowCount()==0)
    {
      //user does not already exist but is in bank
      $sql2 = "SELECT * FROM `bank` WHERE `phone` = '$phone' LIMIT 1";
      $query2 = $connection->query($sql2);
      if($query2->rowCount() > 0)
      {
        foreach ($query2 as $key)
        {
          echo "<h2 align='center'>Welcome $key[firstname] $key[lastname],</h2>
          <center>Your account has already been created, activate it by
          selecting a username and a new password below </center><br />
          <div align='center'>
            <h4>Choose a New Username and Password Below</h4><br />
            <form method='post' style='min-width:200px;width:60%;'>
              <label class='w3-left'>Choose Username</label><br />
              <input name='username' type='text' name='new_username' placeholder='Username'
                style='height:38px;outline:none;width:100%;margin-bottom:3px'/><br />
                <label class='w3-left'>Choose a Password</label><br />
              <input name='password' type='text' name='new_password' placeholder='Choose a password'
                style='height:38px;outline:none;width:100%;margin-bottom:3px'/><br />
              <button name='first_continue' class='w3-btn w3-red' style='width:100%;'>Continue</button>
            </form>
          </div>
          ";
        }
        exit();
      }

      //user not in bank also
      echo "<h4 align='center' class='text-red'>Please continue by filling in the details below, <small>this will only take a second</small></h4>";
      echo "<br />

      <form method='post' style='min-width:200px;width:60%;margin:auto'>
        <label>Full Name</label><br />
        <input name='fname' type='text' name='new_username' placeholder='Full Name eg: John Friday'
          style='height:38px;outline:none;width:100%;margin-bottom:3px'/><br />
        <label class='w3-left'>Choose a Username</label><br />
        <input name='username' type='text' name='new_username' placeholder='Choose a Username'
          style='height:38px;outline:none;width:100%;margin-bottom:3px'/><br />
          <label class='w3-left'>Choose a Password</label><br />
        <input name='password' type='text' name='new_password' placeholder='Choose a password'
          style='height:38px;outline:none;width:100%;margin-bottom:3px'/><br />
          <label> I am: <br /></label>
          <input type='radio' name='gender' value='Male'/> <b>Male</b>
          <input type='radio' name='gender' value='Female' checked='True'/> <b>Female</b>
        <button name='second_continue' class='w3-btn w3-red' style='width:100%;'>Continue</button><br />&nbsp;
      </form>
      ";
    }
    else
    {
      //user exists
      foreach($query as $r)
      {
        echo "<div style='background:#fff;padding:20px'><h2>Welcome
        Back $r[firstname] $r[lastname], <small> ($r[username]) <br />Please, enter your password below</small></h2>
        <form action='login.php' method='post'>
        <input type='hidden' name='usr' value='$r[username]'>
        <input type='password' name='pwrd' style='height:40px;width:80%;min-width:200px' placeholder='Your Password here'
        ><button name='login' class='btn w3-btn w3-red'>Login</button>
        </form>
        </div>";
      }
    }
  }
  else
  {
    header('location:../index.php');
  }
?>
</div>
