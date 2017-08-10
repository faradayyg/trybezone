<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['user']);
	$token = sanitize($_POST['token']);
	$messageid = sanitize($_POST['messageid']);

	//verify that the user is not hijacked by the transform property
	if($token != transform($username))
	{
		echo "
			<script>show_alert('there is an error with your session, please logout and login again')</script>
		";
		exit();
	}

	//verify i should be able to see the message
	$sql = "SELECT * FROM messages WHERE messageid = '$messageid' LIMIT 1";
	foreach ($connection->query($sql) as $r) {
		if($r['sender']!=$_SESSION['user'] AND $r['receiver']!=$_SESSION['user'])
		{
			echo "<div class='alert alert-warning'><center><i class='ion-ios-locked' style='font-size:3em'></i>
			<br />You tried to view an unauthorised message, this event will be reported</center></div>";
			log_action($_SESSION['user'],'tried to read an unauthorised message',$connection);
			exit();
		}
		$name_to_use = get_name($r['receiver']);
		if($r['sender']!=$_SESSION['user'] )
		{
			$name_to_use = get_name($r['sender']);
		}
	}
	if($r['sender']!=$_SESSION['user'])
	{
		$sql = "UPDATE `messages` SET `read` = '1' WHERE `messageid` = '$messageid'";
		$connection->exec($sql);
		log_action('ishi ike','naani',$connection);
	}
	$sql = "SELECT * FROM conversation WHERE messageid = '$messageid'";
	$query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
	foreach ($query as $k) {
	$style= "background:#eee;";

		if ($k['sender']==$_SESSION['user']) {
			$style = "background:#dfdfdf;margin-left:0%;text-align:right";
		}
		$ppic = advanced_file_validity('../'.get_ppic($k['sender'],$connection).'tumb.jpg',get_ppic($k['sender'],$connection).'tumb.jpg');
		echo "<div style='max-width:100%;word-wrap:break-word;padding:5px;margin:1px;border-radius:0px; $style'>
		<a href='profile.php?view=$k[sender]'><img src='$ppic' height='20px' width='20px' style='border-radius:10px'>
		".get_name($k['sender'])."</a><br />
				$k[message] <br /><small class='text-muted' style='font-size:0.75em;font-family:courier new'>$k[stamp]</small>
			</div>";
	}
?>

<div id='m_append'></div>
<script type="text/javascript">
	$('#m_heading_span').html("<b><?php echo $name_to_use; ?></b>")
	$('#panel_footer_message').html("<table width='100%'><tr><td><input type='text' id='p_textarea' class='form-control'"+
		" placeholder='type here to chat. . .' style='border-radius:9px;' onkeyup ='send_enter_message(event)'></td><td><i"+
		" onmouseover='$(this).addClass(\"text-danger\")'"+
		" onmouseout='$(this).removeClass(\"text-danger\")' onclick='send_message_q(<?php echo $messageid ?>,"+
			"$(\"#p_textarea\").val(),\"m_append\",\"p_textarea\",\"message_body\");' "+
	"class='ion-paper-airplane text-muted pointer' style='font-size:1.5em'>"+
		"</i></td></tr></table>")
	function send_enter_message(event)
	{
		if(event.keyCode == 13)
		{
			send_message_q(<?php echo $messageid ?>,document.getElementById("p_textarea").value,'m_append','p_textarea','message_body')
		}
	}
	document.getElementById('message_body').scrollTop = 20000000

</script>
