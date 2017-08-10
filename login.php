<?php
	session_start();
	if(isset($_SESSION['user']))
	{
		header("location:index.php");
	}
	if(!isset($_GET['noredirect']) AND !isset($_GET['msg']))
	{
		echo "<script>
			var width = window.screen.width;
			if(width<600)
				{
				 window.location='mobile/login.php';
				}
		</script>";
	}
	include('includes/constants.inc');
	include('includes/functions.inc');
	include('includes/header.inc');
?>
<title>Login | trybezone</title>

<body style='background:url(images/bg.jpg);background-size:100% 100%;background-repeat:no-repeat;padding-top:5%;min-width:300px;'>
	<div align='center'>
		<div id='loading' style='display:none;'>
			<div class='well' style='position:absolute;width:30%;min-width:300px;background:#eee;box-shadow:0 5px 15px #000;border-radius:0px;'>
			  <img src='images/32.gif'> Logging in please wait...
			</div>
		</div>
		<img src='images/logo.png' style='max-height:200px'/>
	</div>
	<h1 align='center' style='color:#fff;font-family:courier new;text-shadow:0 0 5px #000;font-size:40px;font-weight:bold '>
		<?php echo SITE_NAME; ?>
	</h1>
	<div style='width:40%;min-width:300px;padding:20px;align:center;background:rgba(55,55,55,0.3);margin:auto;'>
	  <form action='' method='post'>
		<table width='100%' style='color:#fff'>
			<tr>
				<td>
					<label for='username'>Username:</label>
					<input type='text' id='username' name='username' class='form-control'/>
				</td>
				<td> &nbsp;</td>
				<td>
					<label for='password'>Password:</label>
					<input type='password' id='password' name='password' class='form-control'/>
				</td>
				<td> &nbsp;</td>
				<td> <label for='login'>&nbsp;</label>
					<input type='submit' value='Login' id='login' class='form-control btn btn-danger' />
				</td>
			</tr>
		</table>
	  </form>
		<table width='100%'>
			<tr>
				<td>
					<a href='javascript:' class='pointer' style='color:#eee' data-toggle='modal' data-target='#signUpModal'>Not yet a user?</a>
				</td>
				<td>
					<a class='pointer' style='color:#eee'>Forgot password?</a>
				</td>
			</tr>
		</table>
	</div>


	<!--login error messages and stuff -->

<?php
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		include('includes/db.inc');
		$username = sanitize($_POST['username']);
		$password = sanitize($_POST['password']);
		echo "
			<style>
				.login
				{
					width:40%;min-width:300px;padding:20px;margin:auto;
				}
			</style>
			<br />
				";
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
					echo"<script>window.location='index.php'</script>";
				}

			}
			elseif($query->rowCount()>1)
			{
				//tell em to fuck off
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
</body>
<?php include('includes/footer.inc') ?>
<script>
$('#login').click(function(e){
	e.preventDefault();
	login(document.getElementById('username').value,document.getElementById('password').value)
})

</script>
