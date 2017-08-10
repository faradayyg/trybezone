<?php
 session_start();
 include('../includes/constants.inc');
 include('../includes/functions.inc');
 include('../includes/db.inc');

 if(!isset($_SESSION['user']))
 {
	echo "<script>
		show_alert('Oops, seems like your session has expired please login again');
		window.location = location;
	</script>";
	exit();
 }
    class SimpleImage {
var $image ;
var $image_type ;
function load ( $filename ) {
$image_info = getimagesize( $filename ) ;
$this-> image_type = $image_info [ 2 ] ;
if ( $this -> image_type == IMAGETYPE_JPEG ) {
$this-> image = imagecreatefromjpeg ( $filename) ;
} elseif( $this -> image_type == IMAGETYPE_GIF ) {
$this-> image = imagecreatefromgif ( $filename) ;
} elseif( $this -> image_type == IMAGETYPE_PNG ) {
$this-> image = imagecreatefrompng( $filename) ;
}
}
function save ( $filename , $image_type = IMAGETYPE_JPEG , $compression = 75 , $permissions = null) {
if ( $image_type == IMAGETYPE_JPEG ) {
imagejpeg( $this-> image, $filename , $compression ) ;
} elseif( $image_type == IMAGETYPE_GIF ) {
imagegif ( $this -> image, $filename ) ;
} elseif( $image_type == IMAGETYPE_PNG ) {
imagepng ( $this -> image, $filename ) ;
}
if ( $permissions != null ) {
chmod( $filename , $permissions ) ;
}
}
function output ( $image_type = IMAGETYPE_JPEG )
{
if ( $image_type == IMAGETYPE_JPEG ) {
imagejpeg( $this-> image) ;
} elseif( $image_type == IMAGETYPE_GIF ) {
imagegif ( $this -> image) ;
} elseif( $image_type == IMAGETYPE_PNG ) {
imagepng ( $this -> image) ;
}
}
function getWidth () {
return imagesx( $this -> image) ;
}
function getHeight () {
return imagesy ( $this -> image) ;
}
function resizeToHeight ( $height ) {
$ratio = $height / $this -> getHeight() ;
$width = $this -> getWidth() * $ratio ;
$this-> resize ( $width , $height ) ;
}
function resizeToWidth ( $width ) {
$ratio = $width / $this -> getWidth() ;
$height = $this -> getheight () * $ratio ;
$this-> resize ( $width , $height ) ;
}
function scale ( $scale ) {
$width = $this -> getWidth() * $scale / 100;
$height = $this -> getheight () * $scale / 100;
$this-> resize ( $width , $height ) ;
}
function resize ( $width , $height ) {
$new_image = imagecreatetruecolor( $width , $height ) ;
imagecopyresampled( $new_image , $this -> image , 0 , 0 , 0 , 0 , $width , $height , $this-> getWidth () , $this-> getHeight()) ;
$this-> image = $new_image ;
}
}







if($_FILES['picture']['type']=="image/jpeg"||$_FILES['picture']['type']=="image/jpg"||
$_FILES['picture']['type']=="image/png"||$_FILES['picture']['type']=="image/gif")
{
	//naming
	$rand= rand(1000,9999);
	$name="../uploads/".$_SESSION['user']."/trybezone.com".$rand.time().'.jpg';
	$insname="uploads/".$_SESSION['user']."/trybezone.com".$rand.time().'.jpg';
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
	elseif($h<200)
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
		try
		{
			$sql = "INSERT INTO uploads (`picture`,`user`) VALUES ('$insname','$_SESSION[user]')";
			$connection->exec($sql);
			$last_id = $connection -> lastInsertId();
			$disp = disp_time();
			$sql2 = "INSERT INTO feeds(uploadid, type, disp, username) VALUES ('$last_id','picture','$disp','$_SESSION[user]')";
			$connection->exec($sql2);
      $last_id = $connection->lastInsertId();

      $sql = "UPDATE `profilepics` SET `profilepic`='$insname',`upid`='$last_id' WHERE username = '$_SESSION[user]'";
			$connection->exec($sql);
      
			echo "<script>show(`id`,`dummy`);
			document.getElementById('dummy').innerHTML = \"<div style='height:100%;width:100%;position:fixed;top:0px;z-index:2000;background:rgba(0,0,0,0.7);padding-top:2%;overflow:auto;' align='center'>\"+
			\"<div class='well' style='width:40%;min-width:300px;overflow:auto;' box-shadow: 0px 5px 9px #000; align='center'><b class='close' onclick='hide(`id`,`dummy`)'>&times;</b><h3>Successfully uploaded</h3> <img src='$tumbnail' style='max-width:100%;max-height:400px;'>\"+
			\"<hr /><textarea class='form-control' id='saysomething' placeholder='say something about this photo. . . '></textarea><br /><button class='btn btn-danger form-control input-lg' onclick='after_upload_com(document.getElementById(`saysomething`).value,$last_id)'><i class='glyphicon glyphicon-comment'></i> Add Description</button></div></div>\"
			</script>";
		}
		catch(PDOException $e)
		{

    }
	}
}


?>
