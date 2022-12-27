function editPost(postid){
	aurnaIframe('editpost.php?id='+postid);
}

function refreshComments(postid){
	return 0;
}

function deletePost(postid){
	if(confirm('Are You Sure want to Delete this post?') == true){
		var PostContainer = 'PostCont'+postid;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200) {
				var RetJson = JSON.parse(this.responseText);
				if(RetJson.deleted.isMatch('true')){
					document.getElementById(PostContainer).remove();
				}
			}
		}
		xmlhttp.open("GET","ajax.php?data=deletePost&post_id="+postid, true);
		xmlhttp.send();		
	}
}
	
	
	
