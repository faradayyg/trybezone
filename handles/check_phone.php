<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$number = sanitize($_POST['number']);
	$phone = $number;
	$length = strlen($phone);
	$phone = substr($phone,$length-10,10);
	$phone = '0'.$phone;
	if(strlen($phone) <10)
	{
		echo "<span class='text-danger'> <b>Phone number is not long enough</b><br /></span>";
		exit();
	}
	$sql = "SELECT phone FROM bank WHERE phone = '$phone' ORDER BY id DESC LIMIT 1";
	try
	{
		$query = $connection -> query($sql);
		if($query->rowCount()!=0)
		{
			echo "taken";
		}
		if($query->rowCount()==0)
		{
			echo "free";
		}
	}
	catch(PDOException $e)
	{
		if(TESTING == 1)
		{
			echo $e->getMessage();
		}
		echo $e->getMessage();
	}
?>
