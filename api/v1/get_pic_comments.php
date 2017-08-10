<?php
	include 'include.inc';
	$id = sanitize($_POST['picid']);
	$e = '';
	$response = array();
	$comments = array();
	$sql = "SELECT uploadcomments.*,users.username,users.firstname,users.lastname,users.level,users.department,users.sex,profilepics.profilepic
	FROM uploadcomments INNER JOIN users ON uploadcomments.commenter =
	users.username INNER JOIN profilepics ON uploadcomments.commenter =
	profilepics.username WHERE uploadcomments.upid='$id' ORDER BY stamp";
	$query = $connection->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	foreach($query as $r)
	{
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
	if($query->rowCount() == 0)
	{
		$response['status']=ERR;	
	}
	$response['status']=OK;
	$response['comments']=$comments;
	echo json_encode($response);

?>