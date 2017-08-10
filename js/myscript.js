/*$(window).scroll(function(){
	if($(window).scrollTop()>40 )
	{
		document.getElementById('nav').className ='navbar navbar-red navbar-fixed-top';
		document.getElementById('static_right').style.paddingTop='52px'
	}
	else if($(window).scrollTop()<40)
	{
		document.getElementById('nav').className ='navbar navbar-red navbar-static-top'
		document.getElementById('static_right').style.paddingTop='0px'
	}
})*/

//some useful but foolish variables. I need to improve my skills |-_-|
var txSuccess=0
var isDialog =0
function like(the_div,the_number,type,action,last)
{

	num = the_number
	if(last.getAttribute('action') == 'add')
	{
		todo = 1
		last.setAttribute('action','sub')
		last.setAttribute('data-hint','Unlike')
		num =parseInt($('#'+the_div).html())
	}
	else
	{
		todo = -1
		last.setAttribute('action','add')
		last.setAttribute('data-hint','Like')
		num =parseInt($('#'+the_div).html())
	}
	$('#'+the_div).html(num+todo)
	$.post('handles/like.php',{user:user,token:token,type:type,id:the_div},function(data){
		$('#dummy').html(data)
	})
}

var interval
function dialog_message_view(messageid)
{
	document.getElementById('dummy2').style.display='block'
	document.getElementById('dummy2').innerHTML = ""+
	"<div id='jjj' style='width:20%;min-width:300px;bottom:0%;position:fixed;right:0px;z-index:23000'>"+
	"<div class='panel panel-danger' style='box-shadow:0 0 5px #555'><div class='panel-heading' id='m_heading' style='cursor:all-scroll'>"+
	"<span class='close' onclick=\"$('#dummy2').slideUp(400)\">&times;"+
	"</span> &nbsp;<span class='btn btn-default close' style='padding-top:0px;margin-right:4px' onclick=\"$('#message_body').toggle(400);$"+
	"('#panel_footer_message').toggle(400)\">&minus;</span><span id='m_heading_span'>Conversation</span> </div><div class='panel-body'"+
	" style='height:300px;overflow:auto;padding:1px' id='message_body'></div><div class='panel-footer' id='panel_footer_message'></div></div></div>"


	$.post('handles/get_message.php',{messageid:messageid,token:token,user:user},function(data){
		$('#message_body').html(data)
	})
	clearInterval(interval)
	interval =	setInterval(function(){
		$.post('handles/reload_messages.php',{messageid:messageid,user:user,token:token},function(data){
			$('#message_body').html(data)

		})
	},5000)
}

function comment(comment,id,type,username,token,div_to_increment,rel,diag)
{
	error =''
	$('input').blur();
	error = check_length(comment,error,"Your Comment is either empty or too short")
	if (error == '')
	{
		show_hint('Commenting. . . please wait');
		$.post('handles/comment.php',{id:id,comment:comment.value,type:type,username:username,token:token,div:rel},function(ret){
			$('#dummy').html(ret)
		}).error(function(){
			comment(comment,id,type,username,token,div_to_increment,rel,diag);
			show_hint('retrying')
		}).success(function(){
			isDialog = diag
			if(isDialog==1)
			{
				load_pic_comments(id);
				$('#p_last_comment').html($('#p_last_comment').html()+"<table width='100%' style='margin-top:3px'><td style='width:20%' valign='top'>"+
				"<div style='width:40px;overflow:hidden;border-radius:20px;border:2px solid #fff;'><img src='images/no_pic.png' height='40px'></div></td>"+
				"<td><div class='p_comment' style='font-size:0.85em;line-height:22px'><a href='profile.php?view=$r[username]' class='hint--top hint--info'  data-hint='me'><b>Me</b></a><br>"+comment.value+
				"<br /> <small class='text-danger'>just now</small></td></tr></table>")
			}
			comment.value=''
			if(txSuccess==1 )
			{
				increment_numb(div_to_increment);
				show_comments('sta',rel,id)
			}
			if(txSuccess==2 )
			{
				increment_numb(div_to_increment);
				show_comments('pic',rel,id)
			}
			if(txSuccess==3 )
			{
				increment_numb(div_to_increment);
				show_comments('cha',rel,id)
			}
			txSuccess = 0
			isDialog = 0
		})
	}
	else
	{
		showhint(error);
	}
}

