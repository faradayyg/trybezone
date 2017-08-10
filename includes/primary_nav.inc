<!-- start navbar: visible -->
	<div class='navbar navbar-red navbar-fixed-top' id='nav' style='margin-bottom:0px;border-radius:0 0 3px 3px;'>
		<div class='container'>
			<button type='button' class='navbar-toggle' data-target='#the_nav' style='color:#fff;background:#400;' data-toggle='collapse'>
				<span class='icon-bar' style='background:#fff'></span>
				<span class='icon-bar' style='background:#fff'></span>
				<span class='icon-bar' style='background:#fff'></span>
			</button>

			<div class='navbar-header hidden-sm'>
				<a id='brand' href='index.php' class='navbar-brand' style='color:#300;font-size:22px;'><img src='images/logo_small.png' height='30px'> <?php echo SITE_NAME ?></a>
			</div>
			<div class='collapse navbar-collapse' id='the_nav'>
				<ul class='nav nav-tabs navbar-nav navbar-right no-border'>

					<li>
						<span style='display: block;margin-top:10px;' class='hidden-sm' >
						<form method="get" action="search.php">
							<input placeholder='Search <?php echo SITE_NAME?>. . .'  name='term'
							style='color:#422;padding:5px;border:1px solid #eee;height:30px;margin-right:0px'>
							<button class='pointer'
							style="margin-right:10px;height:30px;color:#eee;display:inline-block;
							border:1px solid #ddd;padding-bottom:4px;padding-top:5px;padding-left:11px;padding-right:11px;background:#700" onmouseover='this.style.background="#200"' onmouseleave='this.style.background="#700"'>
								<i class='ion-ios-search-strong'></i>
							</button>
						</form>
						</span>
					</li>
					<li>
						<a class='pointer' data-toggle='modal' data-target='#post'><span class='icon ion-compose'></span> Publish a post</a>
					</li>
					<li class='dropdown'>
						<a class='pointer dropdown-toggle' href='javascript:' data-toggle='dropdown'><i class='ion-android-notifications'></i> Notifications
							<sup id='header_new_notifications' class='label label-danger'style='border:2px
							solid #fff;border-radius:15px;'><?php echo get_new_notifications($_SESSION['user']); ?></sup>
						</a>
						<ul class='dropdown-menu' style='background:#fff;width:350px;padding:5px'>
							<div id='header_notification_place' style='max-height:400px;overflow:auto'><?php echo show_new_notifications($_SESSION['user']); ?></div>
							<li><div><a href='#' class='col-xs-6' style="border-right:1px solid #aaa" ><i class="ion-eye"></i> View all... </a>
							<a class='col-xs-6  pointer' onclick="mark_notif_read(0,'all')"><i class="ion-android-checkbox"></i> Mark all as seen</a></div></li>
						</ul>
					</li>

					<li class='dropdown'>
						<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
							<span class='icon ion-chatbubbles'></span> Messages
							<sup id='header_receptor_messages' class='label label-danger' style='border:2px
							solid #fff;border-radius:15px;'><?php echo get_new_messages($_SESSION['user']); ?></sup>
						</a>

						<ul class='dropdown-menu' style='background:#fff;width:350px;padding:5px'>
							<div id='header_message_place' style='line-height:35px;'>
								<?php echo show_new_messages($_SESSION['user']); ?>
							</div>
							<li><div><a href='messages.php' class='col-xs-6' style="border-right:1px solid #aaa" ><i class="ion-eye"></i> View all... </a>
							<a href='messages.php' class='col-xs-6'><i class="ion-android-checkbox-outline"></i> Mark all as Read</a></div></li>
						</ul>
					</li>
					<li class='dropdown' >
						<a href='#' class='dropdown-toggle' data-toggle='dropdown' style='padding:5px;padding-top:15px'>
							<?php echo "<img src='".file_validity(get_ppic($_SESSION['user'],$connection))."tumb.jpg' height='30px' width= '30px'style='border-radius:20px'>"; ?> <span class="caret"></span>
						</a>
						<ul class='dropdown-menu'>
							<li class='acive'>
								<a class='actve'>
									<i class='icon ion-android-contact'></i> Welcome, <?php echo get_name($_SESSION['user']) ?>
								</a>
							</li>
							<li><a href="profile.php"><i class='ion-eye'></i> View Profile</a></li>
							<li><a href='follow.php?user=<?php echo $_SESSION["user"] ?>&view=followers'><i class='ion-person-stalker'></i> View Followers</a></li>
							<li><a href='javascript:' data-toggle='modal' data-target='#settingsGlobal'><i class='ion-gear-b'></i> Settings</a></li>
							<li><a href='logout.php'><i class='ion-power'></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!--end visible navbar -->

	<!-- dynamic posting modal -->
	<div class='modal fade' id='post' role='dialog'>

		<div class='modal-dialog'>
			<div class='modal-content'>
				<!--div class='modal-header' style='padding:5px;'>
					<h4> <span class='icon ion-compose'></span> Publish a post </h4>
				</div -->
				<div class='modal-body' style='padding-top:2px;padding-bottom:5px;'><span class='close' data-dismiss='modal'>&times;</span>
					<table width='100%'><tr><td width='90%'>
					<textarea id='post_modal' placeholder='Type in your post here then click Publish. . . ' style='outline:none; border:2px solid #fff; width:100%;resize:none'></textarea></td><td valign=' center'>
					<button class='btn btn-danger pull-right' onclick='publish_post(document.getElementById("post_modal").value,user,token)'>Publish <i style='font-size:1.3em' class='icon ion-ios-paperplane'></i></button></td></tr></table>
				</div>
			</div>
		</div>

	</div>


	<!-- scripts to handle cross-page reloading  what am i naming these things sef? yeah, plus the div to take the outputs -->
	<div id='header_items'></div>

	<script type="text/javascript">
		setInterval(function(){header_refresh()} ,10000)
	</script>
