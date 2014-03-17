$(document).ready(function(){
	
	$('.send').click(function() {
	
		var username = $("#loggedUser").html();
		var message = $("#chatText").val();
		
		if (message == "" || message == "Enter your message here") {
			return false;
		}
		//alert(username);
		var dataString = 'username=' + username + '&message=' + message;
		
		$.ajax({
			type: "POST",
			url: "send_message.php",
			data: dataString,
			success: function() {
			//alert(html);
				document.getElementById("chatText").value = "";
			}
		});		
	});



	$('#quit').click(function() {
		byuser = document.getElementById("hid_user").value;
			
		dataString = {method:'inGame', type:'ischallenged', a:'game', u:byuser};
		
		$.ajax({
			type : "POST",
			url : "mid.php",
			data : data,
			cache : false,
			dataType : 'json',
			success : function(html){
				//alert(html);
				if(html[0] == 1){
					$("#start").show();
				}
			}
		});
	});

});




		
	