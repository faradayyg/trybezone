function topic_thing(elem)
{
  document.getElementById('news_topic').innerHTML = elem.value
}

function news_thing(elem)
{
  document.getElementById('news_body').innerHTML = elem.value
}

function picture_thing(event,elem)
{
  var reader = new FileReader();
  reader.onload = function(){
    document.getElementById(elem).src = reader.result;
  }
  reader.readAsDataURL(event.target.files[0]);
}

function delete_blog_post(id)
{
	var j = confirm("Sure to delete this news? ")
	if(j)
	{
		$.post('handles/delete_blog_post.php',{id:id},function(data){
			alert(data)
		})	
		window.location.reload()
	}	
}

function hide_blog_post(id)
{
	var j = confirm("This news will no longer be visible")
	if(j)
	{
		$.post('handles/hide_blog_post.php',{id:id},function(data){
			alert(data)
		})	
		window.location.reload()
	}
}
