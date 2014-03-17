<?php
	
	//session_start();


	function getLoginData($userid, $password, $token){
	
		$db1 = new DB();
		//what does this do?
		//I do data stuff...
		
		//if I hit a db with actual chat in it, then json_encode()
		//the result, it will look something like this:
		$logged = 1;
		
		$sql = "update userinfo set logged = ? where userid = ?";
		if ($stmt = $db1->mysqli->prepare($sql)) {
			$stmt->bind_param('is', $logged, $userid);
			$stmt->execute();
			$updatedRows = $stmt->affected_rows;
			$stmt->close();
		}
		else
			return "Unable to prepare statement";
		
		$password = sha1($password);
		
		$sql = "SELECT password FROM userinfo where userid = ?";
		
		if($stmt = $db1->mysqli->prepare($sql)){
			$stmt->bind_param('s', $userid);
			$stmt->execute();
			$stmt->bind_result($storedPassword);
			$stmt->fetch();
			$stmt->close();
		}
		
		$users = array();
		
		if($password == $storedPassword){
			$users[]=1;
			$_SESSION['userid'] = $userid; // store session data
			
		}else{
			$users[]=0;
		}
			
		return $users;
	}
	
	function insertUserData($firstName, $LastName, $emailid, $userid, $password){
		$db = new DB();
		
		$sql = "SELECT userId FROM userinfo where userid = ?";

		if($stmt = $db->mysqli->prepare($sql)){
			$stmt->bind_param('s', $userid);
			$stmt->execute();
			$stmt->store_result();
		       $num_rows = $stmt->num_rows();
		       $stmt->close();
		        if ($num_rows > 0)
		           $userid1 = "1";
		        else
		           $userid1 = "0";
		}
		
		$users = array();
		$users[] = 0;

		if($userid1=="0"){

			$timestamp =  date('m/d/y');
		
			$ip = $_SERVER['REMOTE_ADDR'];
		
			$token = $db->getToken($userid, $ip, $timestamp);
		
			$password = sha1($password);
		
			$sql = "insert into UserInfo(userId, Password, Firstname, Lastname, Email, Timestamp, Token, logged) VALUES ('$userid', '$password', '$firstName', '$LastName', '$emailid', '$timestamp', '$token', '0')";
				
			if($db->query($sql))
				$users[] = 2;
		}	
		
		return $users;
	}
	
	function signOutUser($userid)
	{
		session_unset();
		//session_destroy();
		$db1 = new DB();
		
		$update1 = "update UserInfo set logged='0' where userid = '$userid'";
		
		$result1 = $db1->query($update1);
		
		$update = "delete from challenge where challengedby = '$userid' or challengedto = '$userid'";
		
		$result2 = $db1->query($update);
		
		$delete = "delete from messages where userid = '$userid'";
		
		$result3 = $db1->query($delete);
		
		$users = array();
		if($result1==true&&$result2==true&&$result3==true)
			$users[] = 1;
		else
			$users[] = 0;
			
		return $users;
	}

?>