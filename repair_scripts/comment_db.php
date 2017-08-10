<?php 
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	
	$sql = "SELECT * FROM comments INNER JOIN commenttext ON comments.commentid = commenttext.commentid";
	
	foreach($connection->query($sql) as $r)
	{
		$r['comment'] = sanitize($r['comment']);
		$sql2 = "INSERT INTO coms (`id`,`upid`,`username`,`comment`,`stamp`,`disp`) VALUES ('$r[commentid]','$r[uploadid]','$r[commenter]','$r[comment]','$r[stamp]','$r[stamp]')";
		$connection -> exec($sql2);
		echo "<pre>"; print_R($r);echo"</pre>";
	}
?>
	