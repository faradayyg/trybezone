<?PHP
	session_start();
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/functions.inc');
	include('includes/header.inc');
	user('includes/easy_login.inc');
	echo"<title>Viewing Post | ".SITE_NAME."</title>";
	include('includes/primary_nav.inc');
	include('includes/secondary_nav.inc');
?>
<div class='container pad-tp'>
	<div class='col-sm-6'>
		<?php
			if(!isset($_GET['id']))
			{
				echo "<div class='alert alert-warning'>Sorry, The post you are trying to view is not available or Has been deleted</div>";
				exit();
			}
			if($_GET['type']=='post')
			{
				$id = sanitize($_GET['id']);
				$sql = "SELECT posts.*,users.firstname,users.lastname,profilepics.profilepic  FROM posts ";
				$sql .= "INNER JOIN users ON posts.username = users.username ";
				$sql .= " INNER JOIN profilepics ON posts.username = profilepics.username";
				$sql .= " WHERE posts.id = '$id' AND visible = '1'";

				$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
				if($query->rowCount() == 0 || $query->rowCount() > 1)
				{
					echo "<div class='alert alert-warning'>Sorry, The post you are trying to view is not available, does not exist, or Has been deleted</div>";
					exit();
				}
				echo "<div class='panel panel-default'>
						<div class='panel-body'>";
				foreach ($query as $r) {
					$sql = "SELECT * FROM likes WHERE type='status' and upid='$id'";
					$num = $connection->query($sql)->rowCount();
					$pic = file_validity($r['profilepic'].'tumb.jpg');
					echo "<i class='ion-chevron-down close pull-right'></i>
							<table style='margin-bottom:5px;'>
							  <tr>
								<td><img src='$pic' height = '40px' width='40px' style='border-radius:30px;' ></td>
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href='profile.php?view=".urlencode($r['username'])."'>
									<b>$r[firstname] $r[lastname] </b></a><br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<small class='text-muted'>$r[disp]</small>
								</td>
							  </tr>
							</table>
							<div style='padding-left:30px;white-space:pre-line;line-height:25px'>$r[content]
							<span class='text-primary pointer'>Likes $num </span>
							</div>


					";
					$csql = "SELECT coms.*,users.firstname,users.lastname,profilepics.profilepic FROM coms";
					$csql .= " INNER JOIN users ON users.username = coms.username";
					$csql .= " INNER JOIN profilepics ON users.username = profilepics.username";
					$csql .= " WHERE coms.upid = '$id' ORDER BY stamp ASC";
					$q = $connection->query($csql);$q->setFetchMode(PDO::FETCH_ASSOC);
					if($q->rowCount()!=0){echo "<b class='text-primary'>Comments <br /></b>";}

					if(isset($_GET['lim']))
					{
						if($_GET['lim']=='all')
						{
							$return = '';
						}
					}
					else
					{
						$start = 0;
						if($q->rowCount()>3)
						{
							$num = $q->rowCount();
							$start = $num-3;
							echo "<a href='".$_SERVER["REQUEST_URI"]."&lim=all'>View all $start previous Comments</a>";
						}
						$csql .= " LIMIT $start,3";
					}
					$q = $connection->query($csql);$q->setFetchMode(PDO::FETCH_ASSOC);
					foreach ($q as $r) {
						$append = '';
						if($r['username']==$_SESSION['user'])
						{
							$append = "<i class='ion-chevron-down close pull-right'></i>";
						}
						$pic = file_validity($r['profilepic'].'tumb.jpg');
						echo "<div style='background:#fee;padding:10px;line-height:15px;border-bottom:1px solid #ddd'><table style='margin-bottom:5px;'>
							  <tr>
							  	$append
								<td valign='top'><img src='$pic' height = '40px' width='40px' ></td>
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<a href='profile.php?view=".urlencode($r['username'])."'>
									<b>$r[firstname] $r[lastname] </b></a><br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									$r[comment]
								</td>
							  </tr>
							  <tr>
							  	<td></td><td><small class='text-muted'>&nbsp;&nbsp;&nbsp;&nbsp;$r[disp]</small></td>
							  </tr>
							</table></div>";
					}
					echo "<div id='append'></div>";
				}

				echo "<table width='100%'><tr><td width='90%'><textarea class='form-control no-resize' onload='$(this).autoGrow()' id='main_comment' style='border-radius:3px;height:40px' placeholder='say something about this'></textarea>
			</td><td> <button class='btn btn-danger ' onclick=\"comment(document.getElementById('main_comment'),$id,'post',user,token);do_append()\" style='border-radius:3px;outline:none'>Comment</button></td></tr></table>";
			echo "</div>";

			$sql = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='COM' AND `participant` = '$_SESSION[user]'  ";
			$sql2 = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='created' AND `participant` = '$_SESSION[user]'  ";
			$sql3 = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='com_like' AND `participant` = '$_SESSION[user]'  ";
			try {
				$r = $connection->exec($sql);
				$r = $connection->exec($sql2);
				$r = $connection->exec($sql3);
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
			}

			?>

		</div>
	</div>
	<div class='col-sm-3' style='background:#fff;padding:10px'>
		<?php include 'includes/call_to_action.php'; ?>
	</div>
	<div class='col-sm-2' style='position:fixed;float:right;right:20px;margin-left:20px;width:23%'>
		<div class='panel panel-default' id='static_right' >
			<div class='panel-body'><b>Sponsored</b></div>
		</div>
	</div>
</div>
<?php
	include('includes/footer.inc');

?>
<script type="text/javascript">
	function do_append()
	{
		document.getElementById('append').innerHTML +="<div style='background:#fee;padding:10px;line-height:15px;border-bottom:1px solid #ddd'>"+
		"<table style='margin-bottom:5px;'><tr><td valign='top'><img src='images/no_pic.png' height = '40px' width='40px' ></td>"+
								"<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href='profile.php?view='<?php echo $_SESSION['user'] ?>'>"+
									"<b>Me </b></a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+document.getElementById('main_comment').value+
								"</td> </tr><tr><td></td><td><small class='text-muted'>&nbsp;&nbsp;&nbsp;&nbsp;just now</small></td>"+
							 "</tr></table></div>"
		document.getElementById('main_comment').value = ''
	}
</script>
