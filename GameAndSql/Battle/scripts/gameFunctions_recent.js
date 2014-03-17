var xhtmlns = "http://www.w3.org/1999/xhtml";
var svgns = "http://www.w3.org/2000/svg";

var BOARDX = 50;			//starting pos of board
var BOARDY = 100;			//look above
var BOARD2X = BOARDX + 430;

var BOARDWIDTH = 10;			//how many squares across
var BOARDHEIGHT = 10;			//how many squares down

var pieceCount = 0;
var boardArr = new Array();		//2d array [row][col]
var pieceArr = new Array();		//2d array [player][piece]
var board2Arr = new Array();
var piece2Arr = new Array();

var CELLSIZE = 35;

var pinCount = 0;
var shipsLeft = 5;
var opponentShipsLeft = 5;
var availableGuesses = 5;
var gameId = 8;

var getTurnTimeout;


function callGETPOST(callType, data, callback) {
    $.ajax({
        type:callType,
        data: data,
        url:"mid.php",
        dataType:'json',
        success:callback
    });
}


function init(){

    var gEle = document.createElementNS(svgns, 'g');
    gEle.setAttributeNS(null,'transform','translate('+BOARDX+','+BOARDY+')');
    gEle.setAttributeNS(null,'id', 8);

    document.getElementsByTagName('svg')[0].appendChild(gEle);

    var gEle2 = document.createElementNS(svgns, 'g');
    gEle2.setAttributeNS(null, 'transform', 'translate('+BOARD2X+', '+BOARDY+')');
    gEle2.setAttributeNS(null, 'id', 8+"_guesses");
    document.getElementsByTagName('svg')[0].appendChild(gEle2);
     
	//create the board...
	//var x = new Cell(document.getElementById('someIDsetByTheServer'),'cell_00',75,0,0);
    for(var i = 0; i < BOARDWIDTH; i++){
        boardArr[i] = new Array();
        board2Arr[i] = new Array();
        for(var j = 0; j < BOARDHEIGHT; j++){
            
            boardArr[i][j] = new Cell(document.getElementById(8), 'cell_' + i + j, 35, i, j, false);
            
            
            board2Arr[i][j] = new Cell(document.getElementById(8+"_guesses"), 'shot_' + i + j, 35, i, j, true);

        }
    }
	
	createTheShipsAndPlaceThemOnBoard();
	
}

function createTheShipsAndPlaceThemOnBoard() {
	
	//Page has been refreshed and the ships are already set
	placeTheShipsOnBoard();
	//alert(opponentPlayerBoard);
	for(var i = 0; i < (opponentPlayerBoard.length)-5; i++) {
		// data[i] looks like: 0_1|0|0 aka ship_guess|row|col
		var arr     = opponentPlayerBoard[i].split("_");
		
		var ship    = parseInt(arr[0]);
		var arr2    = arr[1].split("|");
		//alert(arr2);
		var guess   = parseInt(arr2[0]);
		var row     = parseInt(arr2[1]);
		var col     = parseInt(arr2[2]);

		//If there is no guess, we don't need to do anything
		if (guess == 0) {
			continue;
		}
		//Player guessed
		else if (guess == 1) {
			var hit;
			var cell = row + "_" + col;
			//Player hit ship
			if (ship == 1) {
				hit = true;
			}
			//Player missed ship
			else if (ship == 0) {
				hit = false;
			}

			var obj = {
				"cell" : cell,
				"hit" : hit
			};
			
			var arr = obj.cell.split("_");
			var i = arr[0];
			var j = arr[1];
			piece2Arr[pinCount] = new Piece("guess", youPlayerId, i, j, 'Pin', pinCount);
		   
			if(obj.hit == true) {
				piece2Arr[pinCount].piece.setAttributeNS(null, 'class', 'piece_pin_hit');
			}
			else {
				piece2Arr[pinCount].piece.setAttributeNS(null, 'class', 'piece_pin_miss');
			}
			pinCount++;
		}
	}
    
}

