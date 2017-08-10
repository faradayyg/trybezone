<?php
	//database connection with parameters coming from constants.inc in the same folder
	$server = HOST;
	$db = DATABASE;
	$db_password = DB_PASSWORD;
	$db_username = DB_USERNAME;
	try
	{
		$connection = new PDO("mysql:host=$server;dbname=$db",$db_username,$db_password);
		$connection-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
		echo DATABASE_ERROR_MSG;
		if(TESTING == 1)
		{
			echo $e->getMessage();
		}
	}
?>