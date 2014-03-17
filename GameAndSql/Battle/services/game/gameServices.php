<?php
	require_once('bizData/checkTurn.php');
	require_once 'common/gameFunc.php';

	
	function challenge($touserid, $challengedby){
		return challengeUser($touserid, $challengedby);
	}
	
	function isChallenged($userid)
	{
		return isChallengedUser($userid);
	}
	
	function accepted($userid)
	{
		return updateChallengeAcceptedFlag($userid);
	}
	
	function isAccepted($userid)
	{
		return isChallengeAccepted($userid);
	}
	
	function deleteChallenge($id1, $id2)
	{
		return deleteUserChallenge($id1);
	}
	
	function inGame($userid)
	{
		return isUserInGame($userid);
	}
	
	function updateChallenge($touserid, $challengedby)
	{
		return updateUserChallenge($touserid, $challengedby);
	}
	
	function shipsLoc($playerid) {
		
		return getShips($playerid);
	}
	
	function dropBombs($data) {
		
		return updateBombsInGame($data);
	}
	
	function getTheBoard($data) {
		
		return getBoard($data);
	}
	
	function updateLife($data) {
		
		list($playerid, $acc, $battleship, $cruiser, $sub, $destroyer) = explode("_", $data);
		
		$gameId = 8;
		return updateGameInfoWithLife($gameId, $playerid, $acc, $battleship, $cruiser, $sub, $destroyer);
	}
	
	function getTurn($d) {
		
		$gameId = 8;
		return whoseTurnIsIt($gameId, true);
	}

	function changeWhoseTurnIsIt($d) {
		
		list($gameId, $newTurn) = explode("_", $d);
		
		return updateWhoseTurnIsIt($gameId, $newTurn);
	}
	

	function sendMessage($d) {
		
		list($message, $toWhom) = explode("|", $d);
		
		return createMessage($message, $toWhom);
	}
	
	function getMessages($d) {
		
		$toWhom = $d;
		return getNewMessages($toWhom);
	}
	
	function updateMessages($d) {
		
		$id = $d;
		return updateMessageStatus($id);
	}
	
	function submitShipsToStart($data, $ip, $token) {
		
		return sendShips($data);
	}

	function shipLocations($data) {
		
		return populateShipsTable($data);
	}
	
	
	function checkShipsPresent($data) {
		
		return checkGameExists($data);
	}
	
	function deleteFromAllGameTables($d) {
		
		deleteFromPlayerGame();
		deleteFromShipOrientation();
		deleteFromShips();
		deleteFromPlayerShipsPos();
		deleteFromAlert();
		deleteFromGame();
		
	}
	
	
	
?>
