<?php
  session_start();
	include '../includes/login_required.inc';
	include '../includes/constants.inc';
	include '../includes/functions.inc';
	include '../includes/db.inc';
	include 'includes/header.inc';
	include 'includes/nav.inc';
?>
	<div style="padding:15px;background:#fff">
		<b>Who to follow</b>
		<?php
			$username = $_SESSION['user'];
			$counter = 0;
            $pcount = 0;
            $sql = "SELECT users.username FROM users WHERE username !='$username' ORDER BY RAND() LIMIT 10";
            $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
            foreach ($query as $r) {
              $person[$counter] = $r['username'];
              $counter++;
            }

            foreach ($person as $r) {
              $sql2 = "SELECT username FROM followers WHERE username='$r' and follower = '$username' and username!='$username' AND type='user'";
              if($connection->query($sql2)->rowCount()==0)
              {
                $pcount++;
                $usr = new userInfo;
                $usr->user = $r;
                $usr->connection = $connection;
                $usr_name = $usr->get_name();
                $ppic = get_ppic($r,$connection);
                $ppic = file_validity('../'.$ppic.'tumb.jpg');
                echo "<div class='panel panel-danger' style='padding:0px'>
                <table width='100%'>
                	<tr>
                	<td><img src='$ppic' height='40px' width='40px'></td>
                	<td align='center'><a href='profile.php?view=$r'>$usr_name</a></td>
                	<td align='right'>
                		<button class='btn btn-danger'onclick=\"follow('$r',this,'user')\"  style='padding:2px;height:40px'>
		                  Follow
		                </button>
                	</td>
                	</tr>
                </table>
                
                </div>";
              }
            }
            $sm = ($pcount==0) ? "<h4 class='text-muted'>There are no suggestions at the moment</h4>":"<a href='?show_suggestions'>show More</a>";
            echo "<br />".$sm;
		?>
	</div>
<?php  
include 'includes/footer.inc'
?>