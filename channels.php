<?php
 session_start();
 include('includes/constants.inc');
 include('includes/functions.inc');
 include('includes/db.inc');
 user('includes/easy_login.inc');
 include('includes/header.inc');
 include('includes/primary_nav.inc');

 if(!isset($_SESSION['user']))
 {
	echo "<script>
		show_alert('Oops, seems like your session has expired please login again');
		window.location = location;
	</script>";
	exit();
 }
    
include 'includes/resizer.class';

if($_SERVER['REQUEST_METHOD']=='POST')
{
	if($_FILES['picture']['type']=="image/jpeg"||$_FILES['picture']['type']=="image/jpg"||
	$_FILES['picture']['type']=="image/png"||$_FILES['picture']['type']=="image/gif")
	{
		//naming
		$rand= rand(1000,9999);
		$name="channels/".$_SESSION['user']."mouaunetwork.com".$rand.time().'.jpg';
		$insname="channels/".$_SESSION['user']."mouaunetwork.com".$rand.time().'.jpg';
		move_uploaded_file($_FILES['picture']['tmp_name'],$name);
		//image resize
		$img = new SimpleImage();
		$img->load($name);

		if($_FILES['picture']['size']>1500000)
			{
				$img->scale(60);
			}
		else if($_FILES['picture']['size']>900000 && $_FILES['picture']['size'] <1500000)
			{
				$img->scale(70);
			}
		else if($_FILES['picture']['size']<900000)
			{
				$img->scale(80);
			}
		else
			{
				$img->scale(95);
			}
		$img->save($name);

		//thumbnail creation
		list($w,$h) = getimagesize($name);
		if($w>$h and $h>200)
		{
			$img->resizeToWidth(300);
		}
		elseif($h<200)
		{
			$img->resizeToHeight(200);
		}
		elseif($w<200)
		{
			$img->resizeToWidth(200);
		}
		else
		{
			$img->resizeToHeight(300);
		}
		$img->save($name."tumb.jpg");
		$sc = imagecreatefromjpeg($name."tumb.jpg");

		list($w,$h) = getimagesize($name."tumb.jpg");

		$rr=$r2 = 200;
		if($w<200)
		{
			$rr = $w;

		}
		ELSEif($h<200)
		{
			$rr = $h;

		}

		$imaaa = imagecreatetruecolor($rr,$rr);
		$srcx = $srcy = 0;
		imagecopy($imaaa,$sc,0,0,0,0,$w,$h);
		imagejpeg($imaaa,$name.'tumb.jpg',100);
		$tumbnail = $insname.'tumb.jpg';
		//if file exists.. insert DB
		if(file_exists($name))
		{
			$lastid = $_POST['lastid'];

				$sql = "UPDATE `channels` SET `avatar` = '$name' WHERE `id` = '$lastid'";
				$connection->exec($sql);
			echo "<div class='alert'>uploaded</div>";
		}
}
}
?>

<div class='container'>
	<div class='col-sm-6' style='background:#fff'>
		<div style='padding:10px'> </div>

		<input type='text'  style='border:1px solid #aaa;width:80%;padding:10px;height:40px' placeholder='Search via channel Name' />
		<button class='btn btn-default' style='height:40px;'><i class='ion-ios-search'></i> Search</button>

		<b>Available Channels </b>
		<?php
			$sql = "SELECT * FROM channels LIMIT 20";
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
        $msql = "SELECT username FROM followers WHERE username = '$r[username]' AND type='group'";
  			$num_members = $connection->query($msql)->rowCount();
				$follow = '<a class="btn btn-danger" onclick="follow(\''.$r['username'].'\',\'group\',this)">Join</a>';
				if (check_channel_follow($_SESSION['user'],$r['username'])!=0)
				{
					$follow = '<a class="btn btn-default" onclick="follow(\''.$r['username'].'\',\'group\',this)" style="padding:3px">Leave</a>';
				}
				 echo "<div class='panel panel-default'>

					<div class='panel-body'><span class='pull-right'>$follow</span>
					<a href='view_channel.php?channel=$r[username]'>";
					if($r['avatar']==Null)
					{
						echo "<i class='ion-ios-people' style='font-size:2em'></i> ";
					}
					else
					{
						echo"<img src='$r[avatar]tumb.jpg' height='40px' style='border-radius:20px'> ";
					}
					
				echo ucwords($r['channel'])."</a>
					<span class='text-muted'>(Members: $num_members)</span> </div>
				 </div>";
			}
		?>

	</div>
	<div class='col-sm-3'>
		<?php require_once('includes/call_to_action.php'); ?>
		<?php require_once('includes/online.inc'); ?>
	</div>
	<div class='col-sm-3'>

	</div>
</div>



<?php
	include 'includes/footer.inc';
?>
