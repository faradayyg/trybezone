<style media="screen">
	.nav li a
	{
		padding-left: 5px;
		padding-right:5px;
		font-size: 0.9em;
		border-radius:0px;
	}
</style>
<ul class='nav navbar-red nav-tabs'>
			<li><a href='index.php'>Home</a></li>
			<li><a href='messages.php'>Messages
				<small style="color:#0d0">
				  <b><?php echo get_new_messages($_SESSION['user']) ?></b>
			  </small></a>
			</li>
			<li><a href='channels.php'>Channels</a></li>
			<li><a href='notif.php'>Notifications
				<span style="color:#0d0">
				  <b><?php echo get_new_notifications($_SESSION['user']) ?></b>
			  </span></a>
			</li>
			<li><a href='online.php'>Online
				<span style="color:#0d0">
					<b><?php $onln = (online_number($_SESSION['user']) <1) ? '':"(".online_number($_SESSION['user']).")"; echo $onln; ?></b>
				</span>
			</a></li>
	</ul>
	<?php
	keep_online($_SESSION['user'],'mobile');
	 ?>
