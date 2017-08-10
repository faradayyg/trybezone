<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$id = sanitize($_POST['id']);
	$sql = "SELECT * FROM uploads WHERE upid='$id'";
	$query = $connection->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	foreach($query as $r)
	{
		$pict = $r['picture'];
		$pict = advanced_file_validity('../'.$pict,$pict);
		$time = $r['stamp'];
		echo "<script>
			document.getElementById('p_pic').innerHTML=\"<div class='well' id='p_p_hover' style='position:absolute;width:98%;background:rgba(0,0,0,0.4);\"+
			\"display:none;border-color:transparent;color:#fff'>\"+
			\"</div><div id='p_p_hover2' style='position:absolute;top:30%;width:99%'><span class='pull-left pointer invisible_span' \"+
			\"onclick='go_previous_photo($id)'><img src='images/arrow-previous.png' height='50px' /></span><span class='pull-right pointer invisible_span'\"
			+\"  onclick='go_next_photo($id)'><img src='images/arrow-next.png' height='50px' /></span></div><table height='90%' style='max-width:1000px'>\"+
			\"<tr><td v-align='center' align='center' style='width:70%'><img src='$pict' style='max-width:750px;max-height:600px;'></td></tr></table>\"
			$('#p_previews').html('')
			$('#p_p_hover').html(\"$r[content] <br /> <small class='text-muted'>$r[stamp]</small>\")
			$('#p_pic').mouseover(function(){".'$'."('#p_p_hover').fadeIn(200)}).mouseleave(function(){
				".'$'."('#p_p_hover').fadeOut(200)
			})
		</script>";
		$user = $r['user'];
		$isuser = (strtolower($user)==strtolower($_SESSION['user'])) ? 'true' : 'false' ;
	}
	if($query->rowCount() == 0)
	{
		echo "
			<script>
			document.getElementById('p_pic').innerHTML=\"  <h3>Sorry, it seems the image you are trying to view does not exist </h3> \"
		</script>
		";
	}

	//get other uploads from the same user, populate the p_preview div
	$sql2 = "SELECT * FROM uploads WHERE user='$user' ORDER BY RAND() LIMIT 12";
	$u_c = $connection->query($sql2);
	$u_c->setFetchMode(PDO::FETCH_ASSOC);
	foreach($u_c as $usr)
	{
		$pic = $usr['picture'].'tumb.jpg';
		$pic = advanced_file_validity('../'.$pic,$pic);
		echo "
			<script>
				document.getElementById('p_previews').innerHTML += \" <div  style='border:2px solid #ddd;margin:2px;padding:0px;overflow:hidden;height:51px;background:url($pic);background-repeat:no-repeat;background-size:100%;80%;' class='pointer' onclick='load_pic($usr[upid]);'>\"+
				\" <div style='position:relative;top:30px;padding:1px;height:18px;background:rgba(0,0,0,0.4);color:#fff;font-size:0.8em'>$usr[stamp]</div><a class='pointer ttip' title='Uploaded $usr[stamp]'></a></div> \"
			</script>
		";
	}

	$sql = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='pic' AND `participant` = '$_SESSION[user]'  ";
			$sql2 = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='pic_created' AND `participant` = '$_SESSION[user]'  ";
			$sql3 = "UPDATE `notifications` SET `seen` = 1 WHERE `cid`='$id' AND `type`='pic_like' AND `participant` = '$_SESSION[user]'  ";
	$connection->exec($sql);
	$connection->exec($sql2);
	$connection->exec($sql3);
	$sql = "SELECT * FROM users INNER JOIN profilepics ON users.username = profilepics.username WHERE users.username = '$user'";
	foreach ($connection->query($sql) as $r) {
		$img = advanced_file_validity('../'.$r['profilepic'].'tumb.jpg',$r['profilepic'].'tumb.jpg');
		$delete = ($isuser=='true') ? "<a id=\"delete_pic\" class=\"pointer\">Delete</a> |": '' ;
		$fname = $r['firstname']." ".$r['lastname'];
		echo "
			<script>
			document.getElementById('pic_heading').innerHTML='<img src=\'$img\' height=\'50px\' width=\'50px\' style=\'border-radius:30px\'><a href=\'profile.php?view=$user\'>'+
				'<b>$fname</b> </a><br /> $time <br/> $delete &nbsp;&nbsp;<a class=\'pointer\' id=\'make_ppic\'><i class=\'ion-images\'></i> Make profile picture</a> '
			</script>
		";
	}
	log_action($_SESSION['user'],'viewing pictures',$connection);

	keep_online($_SESSION['user']);
?>

<script type="text/javascript">

	$('#make_ppic').click(function(){
		make_ppic('<?php echo $id ?>','<?php echo $pict ?>')
	})
	$('#delete_pic').click(function(){
		delete_pic('<?php echo $id?>','Are you sure you want to delete this picture?, deleted pictures cannot be recovered')
	})
</script>
