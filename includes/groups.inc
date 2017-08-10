<center><i class='ion-ios-people text-danger' style='font-size:3em'></i></center>
<?php
	//group activities tab
	$sql = "SELECT * FROM followers WHERE follower = '$_SESSION[user]' AND type = 'group'";
	$numb = $connection->query($sql)->rowCount();
	if($numb==0)
	{
		echo "<center>You dont follow any channels yet.</center>";
		$manage = '';
	}
	else
	{
		echo "<button class='btn btn-default form-control'><i class='ion-plus'></i> My Channels</button><br /><br />";
		echo "<button class='btn btn-default form-control'><i class='ion-plus'></i> Channels I follow</button><br /><br />";
	}
	echo "<a href='channels.php' class='btn btn-default form-control' ><i class='ion-search'></i> Find Channels</a><br /><br />";
	echo "<button class='btn btn-danger form-control' onclick='create_channel()'><i class='ion-plus'></i> Create Channel</button>";
?>
