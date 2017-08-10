<?php
	session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
	$size = 10;
	$page = isset($_GET['page']) ? sanitize($_GET['page']) : 1;
	$start = ($page-1)*$size;
	$stop = $page * $size;
?>
	<div style='background:#fff'>
	  <div style='padding:10px'>
		<b>Messages</b><br />
		<?php
			$sql = "SELECT * FROM `messages` WHERE `sender` = '$_SESSION[user]' OR `receiver` = '$_SESSION[user]' ORDER BY stamp DESC LIMIT $start,$size";
			$query = $connection->query($sql); $query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$bg = '#fff';
				if($r['read']==0 && $r['sender']!=$_SESSION['user'])
				{
					$bg = '#fee';
				}
				$name = $r['sender'];
				if($name == $_SESSION['user'])
				{
					$name = $r['receiver'];
				}
				echo "<a href='conversation.php?id=$r[messageid]'><div style='background:$bg;padding:10px;margin:2px;border:1px solid #aaa;color:#333'>
				<small class='pull-right'>".short_past($r['time'])."</small>
				<b>".get_name($name)."</b> <br ><small class='text-muted'>".substr($r['message'],0,50)."</small></div></a>";
			}
			if($query->rowCount()==10)
			{
				echo "<a href='?page=".($page+1)."' class='btn form-control btn-default'>Show More</a>";
			}

			elseif($query->rowCount()==0)
			{
				echo "<h3>There are No messages at the moment.</h3>";
			}
		?>
	  </div>
	</div>
<?php
	include 'includes/footer.inc';
?>
