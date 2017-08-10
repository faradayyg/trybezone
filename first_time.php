<?php
  session_start();
	include('includes/functions.inc');
  user('includes/easy_login.inc');
	include('includes/header.inc');
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/primary_nav.inc');
  $count = 0;
?>
<title>Welcome to <?php echo SITE_NAME ?> | getting started guide</title>
<style>
  select
  {
    appearance:none;
    -moz-appearance:none;
    border:1px solid #eee;
    background-color: #aaa;
  }

  .panel
  {
    border:0px solid;
  }

  .special_input
  {
    border:1px solid #aaa;
    width:98%;
  }
</style>
<div class="container">
  <div class="col-sm-8">
    <h2>Welcome to The Trybe!!!</h2>
    Hello <?php echo $_SESSION['user'] ?>, Here are a few things to get you started.<br />&nbsp;

    <?php
    $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='pic'";
    if($connection->query($sql)->rowCount()==0)
    { ?>
    <div class='panel panel-default' id='ft_pic_div'>
      <div class="panel-body">
        <h3>Upload a Profile Picture</h3>
          <table>
            <tr>
              <td><div class='btn btn-default' style="padding:0px;height:35px;overflow:hidden">
              <form enctype="multipart/form-data" name="pic" id='pic_form'>
                <input onchange="$('#btn_upload').show();$('#upload_text_hint').html('Click on the Upload Button to upload.')"
                style='position:relative;top: 0px;right: 0px;opacity: 1;padding:32px;cursor:pointer;outline:none'
                type="file" id='ft_pic' name='picture'>
              </form>
                <div id='upload_text_hint' style="margin-top:-80px;font-weight:bold"><i class="ion-android-camera"></i> Select Photo </div>
                </div></td>
              <td><button onclick="set_pic_ft('pic_form','ft_pic')" style='display:none' id='btn_upload' class='btn btn-danger'>Upload</button></td>
              <td>&nbsp; &nbsp;<span id='loading' style="display:none"><img src='images/hh.gif'></span></td>
            </tr>
          </table>
      </div>
    </div>
    <?php }
    else{
      $count++;
    }
    ?>
    <?php
    $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='dob'";
    if($connection->query($sql)->rowCount()==0)
    { ?>
    <div class="panel panel-default" id='ft_dob_div'><div class="panel-body">
    <h3>Date Of Birth:<br /><small> This enables Us to wish you a happy birthday when its time,
      The Year is never displayed and your age will not be known</small></h3>
      <table width='100%'>
        <tr>
          <td>
            <select type='select' id='day' class='special_input' placeholder='Day'>
              <?php
                for($i=1;$i<32;$i++)
                {
                  echo "<option value='$i'>$i</option>";
                }
              ?>
            </select>
          </td>
          <td>
            <select id='month' class='special_input'>
              <option value='January'>January</option>
              <option value='Febuary'>Febuary</option>
              <option value='March'>March</option>
              <option value='April'>April</option>
              <option value='May'>May</option>
              <option value='June'>June</option>
              <option value='July'>July</option>
              <option value='August'>August</option>
              <option value='September'>September</option>
              <option value='October'>October</option>
              <option value='November'>November</option>
              <option value='December'>December</option>
            </select>
          </td>
          <td>
            <select id='year' class='special_input'>
              <?php
                for($i=1972;$i<2015;$i++)
                {
                  echo "<option value='$i'>$i</option>";
                }
              ?>
            </select>
          </td>
          <td>
            <button onclick="set_dob_ft($('#day').val(),$('#month').val(),$('#year').val())" class="btn btn-danger">Select</button>
          </td>
        </tr>
      </table>
    </div>
    </div>
    <?php }
    else{
      $count++;
    }?>
    <?php
    $sql = "SELECT * FROM `wowed` WHERE `username` = '$_SESSION[user]' and `field`='department'";
    if($connection->query($sql)->rowCount()==0)
    { ?>
      <div id='ft_dept_div' class="panel panel-default"><div class="panel-body">
      <h3>Select Your Department</h3>
      <table>
        <tr>
          <td>
            <select id='department' class="special_input">
            <?php
              $departments = return_departments();
              foreach ($departments as $key => $value) {
                echo "<option value='$key'>$value</option>";
              }
             ?>
           </select>
         </td>
         <td>
           <button onclick="set_dept_ft()"class="btn btn-danger">Select</button>
         </td>
       </tr>
     </table></div>
   </div>
   <?php }
   else{
     $count++;
   }
   if($count == 3)
   {
     echo "<div class='panel panel-default'><div class='panel-body'><h3>It Seems you have completed Your \"Baby Steps\" You can now continue to the
     site and have the full social experience</h3> <a class='btn btn-danger' href='index.php'>Continue</a></div></div>";
   }
   ?>
   <div id='ft_complete' style="display:none">
     <div class='panel panel-default'>
       <div class='panel-body'>
         <h3>Congratulations!! you just completed the "getting started" guide, You are now ready for the full social experience.</h3>
         <a class='btn btn-danger' href='index.php'>Continue</a>
       </div>
     </div>
   </div>
  </div>
  <div class="col-sm-4">
    <h3>Follow a few people</h3>
    Here are a few people we thought you might want to follow<br />
    <div class="panel panel-default" style="max-height:400px;overflow:auto">
      <div class='panel-body'>
        <?php
        $username = $_SESSION['user'];
        $u = new userInfo;
        $u->user = $_SESSION['user'];
        $u->connection = $connection;
        $name = $u->get_first_name();
        $following = $u->get_following();

        $counter = 0;
        $sql = "SELECT users.username FROM users WHERE username !='$username' ORDER BY RAND() LIMIT 20";
        $query = $connection->query($sql);$query->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($query as $r) {
          $person[$counter] = $r['username'];
          $counter++;
        }

        foreach ($person as $r) {
          $sql2 = "SELECT username FROM followers WHERE username='$r' and follower = '$username' and username!='$username' AND type='user'";
          if($connection->query($sql2)->rowCount()==0)
          {
            $usr = new userInfo;
            $usr->user = $r;
            $usr->connection = $connection;
            $usr_name = $usr->get_name();
            $ppic = get_ppic($r,$connection);
            $ppic = file_validity($ppic.'tumb.jpg');
            echo "<div class='panel panel-info'><button onclick=\"follow('$r','user',this)\" class='btn btn-info pull-right' style='padding:2px'>
              <i class='icon ion-android-person-add'></i>
            </button>
            <img src='$ppic' height='40px' width='40px'>
            <a href='profile.php?view=$r'>$usr_name</a>
            </div>";
          }
        }

        ?>
      </div>
    </div>
  </div>
