
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
		var data = document.getElementById('selectedImage')
		data.innerHTML = "<h2>Please Wait while we gather Metadata</h2>";
		var ajx = new XMLHttpRequest();
		ajx.onreadystatechange = function(){
			if(ajx.readyState == 4){
			var meta = ajx.responseText;
			data.innerHTML=document.getElementById(photoId).innerHTML + 
			"<br/><div style='float:left'>"+meta+"</div>"+
			"<form action='index.php' method='POST'><input type='hidden' name='imageID' value='"+photoId+"'><input type='submit' name='delete_image' value='Delete Image' onclick='return "+'confirm("Are you sure you want to delete this image?")'+"'></form>";	
			}
		}
		ajx.open("GET","get_meta_data.php?imageID="+photoId+"&droponly",true);
		ajx.send();
	}
	
	function show_create_project(){
		show_element('edit_project');
		show_element('shadow');	
	}
	
	
	function hide_all(){
		//Add all elements that you want the shadow box to remove;
		hide_element('manage_images');
		hide_element('edit_classes');
		hide_element('edit_project');
		hide_element('image_and_comments');
		hide_element('create_session');
		hide_element('update_avatar');
		hide_element('update_logo');
		document.getElementById('shadow').style.display = 'none';
	}
	
	var QUESTION = 2;
	function show_next_create_question_field(){
		show_element('project_create_question_'+QUESTION);
		QUESTION = QUESTION +1;
	}
	
	function projects_select_multiple_classes(){
		show_element('project_choose_multiple');
		hide_element('projects_choose_single');
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
	
	function show_manage_images(){
		show_element('manage_images');
		show_element('shadow');
	}
	
	function show_update_avatar(){
		show_element('update_avatar');
		show_element('shadow');
	}
	
	function show_update_logo(){
		show_element('update_logo');
		show_element('shadow');
	}
	
	function show_students_table(){
		var view = document.getElementById('students_in_class_table');
		
		var sel = document.getElementById('students_in_class_selector');
		var clas = sel.options[sel.selectedIndex].value;
		
		var ajx = new XMLHttpRequest();
		ajx.onreadystatechange = function(){
			view.innerHTML = ajx.responseText;
		}
		ajx.open("GET", "class_students_table.php?class="+clas,true);
		ajx.send();
	}
	
	function show_projects_by_class_dropdown(){
		//use of function on student page.
		var view = document.getElementById('projects_uploadable_to');
		//use of function on teacher page. 
		if(view == undefined){
			view = document.getElementById('class_projects_session');
		}
		var sel = document.getElementById('multi-class-selector');
		var clas = sel.options[sel.selectedIndex].value;
		
		ajx = new XMLHttpRequest();
		ajx.onreadystatechange = function(){
			view.innerHTML = ajx.responseText;
		}
		ajx.open("GET","create_dropdown_of_projects.php?class="+clas,true);
		ajx.send();
	}
	