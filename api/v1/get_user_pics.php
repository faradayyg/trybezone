<?php
include('include.inc');
$person = sanitize($_POST['person']);
$username = sanitize($_POST['user']);
$token = sanitize($_POST['token']);
$return = [];
$item = [];

if(transform($username)==$token)
{
	$sql = "SELECT * FROM uploads WHERE user = '$person' ORDER BY stamp DESC LIMIT 10";
	$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
	foreach ($query as $key => $value)
	{
		array_push($item, $value);
	}
	$return['status'] = OK;
	$return['data'] = $item;
}
else
{
	$return['status'] = ERR;
	$return['message'] = "There seems to be an error with your login, please logout and login again";
}

echo json_encode($return);
?>