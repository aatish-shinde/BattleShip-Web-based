<?php 
session_name("Battle");
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['userid'] == null) {
	header ("Location: index.php");
}else {
	if($_SESSION['userid']==true){
	
	require_once('common/gameFunc.php');
	
	deleteFromPlayerGame();
	deleteFromShipOrientation();
	deleteFromShips();
	deleteFromPlayerShipsPos();
	deleteFromGame();
	deleteFromAlert();
	
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Web Chat</title>
<style type="text/css">

	#welcome {
		position:absolute; left: 600px; top: 40px; padding: 5px; width: 500px;
		
		color:#DDDDDD;
		font-family:"Lucida Grande";
		font-size:30px;
		font-weight:bold;
		
	}

	</style>
	
<link rel="stylesheet" type="text/css" href="css/chat.css" />


 <script src="scripts/jquery-1.6.2.min.js"></script>
 <script src="scripts/jquery-ui-1.8.16.custom.min.js"></script>


<script src="scripts/login_script.js" type="text/javascript"></script>
<script src="scripts/chat.js" type="text/javascript"></script>

<script type="text/javascript">
var player1="";
var player2="";
var userid = '<?php echo $_SESSION['userid']; ?>';

$(function() {
	$('#loginForm').hide();
	$('#submitForm').show();
	$('#logout').show();
			
	var id = " "+userid;
	$('#loggedUser').show().html(id);
});

$(document).ready(function(){

	$('#chatText').click(function() {
		document.enter.chatText.value = "";
	});
		
	$("#logoutButton").live("click", function(){
		
		var r=confirm("Are you sure, you want to logout");
		if(r==true){
			dataString = {method:'logOut', type:'out', a:'login', u:userid};
		
			$.ajax({
				type : "POST",
				url : "mid.php",
				data : dataString,
				cache : false,
				dataType : 'json',
				success : function(resp){
					
					if(resp[0] == 1){
						
						window.location = "index.php";
					}
				}
			});
		}
	});
		
	$('#dialog1').dialog({
		autoOpen: false,
		width: 300
	});

	
});

</script>
	
</head>

<body bgcolor="#797F7F">

<div id="welcome">Welcome to BattleShip game Chat</div>

<div id="chatContainer">
	
    <div id="chatTopBar" class="rounded">
		<div>Welcome<span id="loggedUser"></span>!</div>
		<div id="logoutButton" class="rounded" align="right" valign="top">SIGN OUT</div>
		<div class="error"></div>
	</div>
	
    <div id="chatLineHolder" class="rounded">
	
	</div>
    
    <div id="chatUsers" class="rounded">
		<div>online</div>
	</div>
    <div id="chatBottomBar" class="rounded">
    	<div class="tip"></div>
         
        <form id="submitForm" name="enter" class="newMessage" method="POST">
			<div>
            <div><textarea id="chatText" name="chatText"  class="rounded">Enter your message here</textarea></div>
			<div class="send"><input type="button" value="SEND"></input></div>
			</div>
        </form>
        
    </div>
    
	<div style="height: 200px; min-height: 109px; width: auto;" class="ui-dialog-content ui-widget-content" id="dialog1">
		<p></p>
	</div>
</div>

<input id="hid_user" type="hidden" value= "<?php echo($_SESSION['userid']); ?>"></input>
<input id="touser" type="hidden" value= ""></input>


</body>
</html>

<?php } 

}?>