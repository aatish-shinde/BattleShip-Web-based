<?php

	function getChat(){
		
		require_once('bizData/chatData.php');
		
		return displayUsersMessages();
	}
	
	function getUsers($userid){
		require_once('bizData/chatData.php');

		return getOnlineUsers($userid);
	
	}
	
	
?>