//functions to navigate through pictures
function go_next_photo(id)
{
	show_hint('loading picture, please wait')
	$.post('handles/next_pic.php',{id:id},function(data){
		$('#dummy').html(data)
	})
}

function go_previous_photo(id)
{
	show_hint('loading picture, please wait')
	$.post('handles/previous_pic.php',{id:id},function(data){
		$('#dummy').html(data)
	})
}

function mark_notif_read (id,type)
{
	$.post('handles/mark_as_read.php',{id:id,type:type,user:user,token:token},function (argument) {
		$('#dummy').html(argument);
	}).success(function(){
		header_refresh();
	})

}

function increment_numb(div)
{
	num = parseInt(document.getElementById(div).innerHTML)
	document.getElementById(div).innerHTML = num+1

}

function show_comments(type,div,id)
{
	show('id',div)
	show_hint('loading comments');
	$.post('handles/view_comments.php',{type:type,id:id,username:user,token:token},function(data){
		$('#'+div).html(data)
		document.getElementById(div).scrollTop = 20000;
	})
}

function check_length(arg,error,third)
{
	if(arg.length < 3)
	{
		error += third
	}
	else
	{
		error = error
	}
		return error;
}

function pic_dialog(id)
{
	show('id','dummy2')
	document.getElementById('dummy2').innerHTML = "<div class='back_big' style='padding:1%;padding-left:10%;padding-right:10%;overflow:auto'><span style='float:right;position:absolute;right:9%;z-index:2000' class='pointer hint--bottom hint--rounded hint--bounce hint--info invisible_span' data-hint='Close this' onclick='hide(\"id\",\"dummy2\")' style='z-index:1500'><img src='images/icons/close.png' height='25px'></span><div class='well row' style='height:100%;padding:0px;'>"+
	"<div class='col-sm-8' style='background:#000;height:100%;overflow:hidden;padding:5px;'><div id='p_pic' align='center' style='padding:0px;'><div align='center' style='padding-top:50%;'><img src='images/ajax-loader.gif'></div></div></div>"+
	"<div class='col-sm-1 hidden-xs' style='background:#f55;height:100%;padding:0px;overflow:auto;' align='center' id='p_previews'></div>"+
	"<div class='col-sm-3' id='p_comment_area' style='padding:1px'><div id='pic_heading' style='background:#fff;background:linear-gradient(#eee,#fff);padding:5px;height:20%;border-bottom:2px solid #ccc;'>loading <img src='images/s_l.gif'></div><div style='height:73%;overflow-y:auto;overflow-x:hidden'><div id='p_description'></div><div id='p_comments'></div></div><div id='p_make_comment' style='height:15%;padding-top:10px;'></div></div></div></div>";
	load_pic(id);

	load_ = document.onkeyup = function(event){
	if(event.keyCode == 27)
	{
		document.getElementsByClassName('back_big')[0].style.display='none'
		event.cancelBubble = true;
	}
}}


function load_pic(id)
{

	show_hint('retreiving picture...');
	$.post('handles/view_pic.php',{id:id},function(data){
		$('#dummy').html(data)
	}).error(function(){
		$('#p_pic').html("<h3>Network is slow or unavailable, but we will keep trying</h3>")
		load_pic(id)
	})
	load_pic_comments(id);load_pic_comments(id);
}

function load_pic_comments(id)
{
	$.post('handles/load_pic_comments.php',{id:id},function(data){
		$('#p_comments').html(data)
	}).error(function(){
		$('#p_comments').html("<h3>Network is slow or unavailable, but we will keep trying</h3>")
		load_pic_comments(id)
	})
}
function show_hint(val)
{
	$('#dummy').show();
	document.getElementById('dummy').innerHTML = "<div onclick=\"$('#dummy2_child').hide()\" align='center' style=' position:fixed;top:52px;"+
	"z-index:3200;width:100%;'><span id='dummy2_child' style='color:#ddd;background:rgba(0,0,0,0.65);"+
	"box-shadow:0 1px 5px #ccc; display:none;cursor:pointer;padding:10px' >"+
	"<span class='close' data-dismiss='alert'>&times;</span><i> "+val+
	"</i>&nbsp;&nbsp;&nbsp;</span></div>";
	$('#dummy2_child').slideDown(500)
	setTimeout(function(){
		$('#dummy2_child').slideUp(500)
	},7000)
}

