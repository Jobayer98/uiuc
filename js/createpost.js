
String.prototype.isMatch = function(s){
   return this.match(s)!==null
}

function Cancel(){
	if(confirm('Are You Sure?') == true){parent.hideIframe();}
}



function PostNow(id){
	let textData = tinyMCE.activeEditor.getContent();
	console.log(textData);
	console.log(id);
	if(textData.split(' ').length >= 5){
		DoPostText(id, textData);	
	}else{
		if(textData.length <= 1){
			document.getElementById('notific').innerHTML = "The Post field is Empty.";
		}else{	
			document.getElementById('notific').innerHTML = "Minimum 5 Words is Required.";
		}
	}
}


function DoPostText(groupid, textdata){
	const url = 'ajax.php?data=DoPostText';
	let data2 = {
		"postText" : textdata
		};
		
	let xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", url, true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
	xmlhttp.onreadystatechange=function() {
		if (this.readyState == 4 && this.status == 200){
			console.log(this.responseText);
			let RetJson = JSON.parse(this.responseText);
			if(RetJson.posted.isMatch('true')){
				console.log(RetJson.posted);
				console.log(this.responseText);
				console.log(JSON.stringify(this.responseText));
				parent.hideIframe();
			}
		} else{
			return 0;
		}
	}
	let sendJson = JSON.stringify(data2);
	console.log(sendJson);
	console.log(groupid);
	xmlhttp.send('submit&groupID='+groupid+'&post_data='+sendJson);
}



function UpdatePost(id){
	
	var textData = tinyMCE.activeEditor.getContent();
	console.log(textData);
	console.log(id);
	console.log('---------------------');
	
	if(textData.split(' ').length >= 5){
		//All okay, Do post
		
		const url = 'ajax.php?data=EditPostText';
		let data2 = {
			"postID" : id,
			"postText" : textData
			};
			
		let xmlhttp = new XMLHttpRequest();
		xmlhttp.open("POST", url, true);
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		
		xmlhttp.onreadystatechange=function() {
			if (this.readyState == 4 && this.status == 200){
				console.log(this.responseText);
				let RetJson = JSON.parse(this.responseText);
				if(RetJson.posted.isMatch('true')){
					console.log(RetJson.posted);
					console.log(this.responseText);
					parent.document.getElementById('postcontent'+id).innerHTML = RetJson.content;
					parent.hideIframe();
				}
			} else{
				return 0;
			}
		}
		let sendJson = JSON.stringify(data2);
		console.log(sendJson);
		xmlhttp.send('submit&post_data='+sendJson);
		
	}else{
		if(textData.length <= 1){
			document.getElementById('notific').innerHTML = "The Post field is Empty.";
		}else{	
			document.getElementById('notific').innerHTML = "Minimum 5 Words is Required.";
		}
	}
}


	
	
