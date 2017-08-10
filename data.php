<?php
	session_start();
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/functions.inc');
	include('includes/header.inc');
	include('includes/primary_nav.inc');
	include('includes/secondary_nav.inc');

?>
<div class="container">

<div class="col-sm-6">
<?php
	$sql = "SELECT * FROM relationship ORDER BY rand() LIMIT 15";
	foreach($connection->query($sql) as $r)
	{
		// $r['username']."<br />";
	}
	$sql = "SELECT * FROM lastseen  ORDER BY lastseen DESC";
	$count = 0;
	foreach ($connection->query($sql) as $k) {
		$count++;
		$tym = $k['lastseen'];
		echo "<b>$count $k[username],</b>. ".past($tym)."<br />";
	}
//	echo past(11411580336);
?>
</div>

<div class="col-sm-3" style="padding:7px;background:#fff;"><?php include 'includes/call_to_action.php'; ?></div>
<div class="col-sm-3"><div class='panel'><div class="panel-body"><b>Sponsored</b></div></div></div>
</div>

<?php
	require 'includes/footer.inc';
?>
