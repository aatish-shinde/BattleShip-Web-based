<?php
require_once 'db.class.php';

function initGameAjax() {
	$db1 = new DB();
	
	$gameId = 8;
	
    if ($stmt = $db1->mysqli->prepare("SELECT player_gameid FROM player_game WHERE player_gameid = ?")) {
        $stmt->bind_param('i', $gameId);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows();
        $stmt->close();
        if ($num_rows > 0)
            return true;
        else
            return false;
    }
}

function checkGameExists($player) {
    $db1 = new DB();

    $gameId = 8;

    if ($stmt = $db1->mysqli->prepare("SELECT gameId FROM game WHERE gameId = ? AND player1 = ?")) {
        $stmt->bind_param('is', $gameId, $player);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows();
        $stmt->close();
        if ($num_rows > 0)
            return true;
        else
            return false;
    }
    else {
        return -1;
    }
}

function getUserInfo($id) {
 
	$db1 = new DB();
	
    if ($stmt = $db1->mysqli->prepare("SELECT userId, Firstname FROM userinfo WHERE userId = ?")) {
	//echo $id;
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->bind_result($userid, $firstname);
        $stmt->fetch();
        $stmt->close();

        $user = new stdClass();
        $user->firstname = $firstname;
        $user->userid = $userid;
        
        return $user;
    }
    else
        return 'Unable to prepare statement';
}

function updateWhoseTurnIsIt($gameId, $turn) {
	$db1 = new DB();
	
    $sql = "UPDATE player_game SET turn = ? WHERE player_gameid = ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->bind_param('si', $turn, $gameId);
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        return $updatedRows;
    }
    else
        return "Unable to prepare statement";
}

function whoseTurnIsIt($gameId, $val) {
	$db1 = new DB();
	
    $obj = new stdClass();
    $obj->gameId = $gameId;

    //For some reason, doing a prepared statement here completely broke...
    $sql = "SELECT turn FROM player_game WHERE player_gameid = ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
		$stmt->bind_param('i', $gameId);
        $stmt->execute();
		$stmt->bind_result($turn);
        $stmt->fetch();
        $stmt->close();
		
		$obj->turn = $turn;
		
		if($val) {
            return $obj;
        } else {
            return $obj->turn;
        }
	}
	else {
        return "Unable to prepare statement";
    }
}


function getShips($playerId) {

	$db1 = new DB();
	
    $response = new stdClass();
	$response->AircraftCarrier = new stdClass();
	$response->Battleship = new stdClass();
	$response->Submarine = new stdClass();
	$response->Cruiser = new stdClass();
	$response->Destroyer = new stdClass();
	
	
    $sql = "SELECT * FROM  player_ships_pos WHERE playerid = ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->bind_param('s', $playerId);
        $stmt->execute();
        $stmt->bind_result($id, $playerId, $ac_cell, $battleship_cell, $submarine_cell, $cruiser_cell, $destroyer_Cell);
        $stmt->fetch();
        $stmt->close();
	
		$sql = "SELECT * FROM shiporientation ori WHERE playerid = ?";
		if ($stmt = $db1->mysqli->prepare($sql)) {
			$stmt->bind_param('s', $playerId);
			$stmt->execute();
			$stmt->bind_result($oreitID, $playerid, $accOri, $battleOri, $submarineOri, $cruiserOri, $destroyerOri);
			$stmt->fetch();
			$stmt->close();
			
			$response->AircraftCarrier->row_col = $ac_cell;
			$response->AircraftCarrier->Ori = $accOri;
			$response->AircraftCarrier->type = "AircraftCarrier";
			
			$response->Battleship->row_col = $battleship_cell;
			$response->Battleship->Ori= $battleOri;
			$response->Battleship->type = "Battleship";
			
			$response->Submarine->row_col = $submarine_cell; 
			$response->Submarine->Ori = $submarineOri;
			$response->Submarine->type = "Submarine";
			
			$response->Cruiser->row_col = $cruiser_cell;   
			$response->Cruiser->Ori = $cruiserOri;
			$response->Cruiser->type = "Cruiser";
			
			$response->Destroyer->row_col = $destroyer_Cell;
			$response->Destroyer->Ori = $destroyerOri;
			$response->Destroyer->type = "Destroyer";
		}
	}
	return $response;
		
}

