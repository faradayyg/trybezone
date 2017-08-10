<?php
	ob_start();
	session_start();
	include('includes/functions.inc');
	include('includes/header.inc');
	include('includes/constants.inc');
	include('includes/db.inc');
	if(!isset($_GET['noredirect']) AND !isset($_GET['msg']))
	{
		echo "<script>
			var width = window.screen.width;
			if(width<600)
				{
				 window.location='mobile/login.php';
				}
		</script>";
	user('includes/login_required.inc');

	keep_online($_SESSION['user']);

	}

?>
<style>
	.no-hover:active,.no-hover:focus
	{
		border:none;
		outline:none;
	}
</style>
<title>Homepage</title>
<body  onmouseover='show_upload_btn()' ontouchstart='show_upload_btn()'>
	<?php include('includes/primary_nav.inc'); ?>
	<?php include('includes/secondary_nav.inc'); ?>
	<div class='container transparent pad-tp' >
		<div class='row'>
			<div class='col-md-2 hidden-xs' style='color:#000;background:#fff;padding:0'>
				<?php
					$img = file_validity(get_ppic($_SESSION['user'],$connection).'tumb.jpg');
					echo "<img src='$img' width='100%' ><br />"
				?>
				<div style="padding:9px">
					<?php require_once('includes/groups.inc'); ?>
					<?php require_once('includes/bd.inc'); ?>
				</div>
			</div>
			<div class='col-md-6' >
				<div class='btn-group'>
					<span class='btn  btn-file' onclick="show('id','home_panel');hide('id','status_add');show('id','photo_upload')" style='color:#fff;max-height:40px;overflow:hidden;background:rgba(0,0,0,0.6)'>
						<i class='ion-android-camera'></i> Upload Photo <br /><form enctype='multipart/form-data' action='' id='upload_form' method='post'>
							<input type='file' onchange="show_upload_btn()" id='img' name='picture' class='file-upload' /> </form></span>
					<button class='btn ' onclick="toggle('id','home_panel')" style='background:rgba(0,0,0,0.7);color:#fff'><span class='glyphicon glyphicon-remove'></span> Hide panel</button>
				</div>
				<div class='panel panel-default' id='home_panel'>

				<!-- div for status update -->
					<div id='status_add' >
						<div class='panel-body'>
							<span class='text-muted'><b>Publish a post</b></span>
							<textarea id='status' class='no-hover no-resize' style='border-radius:5px;width:100%;display:block;padding:10px 0 0 10px;border:1px solid #ddd;outline:none' placeholder='Start typing a post ...'></textarea><img src='images/switcher-active.png' style='position:absolute;margin-left:15px;margin-top:-1px;width:10px'>
						</div>
						<div class='panel-footer'>
							<button class='btn btn-danger' onclick="publish_post(document.getElementById('status').value,user,token)">Post</button>
						</div>
					</div>
					<!-- close status update -->
					<!-- div for photo uploads -->
					<div id='photo_upload' style='display:none'>
						<div class='panel-body'>
							<span class='text-muted' id='photoHelper'><b>Photo upload</b></span>
						</div>
						<div class='panel-footer'>
							<button class='btn btn-danger' onclick='upload_pic("upload_form","img")' id='upload_pic_btn'><span class='glyphicon glyphicon-upload'></span> Upload</button>
						</div>
					</div>
					<!-- end div for photo upload -->
					<input type='hidden' id='hidden_helper'>
				</div>
				<!--close entire update area div -->
				<div style='background:#fefefe'>
				<div id='loading' style='display:none' align='center'><img src='images/32.gif'> Loading</div>
				<div id='feeds' >
					<center>
						<img src="images/32.gif" />
					</center>
		  	</div>

				<div id='more_posts'></div>

			</div>
				<a href='javascript:'class='btn form-control btn-danger' id='gghhtt'
				onclick='load_more_feeds("more_posts",5,this);this.innerHTML="<i class=\"glyphicon glyphicon-refresh\"></i> loading. . . "'><i class='glyphicon glyphicon-download-alt'></i> Load More</a><br />
			</div><!-- end of the main div -->
			<div class='col-md-3'>
				<div class='panel panel-default' id='static_right' >
					<div class='panel-body' style='overflow:hidden;'>
						<?php require_once('includes/call_to_action.php'); ?>
						<?php require_once('includes/online.inc'); ?>
					</div>
				</div>
			</div>
			<!--div class='col-sm-2' style='position:fixed;float:right;right:0px;margin-left:20px;width:23%'>
				<div class='panel panel-default' id='static_right' >
					<div class='panel-body'><b>Sponsored</b></div>
				</div>
			</div-->
		</div>
	</div>
</body>
<?php include('includes/footer.inc'); ob_end_flush();?>
<script>
	function upload_pic(div,img){
		$('#loading').show(400)
		tt = document.getElementById(div)
		$.ajax({
		url:"handles/upload.php",
		type:"POST",
		data: new FormData(tt),
		contentType:false,
		cache:false,
		processData:false,
		success:  function(data)
		{
			$('#'+img).val('');
			$('#dummy').html(data);
			load_feeds();
		},
		complete:  function()
		{
			$('#loading').hide(400);
		},
		error:  function()
		{
			$('#loading').hide(400);
			show_alert('Unable to upload file, network is unavailable at the moment, try again later')
		},

		})
	}


	var feed_number_init = 20
	function load_more_feeds(div,how_many,btn)
	{
		start = feed_number_init
		feed_number_init +=how_many
		$.post('handles/load_more_posts.php',{username:user,token:token,start:start,stop:how_many},function(data){
			$('#'+div).append(data)
		}).success(function(data){
			btn.innerHTML = "<i class=\"glyphicon glyphicon-download-alt\"></i> Load More Feeds"
			if(data=='')
			{
				btn.style.textAlign = 'center'
				btn.style.color = '#aaa'
				btn.setAttribute('class','col-xs-12 text-muted')
				btn.innerHTML = 'Hey, Seems this is the end of all your feeds :( <br /> <a href="#feeds">Scroll To Top</a>'
			}
		}).error(function(){
			feed_number_init -=how_many
			show_alert('Unable to fetch more news feeds')
			btn.innerHTML = "<i class=\"glyphicon glyphicon-download-alt\"></i> Load More Feeds"
		})
	}
	if(window.innerWidth>750)
	{
		load_on_scroll('gghhtt');
	}


	function load_feeds()
	{
		$.post('handles/load_feeds.php',{username:user,token:token},function(data){
			$('#feeds').html(data)
		}).error(function(){
			load_feeds();
		})
	}

	function show_upload_btn()
	{
		if(document.getElementById("img").value.length < 1)
		{
			hide('id','upload_pic_btn');
			document.getElementById("photoHelper").innerHTML = "<i class='glyphicon glyphicon-camera'></i> No picture Selected"
		}
		else
		{
			show('id','upload_pic_btn');
			//document.getElementById('upload_pic_btn').style.background = '#0f0'
			document.getElementById('upload_pic_btn').style.width = '100%'
			document.getElementById("photoHelper").innerHTML = "<i class='glyphicon glyphicon-picture'></i> Click on the Upload button below to upload picture"
		}
	}
	setInterval(function(){
		load_feeds()},60000);

		$('document').ready(function(){
			load_feeds();
		})
	</script>
