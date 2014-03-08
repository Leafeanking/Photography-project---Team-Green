<script>
	function hide_element(elm){
		var element = document.getElementById(elm);
		if(typeof(element) != 'undefined' && element != null){
			element.style.display='none';
		}
	}
	
	function show_element(elm){
		var element = document.getElementById(elm);
		if(typeof(element) != 'undefined' && element != null){
			element.style.display='block';
		}
	}
	
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
		show_element('shadow');
		request_comments(photoId);
		document.getElementById('selectedImage').innerHTML=document.getElementById(photoId).innerHTML
	}
	
	function show_create_project(){
		show_element('create_project');
		show_element('shadow');	
	}
	
	
	function hide_all(){
		hide_element('edit_classes');
		hide_element('create_project');
		hide_element('image_and_comments');
		document.getElementById('shadow').style.display = 'none';
	}
	
	function show_next_create_question_field(next){
		show_element('project_create_question_'+next);	
	}
	
	function show_edit_classes(){
		show_element('edit_classes');
		show_element('shadow');
	}
	
	function switch_to_edit_classes(){
		hide_all();
		show_element('edit_classes');
		show_element('shadow');
	}
</script>


<div id='shadow' onclick='hide_all()'></div>