String.prototype.isMatch = function(s){
   return this.match(s)!==null
}

function DoCommentListener(event, postid, textdata){
	if (event.keyCode == 13) {
		DoComment(postid, textdata);
	}
}

function DoComment(postid, textdata){
	var url = 'ajax.php?data=DoComment';
	var CommentCounterCont = 'commentCounter'+postid;
	var data2 = {
		"postID" : postid,
		"commentText" : textdata
		};
		
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", url, true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
	xmlhttp.onreadystatechange=function() {
		if (this.readyState == 4 && this.status == 200) {
			console.log(this.responseText);
			let RetJson = JSON.parse(this.responseText);
			if(RetJson.commented.isMatch('true')){
				//Comment Posted
				//
				
				//আমার রাত জাগা তারা
				//তোমার আকাশ ছোঁয়া বাড়ি
				//আমি পাই না ছুঁতে তোমায়
				//আমার একলা লাগে ভারী
				
				document.getElementById('commentBox'+postid).value = '';
				let newFirstElement = 	document.createElement('div');
				newFirstElement.classList.add("commentsConta");
				
				let HTMLData = '<a style="text-decoration: none;" href="profile.php?id='+RetJson.commenterID+'">'+
								'<span style="font-size:14px; font-weight: bold; margin-bottom: 3px; position: absolute;">'+
								RetJson.commenterName+'</span></br>'+
								'<small style="border-bottom: 1px dotted white; font-size: 11px;position: absolute;">'+RetJson.instituteName+'</small>'+
								'</a></br>'+RetJson.commentText+'</br>';
					
					
				document.getElementById('commentContainer'+postid).insertAdjacentElement('afterbegin', newFirstElement);
				document.getElementById('commentContainer'+postid).children[0].innerHTML = HTMLData;
				
				if(parseInt(RetJson.CommentCount) == 0){
					document.getElementById(CommentCounterCont).innerHTML = 'No Comments';
				} else {
					document.getElementById(CommentCounterCont).innerHTML = RetJson.CommentCount+' Comments';				
				}
			}
		} else{
			return 0;
		}
	}
	var sendJson = JSON.stringify(data2);
	console.log(sendJson);
	xmlhttp.send('submit&post_data='+sendJson);
}



function refreshComments(postid){
	return 0;
}

function likePost(postid){
	var LikeTxtId = 'likecont'+postid;
	var LikeCounterCont = 'likeCounter'+postid;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if(this.readyState == 4 && this.status == 200) {
			var RetJson = JSON.parse(this.responseText);
			if(RetJson.liked.isMatch('true')){
				document.getElementById(LikeTxtId).innerHTML = '<i class="fa-solid fa-thumbs-down"></i> Unlike';
			}
			if(RetJson.liked.isMatch('false')){
				document.getElementById(LikeTxtId).innerHTML = '<i class="fa-solid fa-thumbs-up"></i> Like';
			}
			if(parseInt(RetJson.LikeCount) == 0){
				document.getElementById(LikeCounterCont).innerHTML = 'Be the first one to Like';
			} else {
				document.getElementById(LikeCounterCont).innerHTML = RetJson.LikeCount+' Likes';				
			}
		}
	}
	xmlhttp.open("GET","ajax.php?data=like&post_id="+postid, true);
	xmlhttp.send();		
}


function CopyPostLink(postid, pageurl){
	let ServerRoot = pageurl.slice(0,location.href.lastIndexOf("/"));
	let Link = ServerRoot+'/post.php?id='+postid;
	let Element = 'copyBtn'+postid;
	navigator.clipboard.writeText(Link);
	document.getElementById(Element).innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
	setTimeout(function(){
		document.getElementById(Element).innerHTML = '<i class="fa-solid fa-copy"></i> Copy Link';
	}, 2000);
}


