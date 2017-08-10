<?php 
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	
	$sql = "SELECT * FROM statusuploads INNER JOIN statustext ON statusuploads.uploadid = statustext.uploadid";
	
	foreach($connection->query($sql) as $r)
	{
		$r['content'] = sanitize($r['content']);
		$sql2 = "INSERT INTO posts (`id`,`username`,`content`,`location`,`disp`) VALUES ('$r[uploadid]','$r[updater]','$r[content]','$r[location]','$r[stamp]')";
		$connection -> exec($sql2);
	}
?>
	