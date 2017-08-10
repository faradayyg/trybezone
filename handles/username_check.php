<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	if(strlen($username) <3)
	{
		echo "<span class='text-danger'> <b>Username is not long enough</b><br /></span>";
		exit();
	}
	$sql = "SELECT username FROM login WHERE username = '$username' ";
	try
	{
		$query = $connection -> query($sql);
		foreach($query as $r)
		{
			if($query->rowCount()!=0)
			{
				echo "<span class='text-danger'> <b>Sorry that username is taken</b><br /></span>";
			}
		}
		if($query->rowCount()==0)
		{
			echo "<span class='text-success'><span class='glyphicon glyphicon-ok'></span> <b>The Username is free </b><br /></span>";
		}
	}
	catch(PDOException $e)
	{
	
	}
?>