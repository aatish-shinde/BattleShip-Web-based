function signin()
{
	var u = $("#u").val();
	var p = $("#p").val();
	
	var u_count = u.length;
	
	if(u=="") {
		$("#u").css("border-color","#606060");
		$("#un").css("color", "red");
		$(".error").show().html("Please enter you username.");
					
	}else if (u_count > 8){
		$("#u").css("border-color", "#606060");
		$("un").css("color", "#606060");
		$(".error").show().html("UserName cannot be more than 8 characters.");
					
	}else if (p==""){
		
		$(".error").show().html("Please enter you password.");
		$("#p").css("border-color","red");
		$("#up").css("color", "red");
		
	}else {
		
		dataString = {method:'getLogin', type:'signin', a:'login', u:u, p:p};
		$.ajax({
			type : "POST",
			url : "mid.php",
			data : dataString,
			cache : false,
			dataType : 'json',
			success : function(html){
				if(html[0]=="0") {
					$(".error").show().html("The email or password you entered is incorrect");
					$("#p, #u").css("border-color","red");
					$("#up, #un").css("color","red");
				}else if(html[0]== "1") {
					$(".error").fadeOut(5000);
					$(".center").animate({
						opacity : 0.55,
						height : 'toggle'
					},2000,function(){
						//$(".done").fadeIn(1000).html("Welcome, "+u);
							
							setTimeout;
							window.location = "common/redirect.php?userid="+u;
					});
			
				}
			}
		});
	}
}

$(document).ready(function(){
	

	$(".center").animate({
		opacity:100.0,
		height:'toggle'
		}, 3000, function(){
	});


	$(".register_b_btn").click(function(){
		$("#se").val("");
		$("#su").val("");
		$("#sp").val("");
		$(".errorReg").hide();
		$(".doneReg").hide();

		//centering with css
		centerPopup();
		//load popup
		loadPopup();
	});
	
	$("#popupContactClose").click(function(){
		disablePopup();
	});
	
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});
	
	
	$(".input").keypress(function (e) {
		
	  if(e.keyCode=='13'){ //Keycode for "Return"
		 signin();
	  }
	});

	$(".sign_b_btn").live("click", function(){
		signin();
	});
	
	setTimeout(function(){
			
	},5000);
	
	$(".signup").live("click", function(){
		var su = $("#su").val();
		var se = $("#se").val();
		var sp = $("#sp").val();
		var sf = $("#sf").val();
		var sn = $("#sn").val();
		
		var u_count = su.length;
		if (se==""){
			$("#se").css("border-color", "#606060");
			$(".errorReg").show().html("Please enter you Email Id.");
			
		}else if (su==""){
			$("#su").css("border-color", "#606060");
			$(".errorReg").show().html("Please enter new User ID.");
			
		}else if (sp==""){
			$("#sp").css("border-color", "#606060");
			$(".errorReg").show().html("Please enter new password.");

		}else if (u_count > 8){
			$("#u").css("border-color", "#606060");
			$("un").css("color", "#606060");
			$(".error").show().html("UserID cannot be more than 8 characters.");
						
		}else {
			
			//alert(su);
			dataString = {method:'insertUser', type:'register', a:'login', u:su, p:sp, e:se, f:sf, l:sn };
			
			$.ajax({
				type : "POST",
				url : "mid.php",
				data : dataString,
				cache : false,
				dataType : 'json',
				success : function(html){
					//alert(html);
					
					if(html[0]==0){
						$("#se").css("border-color", "#606060");
						$(".doneReg").show().html("The Userid is already present.");
					}
					else if(html[0]==2) {
					
						$(".errorReg").fadeOut(200);
						$(".doneReg").fadeIn(3000).html("Awesome !! "+sf + ", You will be redirected to login page... ");
							setTimeout;
							window.location = "index.php";
					}

				}
				
			});
		}
	});		
		
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
			success: function(html) {
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
	
	$("#u").focus();
	
});