</div>
<?php
  echo "<script> var count = $count </script>";
  include 'includes/footer.inc';
 ?>
 <script type="text/javascript">
   function set_dept_ft()
   {
     show_hint('Processing. . . ')
     set = settings_set('department',$('#department').val(),document.getElementById('ft_dept_div').getElementsByClassName('btn')[0])
     wowed_on('department')
     $('#ft_dept_div').slideUp()
     count++
     check_count()
   }

   function set_dob_ft(day,month,year)
   {
     show_hint('Processing. . . ')
     $.post('handles/set_dob.php',{day:day,month:month,year:year},function(data){

     }).success(function(){
         $('#ft_dob_div').slideUp()
         wowed_on('dob')
         count++
         check_count()
     })
   }

   function check_count()
   {
     if(count == 3)
     {
       $('#ft_complete').slideDown()
     }
   }

   function set_pic_ft(form,img){
 		$('#loading').show(400)
 		tt = document.getElementById(form)
 		$.ajax({
 		url:"handles/upload_first_time.php",
 		type:"POST",
 		data: new FormData(tt),
 		contentType:false,
 		cache:false,
 		processData:false,
 		success:  function(data)
 		{
 			$('#'+img).val('');
 			$('#dummy').html(data);
      $('#ft_pic_div').slideUp()
      wowed_on('pic')
      count++
      check_count()
 		},
 		complete:  function()
 		{
 			$('#loading').hide(400);
 		},
 		error:  function()
 		{
 			$('#loading').hide(400);
 			show_alert('Unable to upload file, network is unavailable at the moment, try again latter')
 		},

 		})
 	}
 </script>