function sendShips($data) {
	$db1 = new DB();
    
	list($player1, $player2, $locations) = explode("|", $data);
    $locations = stripslashes($locations);
    $gameId = 8;
    $locations = json_decode($locations);
	
    $sql = "INSERT INTO game (gameId, player1, player2, ";
    foreach ($locations as $location) {
        $sql .= "cell$location, ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= ") VALUES($gameId, '$player1', '$player2', ";

    foreach ($locations as $location) {
        $sql .= "'1_0', ";
    }

    $sql = substr($sql, 0, -2);
    $sql .= ")";
	
    if ($db1->query($sql) == true)
        return 1;
    else
        return 0;
}

function populateShipsTable($data) {
    $db1 = new DB();

	list($playerId, $acc, $battleship, $submarine, $cruiser, $destroyer) = explode("!", $data);
	
	echo $playerId;
	
    $sql = "INSERT INTO player_ships_pos (playerid, aircraft, battleship, submarine, cruiser, destroyer) VALUES ".
						"('$playerId', '$acc', '$battleship', '$submarine', '$cruiser', '$destroyer')";
	
	if($db1->query($sql))
		return 1;
	else
		return -1;
	
}

function populateShipOrientation($data) {
    $db1 = new DB();

	list($playerId, $acc, $battleship, $cruiser, $submarine, $destroyer) = explode("|", $data);
	
    $sql = "INSERT INTO shiporientation VALUES(?, ?, ?, ?, ?, ?)";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->bind_param('ssssss', $playerId, $acc, $battleship, $cruiser, $submarine, $destroyer);
        $stmt->execute();
        $insertedRows = $stmt->affected_rows;
        $stmt->close();
        return $insertedRows;
    }
    else {
        return -2;
    }
}

function getBoard($data) {
	
	$db1 = new DB();
	
	list($gameId, $playerid) = explode("_", $data);
		$gameId = 8;

    $sql = "SELECT * FROM game, ships WHERE game.player1=ships.playerid and game.gameId = $gameId AND game.player1 = '$playerid'";
    
    if ($result = $db1->mysqli->query($sql)) {
        $row = $result->fetch_assoc();
        $cells = array();
        $count = 0;
        foreach($row as $cell => $value) {
            if ($cell == 'id' || $cell == 'gameId' || $cell == 'player1' || $cell == 'player2' || $cell=='playerid' || $cell=='gameid') {
                continue;
            }
            elseif ($cell == 'aircraft' || $cell == 'battleship'
                    || $cell == 'cruiser'
                    || $cell == 'submarine'
                    || $cell == 'destroyer'
            ) {
                $cells[$count] = $value;
                $count++;
            }
            else {
                // cell = cell0_0
                $cell = substr($cell, 4);
                // cell = 0_0
                list($i, $j) = explode("_", $cell);
                // i = 0 and j = 0
                // value = 0_1
                $value = $value."|".$i."|".$j;
                // value = 0_1|0|0
                $cells[$count] = $value;
                $count++;
            }
        }
        return $cells;
    }
}

