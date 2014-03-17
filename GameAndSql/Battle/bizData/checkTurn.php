<?php
	
	function updateChallengeAcceptedFlag($userid){
		$db1 = new DB();
		
		$flag=0;
		
		$sql = "update player_game set flag = ? where player1 = ?";
		
		if ($stmt = $db1->mysqli->prepare($sql)) {
			$stmt->bind_param('is', $flag, $userid);
			$stmt->execute();
			$updatedRows = $stmt->affected_rows;
			$stmt->close();
			return $updatedRows;
		}
		else
			return "Unable to prepare statement";
	}
	
	function isChallengeAccepted($userid){
		$db1 = new DB();
		
		$sql = "SELECT player1, player2 FROM player_game where player1 = ? and flag = 1";
		
		$obj = new stdClass();
		
		if($stmt = $db1->mysqli->prepare($sql)){
			$stmt->bind_param('s', $userid);
			$stmt->execute();
			$stmt->bind_result($player1, $player2);
			$stmt->fetch();
			$stmt->close();
			$obj->player1 = $player1;
			$obj->player2 = $player2;
			return $obj;
		}
		else
			return "unable to execute prepare stmt";
	}
	
	function isChallengedUser($userid){
	
		$db = new DB();
	
		$sql = "SELECT * FROM Challenge where challengedto='$userid' and challenge=1";

		$db->query($sql);
			
		$result = $db->get();
		
		$challenged = array();
		
		$challenged[0]=0;
		
		foreach($result as $key => $row) {
			$challenged[0] = $row['challengedby'];
		}
		
		return $challenged;
	}
	
	function deleteUserChallenge($userid){
		$db = new DB();

		$sql = "delete from Challenge";

		$db->query($sql);
		$users = array();
		$users[0] = 1;
		
	}
	
	function isUserInGame($userid)
	{
		$db1 = new DB();
		$sql = "select * from challenge where challengedby='$userid' and ingame=1";
		
		$users = array();
		
		$db1->query($sql);
		$result = $db1->get();		
		
		$users[0]=0;
		foreach($result as $key => $row) {
			$users[0] = 1;
		}
		
		return $users;
		
	}
	
	function challengeUser($challengedto, $challengedby){
		$db1 = new DB();
					
		$id = doesTheGameExists($challengedby);
				
		if($id==null){
			
			$sql = "insert into Challenge (gameid, challengedto, challengedby, challenge, ingame) values (8, '$challengedto', '$challengedby', 1, 1)";
			$db1->query($sql);
			
			return 1;
		}
		else
		{
			return 0;
		}
						
	}
	
	function doesTheGameExists($challengedby) {
		$db1 = new DB();
		
		
		$sql = "SELECT gameId FROM game WHERE gameId = 8";
		
		$db1->query($sql);
		
		$gameid = $db1->get('gameId');
		
		return $gameid;
		
    }

	function updateUserChallenge($challengedto, $challengedby){
		$db1 = new DB();
		
		$sql = "update Challenge set challenge = 0 where gameid=8";
			
		$one = $db1->query($sql);
		
		//insertIntoFromGame($challengedto, $challengedby);
		//insertIntoFromPlayerShipsPos($challengedto, $challengedby);
		insertIntoFromShips($challengedto, $challengedby);
		insertIntoFromShipOrientation($challengedto, $challengedby);
		insertIntoPlayerGame($challengedto, $challengedby);
	
		
	}
	
	function insertIntoFromGame($challengedto, $challengedby)
	{
		$db1 = new DB();
		$player1 = "INSERT INTO game (gameId, player1, player2, cell0_0, cell0_1, cell0_2, cell0_3, cell0_4, cell0_5, cell0_6, cell0_7, cell0_8, cell0_9, cell1_0, cell1_1, cell1_2, cell1_3, cell1_4, cell1_5, cell1_6, cell1_7, cell1_8, cell1_9, cell2_0, cell2_1, cell2_2, cell2_3, cell2_4, cell2_5, cell2_6, cell2_7, cell2_8, cell2_9, cell3_0, cell3_1, cell3_2, cell3_3, cell3_4, cell3_5, cell3_6, cell3_7, cell3_8, cell3_9, cell4_0, cell4_1, cell4_2, cell4_3, cell4_4, cell4_5, cell4_6, cell4_7, cell4_8, cell4_9, cell5_0, cell5_1, cell5_2, cell5_3, cell5_4, cell5_5, cell5_6, cell5_7, cell5_8, cell5_9, cell6_0, cell6_1, cell6_2, cell6_3, cell6_4, cell6_5, cell6_6, cell6_7, cell6_8, cell6_9, cell7_0, cell7_1, cell7_2, cell7_3, cell7_4, cell7_5, cell7_6, cell7_7, cell7_8, cell7_9, cell8_0, cell8_1, cell8_2, cell8_3, cell8_4, cell8_5, cell8_6, cell8_7, cell8_8, cell8_9, cell9_0, cell9_1, cell9_2, cell9_3, cell9_4, cell9_5, cell9_6, cell9_7, cell9_8, cell9_9) VALUES ".
						"(8, '$challengedby', '$challengedto', '0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','1_0','1_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','1_0','1_0','0_0','0_0','0_0','1_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','1_0','1_0','1_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0')";

					
		$two = $db1->query($player1);	
		
		$player2 = "INSERT INTO game (gameId, player1, player2, cell0_0, cell0_1, cell0_2, cell0_3, cell0_4, cell0_5, cell0_6, cell0_7, cell0_8, cell0_9, cell1_0, cell1_1, cell1_2, cell1_3, cell1_4, cell1_5, cell1_6, cell1_7, cell1_8, cell1_9, cell2_0, cell2_1, cell2_2, cell2_3, cell2_4, cell2_5, cell2_6, cell2_7, cell2_8, cell2_9, cell3_0, cell3_1, cell3_2, cell3_3, cell3_4, cell3_5, cell3_6, cell3_7, cell3_8, cell3_9, cell4_0, cell4_1, cell4_2, cell4_3, cell4_4, cell4_5, cell4_6, cell4_7, cell4_8, cell4_9, cell5_0, cell5_1, cell5_2, cell5_3, cell5_4, cell5_5, cell5_6, cell5_7, cell5_8, cell5_9, cell6_0, cell6_1, cell6_2, cell6_3, cell6_4, cell6_5, cell6_6, cell6_7, cell6_8, cell6_9, cell7_0, cell7_1, cell7_2, cell7_3, cell7_4, cell7_5, cell7_6, cell7_7, cell7_8, cell7_9, cell8_0, cell8_1, cell8_2, cell8_3, cell8_4, cell8_5, cell8_6, cell8_7, cell8_8, cell8_9, cell9_0, cell9_1, cell9_2, cell9_3, cell9_4, cell9_5, cell9_6, cell9_7, cell9_8, cell9_9) VALUES ".
					"(8, '$challengedto', '$challengedby', '0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','1_0','0_0','1_0','1_0','1_0','0_0','1_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','0_0','0_0','0_0','0_0','1_0','1_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','1_0','1_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0','0_0')";
		
		$three = $db1->query($player2);	
	}
	
	function insertIntoFromPlayerShipsPos($challengedto, $challengedby)
	{
		$db1 = new DB();
		$player1 = "INSERT INTO player_ships_pos (id, playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"(1, '$challengedby', '8_2', '1_3', '5_1', '3_4', '3_7')";
					
		$two = $db1->query($player1);	
		
		$player2 = "INSERT INTO player_ships_pos (id, playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"(2, '$challengedto', '2_8', '2_2', '7_3', '4_4', '0_5')";
					
		$two = $db1->query($player2);
	}
	
	function insertIntoFromShips($challengedto, $challengedby)
	{
		$db1 = new DB();
		$player1 = "INSERT INTO ships (gameid, playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"(8, '$challengedby', 5, 4, 3, 3, 2)";
					
		$two = $db1->query($player1);	
		
		$player2 = "INSERT INTO ships (gameid, playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"(8, '$challengedto', 5, 4, 3, 3, 2)";
					
		$two = $db1->query($player2);
	}
	
	function insertIntoFromShipOrientation($challengedto, $challengedby)
	{
		$db1 = new DB();
		$player1 = "INSERT INTO shiporientation (shipsorientID, playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"(1, '$challengedby', 'false', 'false', 'true', 'true', 'true')";
					
		$two = $db1->query($player1);	
		
		$player2 = "INSERT INTO shiporientation (shipsorientID, playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"(2, '$challengedto', 'true', 'true', 'false', 'false', 'false')";
					
		$two = $db1->query($player2);
	}
	
	function insertIntoPlayerGame($challengedto, $challengedby)
	{
		$db1 = new DB();
		$player1 = "INSERT INTO player_game (id, player_gameid, player1, player2, turn, flag) VALUES ".
						"(1, 8,'$challengedby','$challengedto','$challengedby', 1)";
					
		$two = $db1->query($player1);	
	}
		
?>