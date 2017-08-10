<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	echo "
	<style>
.p_comment
{
	background:#fff;
	border:1px solid #ddd;
	border-radius:4px;
	padding:5px;
	margin-left:5px;
	word-wrap:break-word;
}

.p_comment::before
{
	content: '';
	position:relative;
	top: 2px;
	right: 12px;
	border-style: solid;
	border-width: 8px 8px 0px 0px;
	border-color: transparent #fff;
	display: block;
	width: 0;
	z-index: 0;
}
</style>
<b>Comments</b>

	";
	$id = sanitize($_POST['id']);
	$sql = "SELECT uploadcomments.*,users.username,users.firstname,users.lastname,users.level,users.department,users.sex,profilepics.profilepic
	FROM uploadcomments INNER JOIN users ON uploadcomments.commenter =
	users.username INNER JOIN profilepics ON uploadcomments.commenter =
	profilepics.username WHERE uploadcomments.upid='$id' ORDER BY stamp";
	$query = $connection->query($sql);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	foreach($query as $r)
	{
		echo "
			<table width='100%' style='margin-top:3px'>
				<tr>
					<td style='width:20%' valign='top'><div style='width:40px;overflow:hidden;border-radius:20px;border:2px solid #fff;'><img src='$r[profilepic]".'tumb.jpg'."' height='40px' style='max-width:40px'/></div></td>
					<td>
						<div class='p_comment' style='font-size:0.85em;line-height:22px'>
						<a href='profile.php?view=$r[username]' class='hint--top hint--info'><b>$r[firstname] $r[lastname] </b></a><br >
							$r[comment] <br /> <small class='text-danger'>$r[disp]</small>
						</div>
					</td>
				</tr>
			</table>
		";
	}
	echo"
		<div id='p_last_comment'></div>
		<script>
			var type = \"pic_post\";

			document.getElementById('p_make_comment').innerHTML = \" <div class='input-group'><input class='form-control' id='p_p_textarea' type='text' placeholder='Type a comment. . .' onkeyup='data=this.value;if(event.keyCode==13){comment(this,$id,`pic_post`,user,token,type,type,1);}' style='width:130%'><span class='input-group-btn'><button class='btn btn-danger' id='p_p_btn_comment'>Comment</button></span></div> \"
			$('#p_p_btn_comment').click(function(){
				comment(document.getElementById('p_p_textarea'),$id,'pic_post',user,token,1,1,1);
			})
		</script>
	";
?>
