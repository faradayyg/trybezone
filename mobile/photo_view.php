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
		echo '<div style="max-width:600px;margin:0px auto;background:#fff;padding:10px"><h3>Sorry, seems Picture does not exist</h3></div>';
				include 'includes/footer.inc';
				exit();
	}
	$id = sanitize($_GET['view']);


	?>

	<title>Viewing Picture</title>
	<div class="row" style="max-width:600px;margin:0px auto;background:#fff;padding:10px">
		<?php
		$upd = "UPDATE notifications SET seen = '1' WHERE cid = '$id' and participant =
		'$_SESSION[user]' and type='pic' or type='pic_like'";
		$connection->exec($upd);
			$sql = "SELECT * FROM uploads WHERE upid = '$id' LIMIT 1";
			$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $r) {
				# code...
				$uploader = $r['user'];
				$name = get_name($uploader);
				$pic = file_validity('../'.$r['picture']);
				if(isset($_GET['makeppic']))
				{
					$sql = "UPDATE `profilepics` SET `profilepic`='$r[picture]',`upid`='$id' WHERE username = '$_SESSION[user]'";
					$u = $connection->exec($sql);
					echo "<b>Profile Picture Was changed Succesfully</b><br />";
						log_action($_SESSION['user'],'changed profilepic',$connection);
					if(!$u)
					{
						$sql = "INSERT INTO profilepics (profilepic,upid,username) VALUES('$file','$id','$_SESSION[user]')";
						$connection->exec($sql);
						echo "<script>show_alert('profile picture has been updated')</script>";
					}
				}
				echo "<a href='photos.php?view=$uploader'><< Back to $name's photos</a><div style='padding:20px;background:#000'>
				<center><img src='$pic' style='max-width:100%'><br /><span style='color:#fff'>$r[content]</span></center></div>
				";
			}

			if($query->rowCount()==0)
			{
				echo "<h3>Sorry, seems Picture does not exist</h3></div>";
				include 'includes/footer.inc';
				exit();
			}
			$sql = "SELECT * FROM uploads WHERE upid > '$id' AND user='$uploader' ORDER BY stamp ASC LIMIT 1";
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				echo "<a class='pull-right' href='photo_view.php?view=$r[upid]'>Next >></a>";
			}
			$sql = "SELECT * FROM uploads WHERE upid < '$id' AND user='$uploader' ORDER BY stamp DESC LIMIT 1";
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				echo "<a class='' href='photo_view.php?view=$r[upid]'><< Previous </a>";
			}
		?>
		<center><a href='photo_view.php?view=<?php echo $id?>&makeppic=true'>Set as Profile Picture</a></center>
		<h4 id='comments'>Comments</h4>
		<?php
			if(isset($_GET['page']))
			{
				$sub = $_GET['page']*10;
			}
			else
			{
				$sub = 10;
			}
			$sql = "SELECT * FROM uploadcomments WHERE upid = '$id'";
			$num = $connection->query($sql)->rowCount()-$sub;
			if($num>0)
			{
				$page = isset($_GET['page']) ? $_GET['page']+1 : 2;
				echo "<center><a href='?view=$id&page=$page#comments' ><b>Show older Comments</b></a></center>$num 2";
			}
			elseif($num<0)
			{
				$num = 0;
			}
			$sql = "SELECT uploadcomments.*,users.firstname,users.lastname FROM uploadcomments INNER JOIN users on users.username = uploadcomments.commenter WHERE upid = '$id' ORDER BY stamp ASC LIMIT $num,10";
			$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach ($query as $r) {
				$delete = null;
				if($r['commenter']==$_SESSION['user'])
				{
					$delete = "<small class='text-danger' onclick='h=confirm(\"Sure You want to delete this picture?\")'>(delete)</small>" ;
				}
				$tym = (empty($r['disp'])) ? $r['stamp'] : $r['disp'];
				echo "<div style='padding:5px;background:#ffefff;border-bottom:1px solid #ddd'><b><a href='profile.php?view=$r[commenter]' style='color:#000'>".ucwords(strtolower($r['firstname']." ".$r['lastname']))."</a></b>
				<br /><center>$r[comment]</center><small class='text-muted'>$tym</small>
				$delete
				</div>";
			}

		?>
		<textarea style="width:100%;resize:none;outline:none" id='comment'></textarea><br />
		<button onclick='if($("#comment").val().trim().length<1){alert("your comment is empty")}else{comment($("#comment").val().trim(),<?php echo $id ?>);$("#loading").show();}' class="btn btn-danger">Comment</button>
		<span id='loading' style='display:none'><img src="images/s_l.gif"></span>
	</div>

<?php
	include 'includes/footer.inc'
?>
