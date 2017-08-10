<?php
    session_start();
    include('../includes/constants.inc');
    include('../includes/db.inc');
    include('../includes/functions.inc');
    $phone = sanitize($_POST['phone']);
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    $fname = sanitize($_POST['name']);
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
        mkdir("../uploads/$username");
        $connection-> beginTransaction();
        $connection -> exec($sql);
        $connection -> exec($sql2);
        $connection -> exec($sql3);
        $connection -> exec($sql4);
        $connection->commit();
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
 ?>
