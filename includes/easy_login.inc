<?php
	ob_start();
	if(!isset($_SESSION))
	{
		session_start();
	}
	//handling done here
	include('db.inc');
?>
<style>
	input[type="text"], input[type="password"]
	{
		width:100%;
		border:1px solid #ccc;
		height: 35px;
		padding:5px;
	}
</style>
<head>
	
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1.0" />
		<meta name="description" content="Fuelling social interaction between Nigerian Schools" />
		<meta name="author" content="Friday Godswill U" />
</head>
	<div>
	<div class=' container'>
		<div style='width:50%;min-width:300px;margin:auto;background:#fff;padding:20px;box-shadow:0 0px 15px rgba(0,0,0,0.5);line-height:40px'>
		<div class='alert alert-danger container' id='hhhuu' style="margin:auto;border:1px solid #f00;padding:10px; width:100%">
			<span onclick="document.getElementById('hhhuu').style.display = 'none' " class='close' style='float:right;font-weight:bold;font-size:1.4em;cursor:pointer'>&times;</span>
			Sorry, but you have to be logged in to view this content
		</div>
			<form action='' method='post'>
				<b>Username </b><br />
				<input type='text' name='username' placeholder='Enter your Username here' REQUIRED><br />
				<b>Password </b><br />
				<input type='password' name='password' class='input' placeholder='Enter your Password Here' REQUIRED><hr class='hr_small' style='color:transparent;border:transparent'>
				<button style="background:#a33;border:1px solid #eee; padding:15px;color:#fff;font-weight:bold;cursor:pointer">Login</button>
			</form>
		</div>


<?php
	if($_SERVER["REQUEST_METHOD"]=='POST')
	{

			$username = sanitize($_POST['username']);
			$password = sanitize($_POST['password']);
			if(!ctype_alpha($username) && strlen($username)>10)
			{
				$phone = $username;
				$length = strlen($phone);
				$phone = substr($phone,$length-10,10);
				$phone = '0'.$phone;
				$sql = "SELECT `phone`,`username` FROM `users` WHERE `phone` = '$phone' LIMIT 1";
				$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
				foreach ($query as $r)
				{
					$username = $r['username'];
				}
			}
		$sql = "SELECT username,password,adminstatus,status FROM login WHERE username = '$username' AND password = '$password'";
		try
		{
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			IF($query->rowCount()==1)
			{
				//do the login here
				foreach($query as $r)
				{
					$status = $r['status'];
					$admin = $r['adminstatus'];
				}
				if($status == 1)
				{
					echo "<div class='alert alert-info login'>Sorry your Account has been suspended for some reason,  please <a href='contact.php'>report this as an issue</a></div>";
					exit();
				}
				else
				{
					$_SESSION['user']=$username;
					header("location:$_SERVER[REQUEST_URI]");
				}

			}
			elseif($query->rowCount()>1)
			{
				//big time errors
				echo "<div class='alert alert-danger login'>A fatal error occured, it seems there is an error with your registration, please <a href='contact.php'>report this as an issue</a></div>";
			}
			else
			{
				$sql = "SELECT username FROM login WHERE username ='$username'";
				try
				{
					$query = $connection->query($sql);
					$query->setFetchMode(PDO::FETCH_ASSOC);
					if($query->rowCount()!=0)
					{
						echo "<div class='alert alert-danger login'>Sorry, Looks like The password You entered for $username is incorrect</div>";
					}
					else
					{
						echo "<div class='alert alert-warning login'>
								Sorry, No user by the username \"$username\" exists. <a href='#' data-toggle='modal' data-target='#signUpModal'> Do you want to register? </a>
							  </div>";
					}
				}
				catch(PDOException $e)
				{
					echo DATABASE_ERROR_MSG;
					if(TESTING == 1)
					{
						echo $e->getMessage();
					}
				}
			}
		}
		catch(PDOException $e)
		{
			echo DATABASE_ERROR_MSG;
			if(TESTING == 1)
			{
				echo $e->getMessage();
			}
		}
	}
?>

</div>
<div style='padding:10px;margin:10px'></div>
<!-- load scripts up in here -->
<script src='js/jquery.js' type='text/javascript'></script>
<script src='js/bootstrap.min.js' type='text/javascript'></script>
<script src='js/alertify.min.js' type='text/javascript'></script>
<script src='js/myscript.js' type='text/javascript'></script>
<script src='js/autogrow_plugin.js' type='text/javascript'></script>
<script>
	$('#post_modal').autoGrow();

</script>
	<div id='dummy'></div>
</div>