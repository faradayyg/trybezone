<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$id = sanitize($_POST['id']);
	$sql = "SELECT user FROM uploads WHERE upid = '$id' ";
	$query = $connection->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);

	foreach ($query as $value) {
		$user = $value['user'];
	}

	$sql = "SELECT upid FROM uploads WHERE user = '$user' AND upid < '$id' ORDER BY stamp DESC LIMIT 1 ";
	$query = $connection->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	if($query->rowCount()==0)
	{
		echo"
			<script>show_alert('no further pictures to view')</script>
		";

	}
	
	elseif ($query->rowCount()>0) {
		foreach ($query as $key) {
			$new_id = $key['upid'];
			echo "
			<script>load_pic($new_id)</script>
			";
		}
	}

?>