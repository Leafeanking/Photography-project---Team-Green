<script>
	
	function request_comments(photoId){
		var comments = document.getElementById("comments_ratings");
		var ajx = new XMLHttpRequest();
		ajx.onreadystatechange = function(){
			comments.innerHTML = ajx.responseText;
		}
		ajx.open("GET", "getcomments.php?imageId="+photoId,true);
		ajx.send();
	}
	
	function manage_image_click(photoId){
		document.getElementById('image_and_comments').style.display = 'inline';
		document.getElementById('shadow').style.display='block';
		request_comments(photoId);
		document.getElementById('selectedImage').innerHTML=document.getElementById(photoId).innerHTML
	}
	
	function hide_image_and_comments(){
		document.getElementById('image_and_comments').style.display = 'none';
		document.getElementById('shadow').style.display = 'none';
	}
</script>


<div id='shadow' onclick='hide_image_and_comments()'></div>