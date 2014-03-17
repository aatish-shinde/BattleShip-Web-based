<?php
/*
	function insertChatData($userid, $firstName, $LastName, $password, $emailid, $token){
		
		require_once 'protect.php';

		$db = new DB();

		$username = protect($_POST['username']);
		$message = protect($_POST['message']);
		$time = time();

		$sql = 'INSERT INTO messages (Userid, Message_Content, Message_Time) VALUES	("' . 'Atish' . '", "' . $message . '", ' . $time . ')';
			 
		$db->query($sql);
	}
	*/
	function displayUsersMessages()
	{
		$db = new DB();

		$fiveMinutesAgo = time() - 600;
		
		$sql = 'SELECT * FROM messages WHERE Message_Time > ' . $fiveMinutesAgo . ' ORDER BY message_time';

        $db->query($sql);
				
		$result = $db->get();
		$users = array();
		
        foreach($result as $key => $row) {
			$hoursAndMinutes = date('g:i:s', strtotime($row['Message_Time'])); 
			$users[] = '<p><strong>' . $row['Userid'] . '</strong>: <em>(' . $hoursAndMinutes . ')</em> ' . $row['Message_Content'] . '</p>';
			 
		}
		
		return $users; 
	}
	
	function getOnlineUsers($userid){

		$db = new DB();

		$fiveMinutesAgo = time() - 400;

		$sql = 'SELECT * FROM userinfo where logged="1"';

		$db->query($sql);
			
		$result = $db->get();

		$users = array();
		
		foreach($result as $key => $row) {
			if($row['userId']==$userid)
			{
			}else{
				$users[] = "<p><input type=button id=" . $row['userId'] . " class='buttonLink' onClick=challenge(" . "'" . $userid . "'" . "," . "'" . $row['userId'] . "'" . "," . "'" .$row['Firstname']. "'" . "); value=" . $row['Firstname'] . "></input></p>";
			}
		}

		return $users;
	}
	
	
?>
