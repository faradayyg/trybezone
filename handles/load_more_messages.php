<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$start = sanitize($_POST['start']);
	$stop = sanitize($_POST['stop']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	$sql = "SELECT messages.messageid,messages.sender,messages.message,messages.time,messages.receiver,messages.read FROM messages";
				$sql .= " WHERE messages.receiver = '$username' OR messages.sender = '$username' ORDER BY time DESC LIMIT $start,$stop";
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
					$pic = advanced_file_validity('../'.get_ppic($use,$connection).'tumb.jpg',get_ppic($use,$connection).'tumb.jpg');
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

?>