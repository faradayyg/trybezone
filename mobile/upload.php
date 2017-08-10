<?php
  session_start();
  include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
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


?>
<div style='padding:5px;background:#fff'>
  <b>Upload Pictures</b><br />
  <form action="" method="post" enctype="multipart/form-data"><br />
    <input type="file" name="picture"><br />
    <textarea name='comment' placeholder="Say something about the picture"
    style="height:50px;width:300px;border:1px solid #777;resize:none;outline:none"></textarea><br />
  <button class='btn btn-danger'>Upload</button>
  </form>
</div>
<?php
if(strtolower($_SERVER['REQUEST_METHOD'])=='post')
{
  if($_FILES['picture']['type']=="image/jpeg"||$_FILES['picture']['type']=="image/jpg"||
  $_FILES['picture']['type']=="image/png"||$_FILES['picture']['type']=="image/gif")
  {
  	//naming
  	$rand= rand(1000,9999);
  	$name="../uploads/".$_SESSION['user']."/mouaunetwork.com".$rand.time().'.jpg';
  	$insname="uploads/".$_SESSION['user']."/mouaunetwork.com".$rand.time().'.jpg';
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
  		try
  		{
        $comment = sanitize($_POST['comment']);
  			$sql = "INSERT INTO uploads (`picture`,`user`,`content`) VALUES ('$insname','$_SESSION[user]','$comment')";
  			$connection->exec($sql);
  			$last_id = $connection -> lastInsertId();
  			$disp = disp_time();
  			$sql2 = "INSERT INTO feeds(uploadid, type, disp, username) VALUES ('$last_id','picture','$disp','$_SESSION[user]')";
  			$connection->exec($sql2);
  			header('location:index.php');
  		}
  		catch(PDOException $e)
  		{}
  	}
  }
}


  include 'includes/footer.inc';
?>
