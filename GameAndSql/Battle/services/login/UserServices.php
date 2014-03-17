<?php

	require_once('bizData/UserData.php');

	function getLogin($userid, $password, $token){
	
		return getLoginData($userid, $password, $token);
	}
	
	function insertUser($fname, $lname, $email, $userid, $password){
		
		return insertUserData($fname, $lname, $email, $userid, $password);
	}
	
	function logOut($userid){
		return signOutUser($userid);
	}
?>