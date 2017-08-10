<?php
include('include.inc');
$id = sanitize($_POST['id']);
$e = '';
$response = array();
$comments = array();
$sql = "SELECT coms.*,users.firstname,users.lastname,users.sex,users.level,users.department,profilepics.profilepic  
FROM coms INNER JOIN users ON users.username = coms.username INNER JOIN profilepics ON profilepics.username
= users.username  WHERE coms.upid='$id' ORDER BY coms.stamp ";

$query = $connection->query($sql);
$query->setFetchMode(PDO::FETCH_ASSOC);

foreach ($query as $r) {
	# code...
	$image = advanced_file_validity("../../".$r['profilepic'],$r['profilepic'].'tumb.jpg');
	$temp = array();
	$temp['name'] = $r['firstname']." ".$r['lastname'];
	$temp['comment'] = $r['comment'];
	$temp['time'] = $r['disp'];
	$temp['ppic'] = $image;
	$temp['username'] = $r['username'];
	array_push($comments, $temp);
	unset($temp);
}

$response['comments'] = $comments;

if($query->rowCount()==0)
{
	$response['status'] = ERR;
}
else
{
	$response['status'] = OK;
}

echo json_encode($response);