function compare(e)
{
	$.post('handles/username_check.php',{username:e},function(data){
		$('#username_helper').html(data)
	})
}

function publish_post(data,username,token)
{
	error = ''
	error = check_length(data,error,"Seems Your post is empty, Post cannot be empty");
	if(error!='')
	{
		show_alert(error);
	}
	else
	{
		$('#loading').show(400)
		$.post('handles/make_post.php',{post:data,username:username,token:token},function(data){
			$('#dummy').html(data)
		}).complete(function(){
			$('#loading').hide(400)

		}).error(function(){
			show_alert("Sorry it seems an error occured during posting, this error is usually temporary. Please try again");
		}).success(function(){
			load_feeds()
		})

	}
}


function show_alert(data)
{
	document.getElementById('dummy').style.display='block'
	document.getElementById('dummy').innerHTML= "<div class='back_big' align='center'><div class='panel panel-danger'style='width:30%;"+
	"min-width:300px;margin-top:10%;box-shadow:0 5px 10px #333;' align='left'><div class='panel-heading'><b>Alert</b></div><div class='panel-body' align='center'>"+data+"</div>"+
	"<div class='panel-footer' align='right'><button class='btn btn-danger' onclick='hide(\"id\",\"dummy\")'>Okay</button></div></div></div>";
	document.onkeydown =
	function(event)
	{
		/**/

		if(event.keyCode==13 || event.keyCode==27)
		{
			hide('id','dummy');
			event.cancelBubble = true;
		}
	}


}

function show(element,arg)
{
	if(element == 'id')
	{
		document.getElementById(arg).style.display='block'
	}
}
function hide(element,arg)
{
	if(element == 'id')
	{
		document.getElementById(arg).style.display='none'
	}
}

function after_upload_com(data,id)
{
	$.post('handles/say_something.php',{user:user,comment:data,id:id,token:token},function(data){
		$('#dummy').html(data)
	}).error(function(){
		show_alert('<span class="glyphicon glyphicon-cloud"></span> Seems like the network is unavailable to add comment, anyway your picture has been uploaded successfully, but comment not added!!')
	})
}

function login(username,password)
{
	$('#loading').show(400);
	$.post('handles/login.php',{username:username,password:password},function(data){
		$('#dummy').html(data);
	}).error(function(){show_alert('Temporarily unable to login, please try again latter')}).complete(function(){
		$('#loading').hide(400);
	})
}

function signup(name,username,password,gender,phone)
{

	error = ''
	error = check_length(name,error," <b>Name</b><br />")
	error = check_length(username,error,"<b>Username</b><br />")
	error = check_length(phone,error,"<b>Phone Number</b><br />")
	error = check_length(password,error,"<b>Password</b><br />")
	if(error == '')
	{
		document.getElementById('continue_reg').style.display='none'
		$('.modal-footer').slideUp(800)
		document.getElementById('regplace').innerHTML="<div class='alert alert-info'>Processing, please wait. . .</div>"
		$.post('handles/new_register.php',{name:name,username:username,phone:phone,
			gender:gender,password:password},function(data){
			$('#regplace').html(data)
		}).error(function(){
			$('#continue_reg').slideDown(100)
			$('.modal-footer').slideDown(1000)
			document.getElementById('regplace').innerHTML="<div class='alert alert-warning'  id='helper'><span class='close' "+
			"onclick='dismiss(\"id\",\"helper\")'>&times;</span>Oh dear!.. We are having problems "+
			"connecting to the internet, please check your internet connection and try again</div>"
		})
	}
	else
	{
		document.getElementById('regplace').innerHTML = "<div class='alert alert-danger' id='errors'><span class='close' onclick='dismiss(\"id\",\"errors\")'>&times;</span>Sorry, There was an error in your registration Please fill <br />"+error+" Fields properly</div>";
	}
}

function dismiss(type,name)
{
	if(type=='id')
	{
		document.getElementById(name).style.display='none'
	}
}

function rdr(args)
{
	window.location=args
}

