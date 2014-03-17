
function callGETPOST(callType, data, callbackfunction) {
    $.ajax({
        type:callType,
        data: data,
        url:"mid.php",
        dataType:'json',
        success:callbackfunction
    });
}



$(document).ready(function(){
	
	// Run the init method on document ready:
	chat.init();
	$("#start").hide();
		
});

var chat = {

	init : function(){
		
		(function getUsersTimeoutFunction(){
			chat.getUsers(getUsersTimeoutFunction);
		})();
		
		(function getChatTimeoutFunction(){
			chat.getChat(getChatTimeoutFunction);
		})();			
			
		(function getChallengeTimeoutFunction(){
			chat.isChallenged(getChallengeTimeoutFunction);
		})();		
		
		(function getChallengeAcceptedTimeOutFunction(){
			chat.isChallengeAccepted(getChallengeAcceptedTimeOutFunction);
		})();
	},
	
	isChallengeAccepted : function(callback){
		
		callGETPOST("GET", {method:'isAccepted', type:'game', a:'game', data:userid}, function(data){
		//alert(data.player1);
			if(data.player1 == userid){
				alert("Challenge accepted, Lets play !!");
				
				callGETPOST("POST", {method:'accepted', type:'game', a:'game', data:userid}, function(data1){
				//alert(data1);
					if(data1==1){
					
						window.open('battle.php?player1='+userid+'&player2='+data.player2);
					}
				});
				
			}
		});
		
		time = 5000;
		setTimeout(callback,time);
	
	},
	
	isChallenged : function(callback){
		
		player1 = userid;
		
		callGETPOST("POST", {method:'isChallenged', type:'ischallenged', a:'game', u:player1}, function(response){
			
			if(!response[0]==0){
				
				player2 = response[0];
				
				//alert("by :"+player2);
				var r=confirm('You have been Challenged by, '+player2);
				
				if(r==true){
				
					$("#start").show();
					
					//alert(touserid);
					//document.getElementById(player2).value = player2+"_ingame";
					
					//touserid = document.getElementById("hid_user").value;
					
					callGETPOST("POST", {method:'updateChallenge', type:'update', a:'game', to:player1, by:player2}, function(html){
						window.open('battle.php?player1='+player1+'&player2='+player2);
						//alert(html);
					});
				}
				else{
					callGETPOST("POST", {method:'deleteChallenge', type:'update', a:'game', to:player1, by:player2}, function(html){
						//alert(html);
					});
				}
								
			}
			
			
		});
		
		time = 5000;
		setTimeout(callback,time);
	},

	
	getUsers : function(callback){
	
		callGETPOST("GET", {method:'getUsers', type:'users', a:'chat', u:userid}, function(response){
			$('#chatUsers').html(response.join(''));
		});
		
		time = 2000;
		setTimeout(callback,time);
	},

	
	getChat : function(callback){
		
		callGETPOST("GET", {method:'getChat', type:'chat', a:'chat'}, function(response){
			$('#chatLineHolder').html(response.join(''));
		});
				
		time = 2000;
		setTimeout(callback,time);
	},
	
		
};


function challenge(byUser, toUser, toUserName){
	
		
	byuserid = document.getElementById("hid_user").value;

	if(toUserName == toUserName+"_ingame")
	{
		alert("Already in game, cannot challenge");
	}
	else{
	
	  var user = "#"+toUser;
		
		var r=confirm("Are you sure, you want to challenge, "+toUserName);
		
		$(document).ready(function(){
		
			if(r==true){
				
				//window.location.href = "chat.php";
				
				dataString = {method:'challenge', type:'challenge', a:'game', to:toUser, by:byuserid};
				
				callGETPOST("POST", {method:'challenge', type:'challenge', a:'game', to:toUser, by:byuserid}, function(response){
					
					if(response==1){
						//window.open('battle.php?player1='+byuserid+'&player2='+toUser);
					}else{
						alert("player is already in a game, you cannot challenge this player");
					}
					
				});
			}else
			{
				//window.location.href = "chat.php";
			}
		
		});
	}
}

setTimeout(function(){
				
},5000);


