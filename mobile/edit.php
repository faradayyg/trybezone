<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';

	global $errors;
	$errors = array();
	$count = 0;
	function check_n_confirm($arg)
	{
		$msg = '';
		if(empty(sanitize($arg)))
		{
			$msg = " empty";
		}

		return $msg;
	}

	if($_SERVER["REQUEST_METHOD"] == 'POST')
	{
		$relationship_check = check_n_confirm($_POST['relationship']);
		$fname_check = check_n_confirm($_POST['fname']);
		$department_check = check_n_confirm($_POST['department']);
		$level_check = check_n_confirm($_POST['level']);
		$email_check = sanitize($_POST['email'],"email");
		$lname_check = check_n_confirm($_POST['lname']);

		$relationship = sanitize($_POST['relationship']);
		$fname = sanitize($_POST['fname']);
		$department = sanitize($_POST['department']);
		$level = sanitize($_POST['level']);
		$email = sanitize($_POST['email']);
		$lname = sanitize($_POST['lname']);

		if($relationship_check == " empty" || $fname_check == " empty" || $lname_check == " empty" || $department_check == " empty" ||$level_check == " empty"  )
		{
			echo "<div class='alert alert-warning'>One or more fields are missing, please try again</div>";
		}

		else
		{
			$sql = "UPDATE `users` SET `firstname` = '$fname', `lastname` = '$lname', ".
			"`department` = '$department', `level` = '$level', `email` = '$email' WHERE `username` = '$_SESSION[user]'";
			$connection->exec($sql);

			$sql = "SELECT * FROM `relationship` WHERE `username` = '$_SESSION[user]'";

			if($connection->query($sql)->rowCount() == 0)
			{
				$sql2 = "INSERT INTO relationship (username,relationship) VALUES ('$_SESSION[user]','$relationship')";
				$connection->exec($sql2);
			}
			else
			{		
				$sql2 = "UPDATE `relationship` SET `relationship` = '$relationship' WHERE `username` = '$_SESSION[user]'";
				$connection->exec($sql2);
			}

			echo "<div class='alert alert-success'>Your profile information has been updated</div> <a href='profile.php'> Go back to profile </a>";
		}


	}
	function confirm_stuff($stuff)
	{
		if(!isset($stuff))
		{
			$stuff = "";
		}

		return $stuff;
	}

	$userInfo = new userInfo();
	$userInfo -> user = $_SESSION['user'];
	$userInfo->connection = $connection;

	$firstname = $userInfo->get_first_name();
	$firstname = confirm_stuff($firstname);

	$lastname = $userInfo->get_last_name();
	$lastname = confirm_stuff($lastname);

	$department = $userInfo->get_department_id();
	$department = confirm_stuff($department);


	$level = $userInfo->get_level();

	$relationship = $userInfo->get_relationship();

	$sql = "SELECT phone,email FROM users WHERE username = '$_SESSION[user]'";
	$query = $connection->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	foreach ($query as $r) 
	{
		$email = confirm_stuff($r['email']);
		$phone = confirm_stuff($r['phone']);
	}
?>

<title>Edit your profile information</title>

<div style="background:#fff;padding:10px">
<form method="post">
	<h3>Edit Profile</h3><br />

	<b>Basic details:</b><br />
	First Name:<br />
	<input type="text" name='fname' class='form-control' placeholder='Enter Your Firstname' value='<?php echo $firstname ?>'><br />
	Last Name: <br />
	<input type="text" name='lname' class='form-control' placeholder='Enter Your Lastname' value='<?php echo $lastname ?>'><br />

	<b>Educational</b><br />
	Department:<br />
	<select class="form-control" name='department'>
		<?php 
			$departments = return_departments();
			foreach ($departments as $key => $value) {
			  $ret = "<option value='$key'>$value</option>";
			  if($key==$department)
			  {
			  	$ret = "<option value='$key' SELECTED>$value</option>";

			  }
			  echo "$ret";
			}
		?>
	</select><br />
	Level: <br />
	<select class='form-control' name='level'>
		<?php
			for ($i=1; $i <7 ; $i++) { 
				$ret = "<option value=$i"."00".">$i"."00"."</option>";
				if($i."00"==$level)
				{
					$ret =  "<option value='$i"."00'"." SELECTED>$i"."00"."</option>";
				}
				echo $ret;
			}
		?>
		<option value="Graduated">Graduated</option>
	</select>

	<h4>Personal</h4>

	Relationship status<br />
	<select name="relationship" class="form-control">
		<option value="<?php echo $relationship?>"><?php echo $relationship; ?></option>
		<?php 
			for ($i=0; $i < count($super_relationships) ; $i++) 
			{  
				echo "<option value='$super_relationships[$i]'>$super_relationships[$i]</option>";
			}
		?>
	</select>

	Phone Number:<br />
	<div style="padding:5px;border:1px solid #aaa;background:#eee;height:35px"><?php echo $phone ?></div> <br />

	Email:<br />
	<input type="email" name="email" class='form-control' value='<?php echo $email ?>'><br />

	<button type="submit" class="btn btn-danger form-control">Edit</button>
</form>
</div>

<?php
	include 'includes/footer.inc';
 ?>
