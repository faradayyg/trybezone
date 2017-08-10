<div style='padding:10px;margin:10px'></div>
<!-- load scripts up in here -->
<script src='js/jquery.js' type='text/javascript'></script>
<script src='js/bootstrap.min.js' type='text/javascript'></script>
<script src='js/alertify.min.js' type='text/javascript'></script>
<script src='js/myscript.js' type='text/javascript'></script>
<script src='js/autogrow_plugin.js' type='text/javascript'></script>
<script>
	$('#post_modal').autoGrow();
</script>
	<div id='dummy'></div>
<div class='modal fade' id='settingsGlobal' role='dialog'>

		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header' >
					<h4><span class='close' data-dismiss='modal'>&times;</span> <span class='icon ion-gear-a'></span> Profile Settings </h4>
				</div>
				<div class='modal-body' style='padding:2px'>
					<ul class='nav nav-tabs navbar-default'>
						<li><a class='pointer' onclick="$('.settings_body').hide();$('#settings_general').show();"><i class='icon ion-android-options'></i> General Settings</a></li>
						<li><a class='pointer' onclick="$('.settings_body').hide();$('#settings_privacy').show();"><i class='icon ion-android-globe'></i> Privacy Settings</a></li>
						<li><a class='pointer' onclick="$('.settings_body').hide();$('#settings_password').show();"><i class='icon ion-android-lock'></i> Change Password</a></li>
					</ul>
					<div style='padding:5px'>
						<div id='settings_general' class='settings_body' style='padding:10px;'>
							<h4>General Settings</h4>
							<table width='100%'><tr>
								<td align='right' ><b>Level</b> &nbsp;&nbsp;&nbsp;</td><td>
								<select class='form-control select-custom' id='settings_level'>
									<option value='100'>
										100
									</option>
									<option value='200'>
										200
									</option>
									<option value='300'>
										300
									</option value='400'>
									<option>
										400
									</option>
									<option value='500'>
										500
									</option>
									<option value='600'>
										600
									</option>
									<option value='Graduated'>
										Graduated
									</option>
								</select> </td><td>&nbsp;&nbsp;&nbsp;<button class='btn btn-danger' title='click here to save your settings' style='padding:10px; border-radius:70px; border:2px solid transparent;height:40px;width:40px'  onclick="this.style.background='#f40';settings_set('level',$('#settings_level').val(),this)"><i class='icon ion-checkmark-round'></i></button>
							</td></tr><tr><td>&nbsp;</td></tr>
								<tr>
									<td align='right'>
										<b>Department</b>&nbsp;&nbsp;&nbsp;
									</td>
									<td>
										<select  class='form-control select-custom' id='settings_department'>
					            <?php
					              $departments = return_departments();
					              foreach ($departments as $key => $value) {
					                echo "<option value='$key'>$value</option>";
					              }
					             ?>
										</select>
									</td>
									<td>
										&nbsp;&nbsp;&nbsp;<button class='btn btn-danger' onclick="this.style.background='#f40';settings_set('department',$('#settings_department').val(),this)" style='padding:10px; border-radius:70px; border:2px solid transparent;height:40px;width:40px'><i class='icon ion-checkmark-round'></i></button>
							</td></tr>
									</td>
								</tr>
							</table>
						</div>
						<div id='settings_privacy' class='settings_body'  style='display:none'>
							<h4 style='padding-left:20px;'>Privacy Settings</h4>
							<?php

							$email_class=$phone_class='ion-toggle-filled text-success';
							//get settings for phone number
								$sql = "SELECT setting,value FROM settings WHERE username = '$_SESSION[user]' AND setting = 'show_phone'";
								foreach ($connection->query($sql) as $r)
								{
									$phone_class='ion-toggle text-danger';
									if($r['value']==1)
									{
										$phone_class='ion-toggle-filled text-success';
										echo "<script>phone_setting = 1;</script>";
									}
								}

								if($connection->query($sql)->rowCount()==0)
								{
									echo "<script>phone_setting = 1</script>";
								}

								//get settings for the email_show
								$sql = "SELECT setting,value FROM settings WHERE username = '$_SESSION[user]' AND setting = 'show_email'";
								foreach ($connection->query($sql) as $r)
								{
									$email_class='ion-toggle text-danger ';
									if($r['value']==1)
									{
										$email_class='ion-toggle-filled text-success';
										echo "<script>email_setting = 1</script>";
									}
								}
								if($connection->query($sql)->rowCount()==0)
								{
									echo "<script>email_setting = 1</script>";
								}
							?>
							<table width='100%'>
								<tr>
									<td align='right' width='40%'><b>Show Phone Number</b> &nbsp;&nbsp;&nbsp;</td>
									<td><i onclick='toggle_setting("phone_setting",this);settings_set("privacy","phone",this)' class='icon <?php echo $phone_class ?>  pointer ' onclick="" style='font-size:3em;'></i> &nbsp;&nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td align='right' width='40%'><b>Show Email</b> &nbsp;&nbsp;&nbsp;</td>
									<td><i onclick='toggle_setting("email_setting",this);settings_set("privacy","email",this)' class='icon <?php echo $email_class ?> pointer' style='font-size:3em;'></i> &nbsp;&nbsp;&nbsp;</td>
								</tr>
							</table>
						</div>
						<div id='settings_password' class='settings_body' style='display:none;padding:5px'>
							<h4>Password Change</h4>
							<table width='100%'>
								<tr>
									<td align='' width='30%'>Old Password&nbsp;&nbsp; </td>
									<td><input type='password' class='form-control' id='setting_old_password' placeholder='Enter Your Former Password'></td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td align='' width='30%'>New Password&nbsp;&nbsp; </td>
									<td><input type='password' class='form-control' id='setting_new_password' placeholder='Enter Your New Password'></td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td align='' width='30%'>Retype Password&nbsp;&nbsp; </td>
									<td><input type='password' class='form-control' id='setting_repeat_password' placeholder='Enter Your New password Again'></td>
								</tr>
								<tr><td></td><td align='right'><br /><button onclick='change_password($("#setting_old_password").val(),$("#setting_new_password").val(),$("#setting_repeat_password").val());' class='btn btn-lg btn-danger'>Change</button></td></tr>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</html>