function placeTheShipsOnBoard() {
    var data = youPlayerId;
	
    callGETPOST("GET", {method: 'shipsLoc', type: 'shipsPos', a:'game', u: youPlayerId}, function(response){
	
		for(var key in response) {
			
			var arr = response[key].cell.split("_");
			var row = parseInt(arr[0]);
			var col = parseInt(arr[1]);
			var vertical = (response[key].vertical === 'true') ? true : false;
			
			pieceArr[pieceCount] = new Piece("ship", youPlayerId, row, col, response[key].type, pieceCount, vertical);
			pieceCount++;
		}
		
		drawBoard(youPlayerBoard, false);
		
	});
	
}

function getPiece(which){
    /*var userID = which.search(/\_/)+1;
    userID = which.substr(userID, 1);
    userID = parseInt(userID);*/

    var pieceNumber = which.search(/\|/)+1;
    pieceNumber = which.substr(pieceNumber, 1);
    pieceNumber = parseInt(pieceNumber);

    return pieceArr[pieceNumber];
}

function drawBoard(data, withMessages) {
    var acLife = parseInt(data[0]);
    var battleshipLife = parseInt(data[1]);
    var cruiserLife = parseInt(data[2]);
    var submarineLife = parseInt(data[3]);
    var destroyerLife = parseInt(data[4]);

    //Start at 5 because of the ship life columns
    for(var i = 0; i < (data.length)-5; i++) {
        // data[i] looks like: 0_1|0|0 aka ship_guess|row|col
        var arr     = data[i].split("_");
        var ship    = parseInt(arr[0]);
        var arr2    = arr[1].split("|");
        var guess   = parseInt(arr2[0]);
        var row     = parseInt(arr2[1]);
        var col     = parseInt(arr2[2]);

        if (guess == 0 || boardArr[row][col].pin != '') {
            //No guess or we have already made this pin
            continue;
        }
        else if (guess == 1) {
            //New pin we need to create
            pieceArr[pieceCount] = new Piece("ship", youPlayerId, row, col, 'Pin', pieceCount);
            if(ship == 0) {
                //Miss
                pieceArr[pieceCount].piece.setAttributeNS(null, 'class', 'piece_pin_miss');
            }
            else if (ship == 1) {
                //Hit
                pieceArr[pieceCount].piece.setAttributeNS(null, 'class', 'piece_pin_hit');
                //Find the ship that occupies this spot and subtract 1 from its life
                var pieceId = boardArr[row][col].occupied;
                var shipOnBoard = getPiece(pieceId);
                shipOnBoard.object.life--;
                //If its life is now zero, tell both users!
                if (shipOnBoard.object.life == 0) {
                    if(withMessages) {
                        
						var message = "You sunk " + youPlayer + "'s " + shipOnBoard.object.name+'|'+youPlayerId;
						
						callGETPOST("GET", {method: "sendMessage", type: "game", a: "game", data: message}, function(data){
						});
                       
                        alert(opponentPlayer + " has sunk your " + shipOnBoard.object.name);
                        
                    }
                    shipsLeft--;
                }
            }
            pieceCount++;
        }
    }
}

