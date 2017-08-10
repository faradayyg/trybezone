<?php
	include('include.inc');
	$return = array();
	$user = sanitize($_POST['user']);
	$userInfo = new userInfo();
	$userInfo->user = $user;
	$userInfo->connection = $connection;

	$return['pic'] = get_ppic($user,$connection)."tumb.jpg";
	$return['username'] = $user;
	$return['name'] = $userInfo->get_name();
	$return['department'] = $userInfo->get_department();
	$return['level'] = $userInfo->get_level();
	$return['relationship'] = $userInfo->get_relationship();
	$return['gender'] = $userInfo->get_gender();
	$return['phone'] = $userInfo->get_phone();
	$return['day'] = $userInfo->get_birth_date();
	$return['month'] = $userInfo->get_birth_month();
	echo json_encode($return);
?>