function updateBombsInGame($data) {

	$db1 = new DB();
	
	list($playerid, $square) = explode("|", $data);
	
	$gameId = 8;
	
	$obj = new stdClass();
	$obj->cell = $square;
	$obj->abc = $playerid;
	
	$square = "cell$square";
	
	$query = "SELECT $square FROM game WHERE gameId = ? AND player2 = ?";
	if ($stmt = $db1->mysqli->prepare($query)) {
		$stmt->bind_param('is', $gameId, $playerid);
		$stmt->execute();
		$stmt->bind_result($square_value);
		$stmt->fetch();
		$stmt->close();
		
		$pieces = explode("_", $square_value);
		$new_value = $pieces[0] . "_1";

		if ($pieces[0] != "0") {
			
			$obj->hit = true;
		} else {
			$obj->hit = false;
		}
		
		if ($stmt2 = $db1->mysqli->prepare("UPDATE game SET $square = ? WHERE gameId = ? AND player2 = ?")) {
			$stmt2->bind_param('sis', $new_value, $gameId, $playerid);
			$stmt2->execute();
			$stmt2->close();
			return $obj;
		} else {
			return "Unable to prepare statement update";
		}
	} else {
		return $obj;
	}
}

function updateGameInfoWithLife($gameId, $playerid, $aircraft, $battleship, $sub, $cruiser, $destroyer) {

	$db1 = new DB();
	
    $obj = new stdClass();
    
	$aircraft = (int)$aircraft;
    $battleship = (int)$battleship;
    $sub = (int)$sub;
    $cruiser = (int)$cruiser;
    $destroyer = (int)$destroyer;

    $sql = "UPDATE ships SET aircraft = ?, battleship = ?, submarine = ?, cruiser = ?, destroyer = ? WHERE playerid = ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->bind_param('iiiiis', $aircraft, $battleship, $sub, $cruiser, $destroyer, $playerid);
        $stmt->execute();
        $obj->affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $obj;
    } else {
        return "Unable to prepare statement ";
    }
}

function createMessage($alert, $youPlayerId) {

	$db1 = new DB();
	
    $void = 'new';
	$gameid = 8;
	
    $sql = "INSERT INTO alerts(gameid, alert, toWhom, void) VALUES(8, '$alert', '$youPlayerId', '$void')";
	
	if($db1->query($sql))
		return 1;
	else
		return "unable to insert";
	
}

function getNewMessages($toWhom) {

	$db1 = new DB();
	
    $obj = new stdClass();

    $void = 'new';
	
    $sql = "SELECT alertId, alert FROM alerts WHERE void = ? and toWhom= ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
		$stmt->bind_param('ss', $void, $toWhom);
        $stmt->execute();
		$stmt->bind_result($id, $alert);
        $stmt->fetch();
		$stmt->close();
		
        $obj->alert = stripslashes($alert);
		$obj->id = $id;
        return $obj;
    }
    else {
        return -1;
    }

}

function updateMessageStatus($id) {

	$db1 = new DB();
	$void = 'void';
    
    $sql = "UPDATE alerts SET void = ? WHERE alertId = ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->bind_param('si', $void, $id);
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        //return $updatedRows;
    }
    else {
        return -1;
    }
}

function deleteFromGame() {
	$db1 = new DB();

    $gameId = 8;

    $sql = "DELETE FROM game WHERE gameId = ?";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->bind_param('i', $gameId);
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        //return $updatedRows;
    }
    else {
        return -1;
    }
}

function deleteFromPlayerShipsPos() {
	$db1 = new DB();

    $sql = "DELETE FROM player_ships_pos";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        //return $updatedRows;
    }
    else {
        return -1;
    }
}

function deleteFromShips() {

	$db1 = new DB();
    $sql = "DELETE FROM ships";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        //return $updatedRows;
    }
    else {
        return -1;
    }
}

function deleteFromShipOrientation() {

	$db1 = new DB();

    $sql = "DELETE FROM shiporientation";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        //return $updatedRows;
    }
    else {
        return -1;
    }
}

function deleteFromPlayerGame() {

$db1 = new DB();

    $sql = "DELETE FROM player_game";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        return $updatedRows;
    }
    else {
        return -1;
    }
}

function deleteFromAlert(){
	$db1 = new DB();

    $sql = "DELETE FROM alerts";
    if ($stmt = $db1->mysqli->prepare($sql)) {
        $stmt->execute();
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        return $updatedRows;
    }
    else {
        return -1;
    }
}



?>