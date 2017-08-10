<?php
	include('includes/functions.inc');
	include('includes/header.inc');
	include('includes/constants.inc');
	user('includes/easy_login.inc');
	include('includes/db.inc');
	$form = "<div class='back_big'><div class='well' style='width:50%;min-width:300px;margin:auto;margin-top:10%'>".
		"<span class='pull-right close' onclick='hide(\"id\",\"dummy2\")'>&times;</span> <center>".
		"<i class='ion-settings text-danger' style='font-size:3em'></i></center>".
		"<br />A Channel is a group of people with common interests, ".
		" users can subscribe to them by simply following them. <br /> You will be prompted to upload the channel's picture after now".
		"<br /><form action='create_group.php' method='post'>".
		"<input type='text' class='form-control' name='name' placeholder='Name of the channel' REQUIRED><br />".
		"<input type='text' class='form-control' name='info' placeholder='More info anout the channel' REQUIRED><br />".
		"<button class='btn btn-danger form-control'>Create</button></form></div></div>";
	if($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo $form;
		exit();
	}
?>

<title>Creating Group</title>

<?php
	include 'includes/primary_nav.inc';
	$info = sanitize($_POST['info']);
	$name = sanitize($_POST['name']);
	if(!isset($_POST['info'])||!isset($_POST['name']))
	{
		echo $form;
		exit();
	}
?>

<div class='container'>
	<div class='col-sm-6'>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php 
				if(empty($info)||empty($name))
				{
					echo "<script>show_alert('All fields are required')</script>";
					echo "<h2>Some fields are missing, all fields are required</h2>".$form;
					exit();
				}
					$sql = "INSERT INTO channels (channel,creator,info) VALUES('$name','$_SESSION[user]','$info')";
					$connection->exec($sql);
					$lastid = $connection->lastInsertId();
					$channelname =  'channel'.$lastid;
					$sql = "UPDATE channels SET username = '$channelname' WHERE id = '$lastid'";
					$connection->exec($sql);
					$sql = "INSERT INTO `followers` (`username`,`follower`,`type`) VALUES
						('$channelname','$_SESSION[user]','group')";
					$connection->exec($sql);	
				?>
					

				Upload the channel's photo
				<form action="channels.php" method="post" enctype="multipart/form-data">
					<input type='file' name='picture' style="appearance:none;-moz-appearance:none;
					border:1px solid #aaa;height:35px;background:#eee;display:inline;">
					<button class="btn btn-danger">Upload</button>
					<input name='lastid' type='hidden' value='<?php echo $lastid?>'>
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-3" style="background:#fff;padding:15px">
		<?php include 'includes/online.inc'; ?>
		<?php include 'includes/call_to_action.php'; ?>
	</div>
	<div class="col-sm-3"></div>
</div>


<?php
	include 'includes/footer.inc';
?>