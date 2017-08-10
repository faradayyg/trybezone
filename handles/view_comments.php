<?php
	include('../includes/constants.inc');
	include('../includes/db.inc');
	include('../includes/functions.inc');
	$username = sanitize($_POST['username']);
	$type = sanitize($_POST['type']);
	$token = sanitize($_POST['token']);
	$id = sanitize($_POST['id']);
	if(!(empty($username)||empty($id)||empty($token)))
	{
		if(transform($username)!=$token)
		{
			echo "<div class='alert alert-warning'><b>There was an error with your login, please logout and login again;</b></div>";
			exit();
		}

		if($type=='sta')
		{
			$sql = "SELECT coms.*,users.firstname,users.lastname,users.sex,users.level,users.department FROM coms INNER JOIN users ON users.username = coms.username WHERE upid='$id' ORDER BY coms.stamp ";
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);

			foreach($query as $r)
			{
				$gender = $r['sex'];
				if($gender == 'M')
				{
					$gender = 'Male';
				}
				else{
					$gender = 'Female';
				}
				$pic = get_ppic($r['username'],$connection);
				$pic = advanced_file_validity('../'.$pic,$pic);
				echo "<div>
					<table width='100%' style='color:#444;font-size:15px;border-bottom:1px solid #ddd;margin-bottom:9px'>
						<tr>
							<td valign='top' style='padding-right:1;width:10%;overflow:hidden;'>
								<img src='$pic".'tumb.jpg'."' height='40px' style='border-radius:20px'>
							</td>
							<td>
								<a href='profile.php?view=".strtolower($r['username'])."'
								class='hint--right' data-hint='$r[department] department, $r[level]level, $gender'><b>$r[firstname] $r[lastname]</b></a>  <br/>
								<span class=''>$r[comment]</span>
								<br /><small class='text-muted'>$r[disp]</small>
							</td>
						</tr>
					</table>
				</div>";
			}
			if($query->rowCount()==0)
			{
				echo "<div class='text-info'>There are no comments on this yet</div>";
			}
		}
		elseif($type == 'pic')
		{
			$sql = "SELECT uploadcomments.*,users.firstname,users.lastname,users.sex,users.level,users.department,users.username
			FROM uploadcomments INNER JOIN users ON users.username = uploadcomments.commenter WHERE uploadcomments.upid = '$id' ORDER BY stamp ASC";
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$pic = get_ppic($r['username'],$connection);
				$pic = advanced_file_validity('../'.$pic,$pic);
				$gender = $r['sex'];
				if($gender == 'M')
				{
					$gender = 'Male';
				}
				else{
					$gender = 'Female';
				}
				echo "<div>
					<table width='100%' style='color:#444;font-size:15px;border-bottom:1px solid #ddd;margin-bottom:9px'>
						<tr>
							<td valign='top' style='padding-right:1;width:10%;overflow:hidden;'>
								<img src='$pic".'tumb.jpg'."' height='40px' class='' data-hint='$r[department], $r[sex] $r[level]level' style='border-radius:20px'>
							</td>
							<td>
								<a style='color:#444' href='profile.php?view=".strtolower($r['username'])."'
								class='hint--right hint--bounce hint--info hint--rounded'
								data-hint='$r[department] department, $r[level] level, $gender'><b>$r[firstname] $r[lastname]</b></a>
								 <br/><span class='text-muted'>$r[comment]</span>
								<br /><small class='text-danger'>$r[disp]</small>
							</td>
						</tr>
					</table>
				</div>";
			}
		}

		elseif ($type == 'cha')
		{
			$sql = "SELECT cp_comments.*,users.firstname,users.lastname,users.sex,users.department,profilepics.profilepic  FROM cp_comments ";
			$sql .= " INNER JOIN users ON users.username = cp_comments.username";
			$sql .= " INNER JOIN profilepics ON profilepics.username = cp_comments.username";
			$sql .= " WHERE cp_comments.p_id = '$id'";
			$query = $connection->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			foreach($query as $r)
			{
				$img = advanced_file_validity('../'.$r['profilepic'].'tumb.jpg',$r['profilepic'].'tumb.jpg');
        echo "
          <div style='border-bottom:1px solid #faa;'>
            <table>
              <tr>
                <td valign='top'>
                  <img src='$img' height='40px' style='border-radius:30px'> &nbsp;&nbsp;&nbsp;
                </td>
                <td>
                  <a href='profile.php?view=$r[username]'>$r[firstname] $r[lastname]</a> &nbsp;&nbsp;<br />
                  <div style='word-wrap:break-word;color:#333'>$r[comment]</div>
									<small class='text-muted'>$r[disp]</smalll>
                </td>
              </tr>
            </table>
          </div>
        ";
			}
			if($query->rowCount()==0)
			{
				echo "<span class='text-primary'>Sorry, There are no comments on this group post yet</span>";
			}
		}
	}
	else
	{
		echo "<script>show_hint('An error occoured viewing your comment')</script>";
	}
?>
