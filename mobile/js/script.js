function like(type,id,elem)
{
	$.post('handles/like.php',{type:type,id:id},function(data){
		$('#'+elem).html(data)
		window.location.reload()
	})
}

function comment(com,id)
{
	$.post('handles/comment_pic.php',{comment:com,id:id},function(data){

	}).success(function(){
		window.location.reload()
	}).error(function(){
		comment(com,id)
	})
}

function follow(to_follow,div,type)
{
	div.innerHTML = '. . .'
	$.post('../handles/follow.php',{user:user,token:token,to_follow:to_follow,type:type},function(data){
		div.innerHTML = data
	})
}

function show_alert(text)
{
	alert(text)
}
