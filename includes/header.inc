<! DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1.0" />
		<meta name="description" content="" />
		<meta name="author" content="Friday Godswill U" />
		<link href='css/bootstrap.min.css' rel='stylesheet' type="text/css"/>
		<link href='css/hint.min.css' rel='stylesheet' type="text/css"/>
		<link href='css/ionicons.css' rel='stylesheet' type="text/css"/>
		<link rel="stylesheet" href="css/alertify.core.css" />
		<link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
		<link rel="shortcut icon" href="favicon.ico"/>
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
		<script>
			var user = "<?php echo $_SESSION['user'] ?>";
			var token = "<?php echo transform($_SESSION['user']) ?>"
			phone_setting = email_setting = 0;
		</script>
		<style type="text/css">
		.special_input
		{
			width: 88%;
			background: transparent;
			border:0px;
			height:30px;
			outline:none;
		}

		.modal .bod
		{
			border:1px solid #ddd;
			padding-left: 5px;
			width:50%;
		}
		.modal .bod_child
		{
			border:1px solid #ddd;
			padding-left: 5px;
			width:25%;
		}
		.bod select,.modal .bod_child select
		{
			appearance:none;
			-moz-appearance:none;
			-webkit-appearance:none;
			-o-appearance:none;
			height:40px;
			width: 100%
		}

		.bod input,.modal .bod_child input
		{
			height: 40px;
		}

		.modal table td i
		{
			font-size: 1.6em;
		}

		.modal table
		{
			margin-bottom: 5px;
		}
		*{
		font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
		}

		</style>


	</head>
	<!-- signup modal -->
	<div class='modal fade' role='dialog' id='signUpModal'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					 <button data-dismiss='modal' class='close'>&times;</button><h3><span class='icon ion-android-checkbox-outline'></span> Sign Up</h3>
				</div>
				<div class='modal-body'>
					<span id='regplace'></span>
					<!--div id='regbody'>
						<table width='100%'>
							<tr>
								<td class='bod'>
									<i class='ion-ios-contact text-muted'></i>&nbsp;
									<input type='text' class='special_input' id='firstName' placeholder='Firstname eg: Friday'/>
								</td>
								<td> &nbsp;</td>
								<td  class='bod'>
									<i class='ion-android-contact text-muted'></i>&nbsp;
									<input type='text' class='special_input' id='lastName' placeholder='Lastname eg: Cyril'/>
								</td>
							</tr>
						</table>
						<table width='100%'>
							<tr>
								<td class='bod'>
									<i class='ion-person text-muted'></i>&nbsp;
									<input type='text' id='usernamee' onkeyup="compare(document.getElementById('usernamee').value)" placeholder='Chose a username, Eg: dhabs' class='special_input'/>
								</td>
								<td>&nbsp;</td>
								<td class='bod'>
									<i class='ion-locked text-muted'></i>&nbsp;
									<input type='text' id='pwrd' placeholder='Chose a password' class='special_input'/>
								</td>
							</tr>

						</table><span id='username_helper'></span>
						<table width='100%'><tr><td class='bod'><i class='ion-iphone text-muted'></i>&nbsp;&nbsp;&nbsp;
						<input type='text' id='phone' placeholder='Phone eg: 08138317490' class='special_input'/></td>
								<td>&nbsp;</td>
						<td class='bod'>
						<select id='gender' class='special_input'>
							<option value='Male'>Male</option>
							<option value='Female'>Female</option>
						</select></td></tr></table>
						<table width='100%'>
							<tr>
								<td class='bod_child' style="width:50.6667%">
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
								<td>&nbsp;</td>
								<td  class='bod_child'>
									<select id='year' class='special_input'>
										<?php
											for($i=1972;$i<2015;$i++)
											{
												echo "<option value='$i'>$i</option>";
											}
										?>
									</select>
								</td>
								<td>&nbsp;</td>
								<td class='bod_child'>
									<select type='select' id='day' class='special_input' placeholder='Day'>
										<?php
											for($i=1;$i<32;$i++)
											{
												echo "<option value='$i'>$i</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</div -->
					<div id='reg_loading' align='center' style="display:none">
						<img src='images/hh.gif'>
					</div>
						<div id="regplace"></div>
						<div id='continue_reg' style="display:none">
							Please Fill the fields below to complete Your registration.<br />
							<input id='reg_phone' type="hidden">
							<label>Full Name</label>
							<input type="text" class="form-control" placeholder="Enter Full Name" id='reg_name' />
							<label>Username</label>
							<input type="text" onkeyup="compare(this.value)" class="form-control"
							 placeholder="Please Choose a Username" id='reg_username2' />
							<span id='username_helper'></span>
							<label>Password</label>
							<input type="text" class="form-control" placeholder="Please Choose a Password" id='reg_password2' />
							<label>Gender</label>
							<select id='reg_gender' class="form-control">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
							<button class='btn btn-danger' onclick="signup($('#reg_name').val(),$('#reg_username2').val(),$('#reg_password2').val(),$('#reg_gender').val(),$('#reg_phone').val())">
								Continue
							</button>
						</div>
						<div id='reg_main_body'>
							<input class='form-control' type="text" id='number' placeholder="Enter Your Phone number"/>
							<button class='btn btn-danger'
									onclick="check_number(document.getElementById('number'))">
								Continue
							</button>
					  </div>
				</div>
				<!--div class='modal-footer' id='regfooter'>

				</div-->
			</div>
		</div>
	</div>
	<!--/end modal for signup -->

	<div id='dummy2'></div>