function load_on_scroll(div)
{
	window.onscroll = load_on_scroll_2;

	function load_on_scroll_2()
	{
		if($(document).height()-$(window).scrollTop()<1200)
		{
			$('#'+div).trigger('click');
		}
	}
}

	function settings_set(type,data,item)
	{
		$.post('handles/settings.php',{data:data,user:user,token:token,type:type},function(data){
			$('#dummy').html(data);
		}).success(function(){
			if(type == 'level' || type=='department')
			{
				item.style.background='#080';

			}
		})
	}
	$('.ttip').tooltip();

	function follow(to_follow,type,elem)
	{
		$.post('handles/follow.php',{user:user,token:token,to_follow:to_follow,type:type},function(data){
			$(elem).html(data)
		}).error(function(){
			$(elem).html('error')
		})
		$(elem).html('')
	}

	function toggle(type,id)
	{
		if(type=='id')
		{
			$('#'+id).slideToggle();
		}
	}

	function toggle_setting(data,item)
	{
		if(data=='phone_setting')
		{
			if(phone_setting==1)
			{
				phone_setting=0
				item.setAttribute('class','ion-toggle text-danger pointer');
			}
			else
			{
				phone_setting=1
				item.setAttribute('class','ion-toggle-filled text-success pointer');
			}
		}

		else if(data=='email_setting')
		{
			if(email_setting==1)
			{
				email_setting=0
				item.setAttribute('class','ion-toggle text-danger pointer');
			}
			else
			{
				email_setting=1
				item.setAttribute('class','ion-toggle-filled text-success pointer');
			}
		}
	}

	function change_password(old,neww,repeat)
	{
		$.post('handles/settings.php',{old:old,neww:neww,repeat:repeat,type:'password',user:user,token:token},function(data){
			$('#dummy').html(data)
		})
	}
//some readily available stuff (stupid naming conventions shaa)

	function send_message_q(messageid,text,append,textarea,wia)
	{
		document.getElementById(textarea).value=''
		if(text.length<1)
		{
			document.getElementById(textarea).value=''
		}
		else
		{
			$.post('handles/send_message.php',{token:token,user:user,text:text,messageid:messageid},function(data){
				$('#dummy').html(data)
			}).error(function(){
				$('#'+append).html('')
				send_message_q(messageid,text)
				document.getElementById(textarea).value=text
			}).success(function(){
				$('#'+textarea).val('')
			}).complete(function(){

			})

			document.getElementById(append).innerHTML = document.getElementById(append).innerHTML+"<div style='background:#ddd;margin-left:0%;text-align:right;max-width:100%;word-wrap:break-word;padding:5px;margin:1px;border-radius:0px; $style'>"+
		"<a href='#'><img src='images/no_pic.png' height='20px' width='20px' style='border-radius:10px'>"+
		"Me</a><br />"+
				text +"<br /><small class='text-muted' style='font-size:0.75em;font-family:courier new'>just now</small>"+
			"</div>"
		}
		document.getElementById(wia).scrollTop=200000
	}