function makeGuess(row, col) {

	alert(turn + "_" + youPlayerId);
	
    if(turn == youPlayerId) {
	
	//alert(availableGuesses);
	//alert(row + "_" + col);
        //My turn and my ships are set
        var data = gameId + "|" + youPlayerId + "|" + row + "_" + col;
		//document.write(data);
		
        if(availableGuesses > 1) {
            //Still have more than 1 guess left
            callGETPOST("GET", {method: "makeGuess", type: "game", a: "game", data: data}, function(data){
				var arr = data.cell.split("_");
				var i = arr[0];
				var j = arr[1];
				piece2Arr[pinCount] = new Piece("guess", youPlayerId, i, j, 'Pin', pinCount);
			   
				if(data.hit == true) {
					piece2Arr[pinCount].piece.setAttributeNS(null, 'class', 'piece_pin_hit');
				}
				else {
					piece2Arr[pinCount].piece.setAttributeNS(null, 'class', 'piece_pin_miss');
				}
				pinCount++;
			
			});
            availableGuesses--;
        }
        else {
            //One guess left, do it, change turns and reset availableGuesses
            callGETPOST("GET", {method: "makeGuess", type: "game", a: "game", data: data}, function(data){
				var arr = data.cell.split("_");
				var i = arr[0];
				var j = arr[1];
				piece2Arr[pinCount] = new Piece("guess", youPlayerId, i, j, 'Pin', pinCount);
			   
				if(data.hit == true) {
					piece2Arr[pinCount].piece.setAttributeNS(null, 'class', 'piece_pin_hit');
				}
				else {
					piece2Arr[pinCount].piece.setAttributeNS(null, 'class', 'piece_pin_miss');
				}
				pinCount++;
			
			});
			turn = opponentPlayer;
			//alert(opponentPlayerId);
			callGETPOST("GET", {method:"changeTurn", type: "game", a:"game", data: gameId + "_" + opponentPlayerId}, function(data){
			 document.getElementById('nyt').firstChild.data = "Turn: " + opponentPlayer;
				getTurn();
			});
        }
    }
    else {
        alert("Its not your turn, "+youPlayerId);
    }
}

function getTurn() {
    callGETPOST("GET", {method: "getTurn", type: "game", a:"game", data: gameId}, callBackTurn);
}

function callBackTurn(data) {
    turn = data.turn;
    if(data.turn == youPlayerId) {
        //It is currently my turn
        document.getElementById('nyt').firstChild.data = "Turn: " + youPlayer;
        //Get the other guy's new guesses
        callGETPOST("GET", {method: "getTheBoard", type: "game", a:"game", data: gameId + "_" + youPlayerId}, callBackGetYouPlayerBoard);
        //Make sure i have the correct amount of guesses
        availableGuesses = opponentShipsLeft;
        
    }
    else {
        //Not my turn. Keep asking.
        getTurnTimeout = setTimeout('getTurn()', 5000);
    }
}

function callBackGetYouPlayerBoard(data) {
    drawBoard(data, true);
    var acLife = parseInt(data[0]);
    var battleshipLife = parseInt(data[1]);
    var cruiserLife = parseInt(data[2]);
    var submarineLife = parseInt(data[3]);
    var destroyerLife = parseInt(data[4]);

    //If the life we started with is different from the current life, we need to update the db
    if (pieceArr[0].object.life != acLife || pieceArr[1].object.life != battleshipLife
        || pieceArr[2].object.life != cruiserLife
        || pieceArr[3].object.life != submarineLife
        || pieceArr[4].object.life != destroyerLife
        ) {
        var ajaxData = gameId + "!" + youPlayerId + "!"
            + pieceArr[0].object.life + "!"
            + pieceArr[1].object.life + "!"
            + pieceArr[2].object.life + "!"
            + pieceArr[3].object.life + "!"
            + pieceArr[4].object.life;
        callGETPOST("GET", {method: "updateLife", service: "game", a:"game", data: ajaxData}, function(data){
		
		});
    }

    //You just lost!
    if (shipsLeft == 0) {
        var message = "You won"+'|'+youPlayerId;
						
		callGETPOST("GET", {method: "sendMessage", type: "game", a: "game", data: message}, function(data){
		});
        alert(opponentPlayer + ' sunk all your ships!');
    }
}


function quit() {
    //confirm
	var r=confirm("Are you sure you want to quit?");
	
	if (r==true)
	{
		//var data = youPlayerId;
		//callGETPOST("GET", {method: "endGame", type: "game", a:"game", data: data}, callBackEndGame);
	}else{
	
	}
}




