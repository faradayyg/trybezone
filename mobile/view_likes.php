<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
	if(!isset($_GET['view']))
	{
		echo "<h4>Sorry, seems like this post does not exist</h4>";include 'includes/footer.inc';exit();
	}

	echo "<div style='background:#fff;padding:15px;'><h4>People who like this</h4>";
	$id = sanitize($_GET['view']);
	$type = sanitize($_GET['type']);
	$sql = "SELECT likes.*,users.firstname,users.lastname,profilepics.profilepic FROM likes ";
	$sql .= " INNER JOIN users ON users.username = likes.username";
	$sql .= " INNER JOIN profilepics ON profilepics.username = likes.username ";
	$sql .= "WHERE likes.type LIKE '%$type%' and likes.upid='$id'";
	$query = $connection->query($sql);
	foreach($query as $r)
	{
		$img = file_validity('../'.$r['profilepic'].'tumb.jpg');
		echo "<div style='border-bottom:1px solid #aaa; padding'><img src='$img' height='30px' width='30px'><a href='profile.php?view=$r[username]'> $r[firstname] $r[lastname] </a><span class='pull-right text-muted'>$r[disp]</span></div>";
	}

?>

</div>

<?php
	include 'includes/footer.inc';
?>
