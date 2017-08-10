<?php
include('include.inc');
$username = sanitize($_POST['username']);
$password = sanitize($_POST['password']);
$response = array();
$error = array();

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

$sql = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";

if($connection->query($sql)->rowCount()==1)
{
	$response = array('status' => OK,'token'=>transform($username),'username'=> $username);

}
else
{

	$sql = "SELECT * FROM login WHERE username = '$username'";
	if($connection->query($sql)->rowCount()>0)
	{
		array_push($error,"The password you entered for user $username is invalid");
	}
	else
	{
		array_push($error,"Username and Password are both incorrect");
	}

	$response = ["status"=>ERR,"error"=>$error];
}

echo (json_encode($response));
?>