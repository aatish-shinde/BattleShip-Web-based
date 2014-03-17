<?php

error_reporting(0);

session_name("Battle");
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['userid'] == null) {
	header ("Location: index.php");
}
else{

	require_once 'mid.php';
	require_once('common/gameFunc.php');
	
	
	$youPlayerID = getUserInfo($_REQUEST['player1']);
	
	$opponentPlayerID = getUserInfo($_REQUEST['player2']);	
	
	$gameId = 8;

	$youPlayerShips = checkGameExists($youPlayerID->userid);
	
	$opponentPlayerShips = checkGameExists($opponentPlayerID->userid);	
		
	$turn = whoseTurnIsIt($gameId, false);
			
		
	if($youPlayerShips && $opponentPlayerShips){
	
		$youPlayerData = $gameId . "_" . $youPlayerID->userid;
		
		$youPlayerBoard = json_encode(getBoard($youPlayerData));
		
		$opponentPlayerData = 	$gameId  . "_" . $opponentPlayerID->userid;
		
		$opponentPlayerBoard = json_encode(getBoard($opponentPlayerData));
		
		//echo $opponentPlayerBoard;
		
		
	}else
	{
		$youPlayerBoard = "null";
		
		$opponentPlayerBoard = "null";
		
		$youPlayerShips = "false";
		
		$opponentPlayerShips = "false";
		
	}

	//echo json_encode(createMessage("hi", 'abc12'));
	
	//echo json_encode(getShipsTable($youPlayerID));
	
	//echo $opponentPlayerBoard;
	
	//echo json_encode(getShipsTable('abc12'));
	
	//echo getships('shipsLoc', 'shipsPos', 'game', 'abc12');

	
?>


<!--Check out: http://developer.mozilla.org/en/docs/SVG_in_Firefox -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>BattleShip</title>
  
   <link rel="stylesheet" type="text/css" href="css/game.css" />
   
	<style type="text/css">
	  <![CDATA[
		.indicator_row, .indicator_col {fill: #0D0;}
		.bomb_hit circle {fill: #A80000;}
		.cell_blue {fill: #09F; stroke: #33F; stroke-width: 2px;}
		.cell_white { fill:white;stroke:red;stroke-width:2px; }
		.cell_black { fill:black;stroke:red;stroke-width:2px; }
		#background {
			fill: #666;
			stroke: black;
			stroke-width: 2px;
		}
		.piece_AircraftCarrier rect {fill: #AD8BD3;}
		.piece_Battleship rect{fill: #8BD3C8;}
		.piece_Cruiser rect{fill: #828893;}
		.piece_Submarine rect{fill: #D3D38B;}
		.piece_Destroyer rect {fill: #98D38B;}
					
		.player0   {fill: #990000; stroke: white; stroke-width: 1px; }
		.player1 {fill: green; stroke: white; stroke-width: 1px; }
		
		.htmlBlock {position:absolute;top:200px;left:300px;width:200px;height:100px;background:#ffc;padding:10px;}
		body{padding:0px;margin:0px;}
		
		#Battle #types {position:absolute; left: 280px; top: 495px; padding: 5px; width: 400px;}
		#Battle #r1 {position: absolute; left: 450px; top: 495px;}
		#Battle #end {position: absolute; left: 20px; top: 515px;}
		#Battle #buttonShips {position: absolute; left: 110px; top: 515px;} 
				
	  ]]>
	</style>
	<script src="scripts/jquery-1.6.2.min.js"></script>
	<script src="scripts/jquery-ui-1.8.16.custom.min.js"></script>
	<script src="scripts/Cell.js" type="text/javascript"></script>
	 <script src="scripts/Piece.js" type="text/javascript"></script>
			
	<script src="scripts/gameFunctions.js" type="text/javascript"></script>
	<script src="scripts/dragging.js" type="text/javascript"></script>
	
	
	<script type="text/javascript">
		var youPlayer = '<?php echo $youPlayerID->firstname ?>';
		
		var opponentPlayer = '<?php echo $opponentPlayerID->firstname; ?>';
		var youPlayerId = '<?php echo $youPlayerID->userid; ?>';
		var opponentPlayerId = '<?php echo $opponentPlayerID->userid; ?>';
		var turn = '<?php echo $turn; ?>';
		var turnName = (turn == youPlayerId) ? youPlayer : opponentPlayer;
		var youPlayerBoard = <?php echo $youPlayerBoard; ?>;
		var opponentPlayerBoard = <?php echo $opponentPlayerBoard; ?>;
		var youPlayerShipsSet = <?php echo $youPlayerShips; ?>;
		var opponentPlayerShipsSet = <?php echo $opponentPlayerShips; ?>;
		var BOARDX = 50;			//starting pos of board
		var BOARDY = 100;			//look above
		var BOARD2X = BOARDX + 430;
				
			
		$(document).ready(function() {
			document.getElementById('youPlayer').firstChild.data = "You: " + youPlayer;
		
			document.getElementById('opponentPlayer').firstChild.data = "Opponent is: " + opponentPlayer;
		
			document.getElementById('nyt').firstChild.data = "Turn : " + turnName;
			
			//getYouPlayerAndOpponentPlayer();
			getWhoseTurnIsIt();
			getMessages();
			checkShipsPresent();

		});
		
	</script>

</head>
<body id="Battle" onload="init()">


<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="900px" height="500px">

	<!-- Make the background -->
	<rect x="0px" y="0px" width="100%" height="100%" id="background" />

	<text x="250px" y="20px" id="youPlayer" fill="black">
		You:
	</text>
		
	<text x="450px" y="20px" id="nyt" fill="red">
        Turn:
    </text>
	
	<text x="675px" y="20px" id="opponentPlayer" fill="white">
		Opponent is:
	</text>
				
</svg>
<br/>
<button id="buttonShips" onclick="submitShipsToStart();">Submit ships To Start</button>
<button id="end" onclick="quit();">End Game</button>

<div id="types">
	<p>
		<strong id="aircraft">Aircraft Carrier</strong><br />
		<strong id="battle">Battleship</strong> <br />
		<strong id="submarine">Submarine </strong><br />
		<strong id="cruiser">Cruiser </strong><br />
		<strong id="destroyer">Destroyer </strong><br />
	</p>
</div>

<div id="r1">
	<p>			
		<strong>@</strong> Drag and place the ships in the left board wherever you like and click the button "submit ships"<br />
		<strong>@</strong> Click empty squares on the right board to shoot your opponent&#39;s ships<br />
		<strong>@</strong> You have 5 shots each turn <br/>
	</p>
</div>


</body>
</html>

<?php
}
?>

