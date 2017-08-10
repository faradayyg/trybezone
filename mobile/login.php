<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1.0" />
		<meta name="description" content="Welcome to Trybezone.com, a platform to meet, share, study and connect." />
		<meta name="author" content="Friday Godswill U" />
		<link rel='stylesheet' href='css/bootstrap.css' />
		<link rel='stylesheet' href='css/styles.css' />
		<link rel='stylesheet' href='handles/w3.css' />
		<link rel='shortcut icon' href='favicon.ico' />
		<title>Welcome | TrybeZone.com</title>
	</head>
	<body style='max-width:600px;margin:auto;background:#888;background-size:100% 100%;'>
		<div style='background:#cb2222;border-bottom:3px solid #aa2222;color:#eee;margin:0px;padding:1px'><h4 align='center'>TrybeZone.com</h4></div>
		<div style='background:#fff;padding:15px;margin:0px;border-bottom:4px solid #ddd;'>
			<div style="line-height:30px;text-align:center">
				<?php
				if(isset($_GET['error']))
				{
					echo "<div class='alert alert-danger'>Login error: $_GET[error]</div>";
				}
			?>
				
			<form method='post' action='handles/login.php' style="padding:15px;" class='w3-card-8'>
			<center><img src='../images/logo_small.png' height='80px'></center><br />
			<div style="text-align:left;"><label>Username / Phone</label></div>
			<input type='text' name='usr' style='background:#fff;border:1px solid #aaa;padding:5px;outline:none;width:100%;height:40px'>
			<div style="text-align:left"><label>Password</label></div>
			<input type='password' name='pwrd' style='background:#fff;border:1px solid #aaa;padding:5px;outline:none;width:100%;height:40px'>
			<span class='text-muted pull-left'>Forgot Password?</span> <br /><button class='btn btn-danger pull-right'>Login</button><br />&nbsp;
			</form>
			<div style='background:#fdd;padding:5px'><b>Not yet a member?</b> Sign up below</div>
			<br />
			<form method='get' action='handles/continue.php'>
			<table width='100%'>
				<tr>
					<td>
					<label>Phone number</label>
			  		<input type='text' name='phone' placeholder='Phone Number' style='background:#fff;border:1px solid #aaa;padding:5px;outline:none;width:100%;height:40px'>
					</td>
					<td align='left' width='50px'>&nbsp;
			  		<button class='btn btn-danger pull-right'>Continue</button>
					</td>
				</tr>
			</table>
			</form>
		</div>
		<center><small style='color:#bbb'>About  &#8227; Contact &#8227; Privacy </small></center>
	</body>
</html>
