<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');

	$count = 0;
	$sql = "SELECT * FROM request";
	$query = $connection ->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	foreach ($query as $key) {
		$sql2 = "SELECT * FROM followers WHERE follower = '$key[username]' AND username = '$key[request]'";
		if($connection->query($sql2)->rowCount() == 0)
		{
			//$key['username'] = htmlspecialchars(addslashes($key['username']));
			$sql3 = "INSERT INTO `followers` (`follower`,`username`,`type`) VALUES('$key[username]','$key[request]','user')";
			$connection->exec($sql3);
		}
		else
		{
			$count +=1;
		}
	}
		echo $count .' duplicate rows not inserted';
?>