function make_ppic(id,file)
	{
		$.post('handles/make_ppic.php',{id:id,file:file,username:user,token:token},function(data){
			$('#dummy').html(data)
		})
	}


	function click_on_enter(event,object)
	{
		if(event.keyCode==13)
		{
			$('#'+object).trigger('click');

		}
	}

	function delete_pic(id,text)
	{
		alertify.confirm(text,function(e){
			if(e==true)
			{
				$.post('handles/delete_pic.php',{id:id,user:user,token:token},function(data){

				})
			}
		});
		return false;
	}

	function header_refresh()
		{
			$.post('handles/run_function.php',{type:'messages',user:user,token:token},function(data){
				$('#header_items').html(data)
			})
			$.post('handles/run_function.php',{type:'show_messages',user:user,token:token},function(data){
				$('#header_message_place').html(data)
			})
			$.post('handles/run_function.php',{type:'notifications',user:user,token:token},function(data){
				$('#header_new_notifications').html(data)
			})
			$.post('handles/run_function.php',{type:'show_notifications',user:user,token:token},function(data){
				$('#header_notification_place').html(data)
			})
		}


	function init_chat(userr)
	{
		$('#dummy2').show(400)
		to_use = userr
		document.getElementById('dummy2').innerHTML= "<div style='height:100%;width:100%;position:fixed;background:rgba(0,0,0,0.5);z-index:2001' id='this_chat'><div  style='position:fixed;left:30%;top:10%;z-index:2000;width:40%;min-width:200px'>"+
		"<span class='close' onclick='$(\"#this_chat\").hide(400)'>&times;</span>"+
		"<div class='panel panel-default' style='box-shadow:0 0 19px #333;border:1px solid #333;'><div class='panel-body'><table width='100%'><tr><td><textarea id='dynamic_new_message' class='no-resize' "+
		"placeholder='say hello to "+userr+"..' style='outline:none;width:100%;border:0px solid'></textarea></td>"+
		"<td><i class='icon ion-paper-airplane text-muted pointer' style='font-size:2em' onclick='new_chat(to_use,$(\"#dynamic_new_message\").val())'></i></td></tr></table></div></div></div></div>"
	}

	function new_chat(userrr,message)
	{
		show_hint('sending message');
		$.post('handles/new_chat.php',{person:userrr,username:user,token:token,message:message},function(data){
			$('#dummy2').html(data)
		})
	}



	function create_channel() {
		show('id','dummy2')
		html = "<div class='back_big'><div class='well' style='width:50%;min-width:300px;margin:auto;margin-top:10%'>"+
		"<span class='pull-right close' onclick='hide(\"id\",\"dummy2\")'>&times;</span> <center>"+
		"<i class='ion-settings text-danger' style='font-size:3em'></i></center>"+
		"<br />A Channel is a group of people with common interests, "+
		" users can subscribe to them by simply following them. <br /> You will be prompted to upload the channel's picture after now"+
		"<br /><form action='create_group.php' method='post'>"+
		"<input type='text' class='form-control' name='name' placeholder='Name of the channel'><br />"+
		"<input type='text' class='form-control' name='info' placeholder='More info anout the channel'><br />"+
		"<button class='btn btn-danger form-control'>Create</button></form></div></div>"
		$('#dummy2').html(html)
	}

function delete_post(id,type,div)
{
	alertify.confirm('Sure To delete Post?',function(e)
	{
		if(e==true)
		{
			if(type==='picture')
			{
				$.post('handles/delete_pic.php',{id:id,user:user,token:token},function(data){

				}).success(function(){
					$('#'+div).slideUp(1000)
				})
			}
			else if (type === 'status')
			{
					del = deleteStatusPost(id,user,token,type)
					$('#'+div).slideUp(1000)
					destroy_node('dynamic_post_dialog')
					if(del == false)
					{
						$('#'+div).fadeIn(1000)
					}
			}
		}

		destroy_node('dynamic_post_dialog')
	})
	return false
}

function deleteStatusPost(id,user,token,type)
{
	$.post('handles/delete.php',{user:user,id:id,token:token,type:type},function(data){

	}).error(function(){
		alert('booo')
		return false
	});
}

/*function confirm_dialog(text)
{
	var dialog  = document.createElement("DIV")
	dialog.setAttribute('class','back_big')
	dialog.setAttribute('id','dynamic_dialog')
	var childDiv = document.createElement("div")
	var textNode = document.createTextNode(text)
	var closeButton = document.createElement("SPAN")
	var buttonsDiv = document.createElement("DIV")
	childDiv.setAttribute('class','well my_dialog')
	childDiv.appendChild(textNode)
	childDiv.appendChild(closeButton)
	childDiv.appendChild(buttonsDiv)
	closeButton.innerHTML = "<span class='pull-right close' onclick=\"destroy_node('dynamic_dialog')\">&times;</span>"
	buttonsDiv.innerHTML = "&nbsp;<br /><span class='pull-right'>"+
	"<button class='btn btn-danger'>No</button> <button class='btn btn-success'>Yes</button></span>"
	dialog.appendChild(childDiv)
	document.body.appendChild(dialog)
}*/

function destroy_node(node)
{
	elem = document.getElementById(node)
	elem.parentNode.removeChild(elem)
}

