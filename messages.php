<?php
	include('includes/functions.inc');
	include('includes/header.inc');
	include('includes/constants.inc');
	include('includes/db.inc');
	user('includes/easy_login.inc');
	include('includes/primary_nav.inc');
	keep_online($_SESSION['user']);
?>
<script type="text/javascript">var h</script>
<div class='pad-tp'>
	<div class='col-sm-3'>
		<div class='panel panel-default' style=''>
			<div class='panel-body' id='main_list' style='padding:0px;padding-top:10px;'>
				<b><center>New Messages</center></b>
			  <div style='padding:15px;'>
			  	<div style='border:1px solid #aaa;border-radius:3px;box-shadow:0 0 1px #ddd;'>
					<table width='100%'><tr><td width='85%'><input type='text' class='' style='border:2px solid #fff;border-right:1px
						solid #aaa;outline:none
					;padding-left:10px;padding-right:10px;width:100%;height:30px' placeholder='search...' /></td><td class='hov pointer'>
					<i class='icon ion-android-search' style='font-size:1.6em;margin-left:10px;'></i></td></tr></table>
				</div>
			  </div>
			  <div style='height:80%;overflow:auto'>
			  <div  id='scroll_more'>
			  <?php
			  	$username = $_SESSION['user'];
			  	$sql = "SELECT messages.messageid,messages.sender,messages.message,messages.time,messages.receiver,messages.read FROM messages";
				$sql .= " WHERE messages.receiver = '$username' OR messages.sender = '$username' ORDER BY time DESC LIMIT 10";
				foreach ($connection->query($sql) as $key) {
					$i = new userInfo;
			  	$bg = "#fff";
					$i->connection = $connection;
					$i->user = $key['sender'];
					$use = $key['sender'];
					if($key['sender']==$username)
					{
					$i->user = $key['receiver'];
					$use = $key['receiver'];

					}
					elseif($key['receiver']==$username)
					{
						if($key['read']==0)
						{
							$bg = '#fee';
						}
					}
					$pic = advanced_file_validity(get_ppic($use,$connection).'tumb.jpg',get_ppic($use,$connection).'tumb.jpg');
					echo "
					<div class='use' style='border-bottom:1px solid #aaa;line-height:20px;padding:7px;background:$bg;'
					onmouseleave='check_leave(this)' onmouseover='check_hover(this)' onclick='$(\".use\").css(\"background\",\"$bg\");
					this.style.background=\"#faa\";$(\".use\").attr(\"data-active\",\"false\");this.setAttribute(\"data-active\",\"true\")'>
						<table class='pointer' width='100%' onclick=\"set_footer($key[messageid]); main_message_view($key[messageid]);reld($key[messageid])\">
							<tr style='padding:5px;'>
								<td>
									<img src='$pic' height='40px' width='40px'>
								</td>
								<td align='left'>
									<b><small>".$i->get_name()."</small></b><br /><small class='text-muted'>".substr($key['message'], 0,20)."</small>
								</td>
								<td align='right'>
									<small class='text-danger'>".short_past($key['time'])."</small>
								</td>
							</tr>
						</table>
					</div>";
				}

				if($connection->query($sql)->rowCount()>9)
				{
			  ?></div>
				<div id='message_list_append'></div>
				<br />
				<a href='javascript:' class='btn btn-default form-control' onclick="load_more_msg(5,this)"><i class='ion-android-add-circle'></i> Load More Messages</a>
			<?php }else{echo"</div>";} ?>
			</div>
			</div>
		</div>
	</div>
	<div class='col-sm-5'>
		<div class='panel panel-default' style='margin-bottom:0px'>
			<div class='panel-heading'><span id='message_heading_span'>Message View</span> <span class='pull-right' id='loading' style='display:none'><img src='images/s_l.gif' /></span></div>
			<div class='panel-body'id='main_message_body' style='height:80%;overflow:auto;padding-left:0px;padding-right:0px'><b>Click On a mesage by the left
			 and it will be shown here in full</b></div>

		</div>
		<div id='footer_message'></div>
	</div>
</div>
<?php

	include('includes/footer.inc');
?>
<script type="text/javascript">
	function main_message_view(messageid)
	{
		$('#loading').show(400);
		$.post('handles/get_full_message.php',{messageid:messageid,token:token,user:user},function(data){
			$('#main_message_body').html(data)
		}).success(function(){
			document.getElementById('main_message_body').scrollTop = 200000
		}).complete(function(){
			$('#loading').slideUp(400)
			document.getElementById('main_message_body').scrollTop = 200000
		})

	}
		function main_message_view_reload(messageid)
		{
			$('#loading').show(400);
			$.post('handles/get_full_message.php',{messageid:messageid,token:token,user:user},function(data){
				$('#main_message_body').html(data)
			}).success(function(){
			}).complete(function(){
				$('#loading').slideUp(400)
			})

		}

	function check_hover(t)
	{
		if(t.getAttribute("data-active")!='true')
		{
			t.style.background="#eee"
		}
		else
		{

		}

	}
	function check_leave(t)
	{
		if(t.getAttribute("data-active")!='true')
		{
			t.style.background="#fff"
		}
		else
		{

		}

	}
 feed_number_init =10

 function load_more_msg(how_many,btn)
	{
		start = feed_number_init
		feed_number_init +=how_many
		$.post('handles/load_more_messages.php',{user:user,token:token,start:start,stop:how_many},function(data){
			$('#message_list_append').append(data)
		}).success(function(data){
			btn.innerHTML = "<i class=\"ion-android-add-circle\"></i> Load More Messages"
			if(data=='')
			{
				btn.style.display = 'none'
			}
		}).error(function(){
			btn.innerHTML = "<i class=\"glyphicon glyphicon-refresh\"></i> Unable to load, click to retry"
		})
	}

function set_footer(messageid)
{
	$('#footer_message').html("<div style='padding:10px;background:#eee'><table width='100%' style='margin-top:0px'><tr><td><textarea class='no-resize'"+
			" onmouseover='$(this).autoGrow()' style='width:100%;outline:none;border:1.5px solid #aaa;height:40px;padding:10px' id='txt_main' placeholder='type a message'>"+
			"</textarea></td><td>&nbsp;&nbsp;<button class='btn btn-danger' onclick='send_message_q("+messageid+",$(\"#txt_main\").val(),\"message_append_area\",\"txt_main\",\"main_message_body\")'"+
			"style='outline:none;'>Send</button></td><td>Panel</td></tr></table></div>")
}

function reld(id)
	{
		clearInterval(h);
		h = setInterval(function(){main_message_view_reload(id)
		},5000)
	}

	setInterval(function(){$.post('handles/load_more_messages.php',{user:user,token:token,start:0,stop:10},function(data){
		$('#scroll_more').html(data)
	})
		},5000)
</script>
<style type="text/css">
.hov:hover
{
	background:#efefef;
}

</style>