function show_post_options(id,type,div)
{
	mainDiv = document.createElement("div")
	mainDiv.setAttribute('class','back_big')
	mainDiv.setAttribute('id','dynamic_post_dialog')
	dialog = document.createElement('div')
	dialog.setAttribute('class','well my_dialog')
	childDiv = document.createElement('div')
	childDiv.innerHTML ="<b>Quick Menu</b> <br /><ul class='nav nav-stacked navbar-default quick_menu_list'>"+
	"<li><a onclick='edit_post("+id+",\""+type+"\")'><span class='ion-android-create'></span> Edit Post</a></li>"+
	"<li><a onclick='hide_post("+id+",\""+type+"\",\""+div+"\")'><i class='ion-eye-disabled'></i> Hide Post</a></li>"+
	"<li><a onclick='delete_post("+id+",\""+type+"\",\""+div+"\")'><i class='ion-ios-trash'></i> Delete Post</a></li>"+
	"</ul><button class='btn btn-danger form-control' onclick=\"destroy_node('dynamic_post_dialog')\">Close Menu</button>"
	dialog.appendChild(childDiv)
	mainDiv.appendChild(dialog)
	document.body.appendChild(mainDiv)
}

function hide_post(id,type,the_div)
{
	destroy_node('dynamic_post_dialog')
	$('#'+the_div).slideUp(1000)
}

function final_edit(id,type,textarea)
{
	$.post('handles/final_edit.php',{text:document.getElementById(textarea).value,id:id,type:type},function(data){
		if(data=='success')
		{
			window.location.reload()
		}
		else
		{
			show_alert(data)
			destroy_node('dynamic_post_dialog')
		}
	})
}

function edit_post(id,type)
{
	destroy_node('dynamic_post_dialog')
	mainDiv = document.createElement("div")
	mainDiv.setAttribute('class','back_big')
	mainDiv.setAttribute('id','dynamic_post_dialog')
	mainDiv.innerHTML = "<div class='well my_dialog' style='width:70%;min-width:250px;' "+
	"align='center'><b>Loading Post</b><br /><img src='images/hh.gif'></div>"
	document.body.appendChild(mainDiv)
	$.post('handles/get_post.php',{id:id,type:type,user:user,token:token},function(){

	}).success(function(data){
		if(data=='error')
		{
			destroy_node('dynamic_post_dialog')
			show_alert('There was an error editing this post')
		}
		else
		{
			mainDiv.getElementsByClassName('well')[0].innerHTML = "<button class='close' onclick=\"destroy_node('dynamic_post_dialog')\">&times;</button><b>Edit Post</b><br /><textarea id='edit_textbox' class='form-control'>"+data+
			"</textarea><button onclick=\"final_edit("+id+",'"+type+"','edit_textbox')\" class='btn form-control btn-danger'>Edit</button>"
		}
	})
}

function check_number(number)
{
	$('#reg_loading').slideDown()
	$('#reg_main_body').hide()
	$.post('handles/check_phone.php',{number:number.value},function(data){

	}).success(function(data){
		if(data=='taken')
		{
			$.post('handles/get_reg_details.php',{number:number.value},function(data){

			}).success(function(data){
				$('#reg_main_body').show()
				$('#reg_main_body').html(data)
				$('#reg_loading').hide()
			})
		}
		else if(data=='free')
		{
			document.getElementById('continue_reg').getElementsByTagName('input')[0].value = number.value;
			$('#continue_reg').slideDown()
			$('#reg_loading').hide()
		}
		else
		{
			show_alert('There was a problem with Your input, please check and try again')
			$('#reg_loading').hide()
			$('#reg_main_body').slideDown()
		}
	}).error(function(){
		$('#reg_loading').hide()
		$('#reg_main_body').slideDown()

	})
}

function activate_user(uid,username,password)
{
	$('#reg_loading').show()
	$('#reg_main_body').slideUp()
	$.post('handles/activate_user.php',{id:uid,username:username,password:password},function(data){

	}).success(function(data){
		$('#reg_loading').html(data)
	}).error(function(){
			$('#reg_loading').slideUp()
			$('#reg_main_body').show()
	})
}

function wowed_on(field)
{
	$.post('handles/wowed_on.php',{field:field},function(){
	})
}


function leave_channel(channel_id,user)
{
	
	var j = confirm('Sure to Leave this Channel ?');
	if(j)
	{
		follow(channel_id,'group','');
		window.location.reload()
	}
}

$('.my_popover_close').click(function(){
	for(var i = 0;i<document.getElementsByClassName('my_popover').length;i++)
	{
		document.getElementsByClassName('my_popover')[i].style.display = 'none'
	}
})
