var xhtmlns = "http://www.w3.org/1999/xhtml";
var svgns = "http://www.w3.org/2000/svg";

var BOARDWIDTH = 10;			//how many squares across
var BOARDHEIGHT = 10;			//how many squares down

var pieceCount = 0;
var boardArr = new Array();		//2d array [row][col]
var pieceArr = new Array();		//2d array [player][piece]
var board2Arr = new Array();
var piece2Arr = new Array();

var bombCount = 0;
var shipsLeft = 5;
var opponentShipsLeft = 5;
var availablebombs = 5;
var gameId = 8;
var win = 0;

var getTurnTimeout;

//the problem of dragging....
var myX;				//hold my last pos.
var myY;				//hold my last pos.
var mover='';				//hold the id of the thing I'm moving


function callGETPOST(callType, data, callback) {
    $.ajax({
        type:callType,
        data: data,
        url:"mid.php",
        dataType:'json',
        success:callback
    });
}

function getMessages(){

	callGETPOST("GET", {method: "getMessages", type: "game",a:"game", data:youPlayerId}, function(data){
	
		if(data.alert!=''){
		
			var r=confirm(data.alert);
		
			if (r==true)
			{
				callGETPOST("POST", {method: "updateMessages", type: "game", a:"game",data:data.id}, function(){});
			}
			else
			{
				callGETPOST("POST", {method: "updateMessages", type: "game", a:"game",data:data.id}, function(){});
			}
		}
		setTimeout("getMessages();", 5000);
	});
	
}


function init(){

	if(youPlayerShipsSet){
		if(window.addEventListener) {
            document.getElementsByTagName('svg')[0].removeEventListener('mouseup', releaseMove, false);
            document.getElementsByTagName('svg')[0].removeEventListener('mousemove', go, false);
         
        } else {
            //IE
            document.getElementsByTagName('svg')[0].detachEvent('onmouseup', releaseMove);
            document.getElementsByTagName('svg')[0].detachEvent('onmousemove', go);
            
        }
		
		$('#buttonShips').hide();
	}
	else
	{
		if(window.addEventListener) {
			document.getElementsByTagName('svg')[0].addEventListener('mouseup', releaseMove, false);
			document.getElementsByTagName('svg')[0].addEventListener('mousemove', go, false);
			
		} else {
			
			document.getElementsByTagName('svg')[0].attachEvent('onmouseup', releaseMove);
			document.getElementsByTagName('svg')[0].attachEvent('onmousemove', go);
		  
		}
	}
		
    var gEle = document.createElementNS(svgns, 'g');
    gEle.setAttributeNS(null,'transform','translate('+BOARDX+','+BOARDY+')');
    gEle.setAttributeNS(null,'id', 8);

    document.getElementsByTagName('svg')[0].appendChild(gEle);

    var gEle2 = document.createElementNS(svgns, 'g');
    gEle2.setAttributeNS(null, 'transform', 'translate('+BOARD2X+', '+BOARDY+')');
    gEle2.setAttributeNS(null, 'id', 8+"_watercell");
    document.getElementsByTagName('svg')[0].appendChild(gEle2);
     

    for(var i = 0; i < BOARDWIDTH; i++){
        boardArr[i] = new Array();
        board2Arr[i] = new Array();
        for(var j = 0; j < BOARDHEIGHT; j++){
            
            boardArr[i][j] = new Cell(document.getElementById(8), 'cell_' + i + j, 35, i, j, false);
            
            
            board2Arr[i][j] = new Cell(document.getElementById(8+"_watercell"), 'watercell_' + i + j, 35, i, j, true);

        }
    }
	
	
	createTheShipsAndPlaceThemOnBoard();
}

function createTheShipsAndPlaceThemOnBoard() {
	if(!youPlayerShipsSet) {
      		
        pieceArr[pieceCount] = new Piece("ship", youPlayerId, 8, 2, 'AircraftCarrier', pieceCount, false);
        pieceCount++;
        pieceArr[pieceCount] = new Piece("ship", youPlayerId, 1, 3, 'Battleship', pieceCount, false);
        pieceCount++;
        pieceArr[pieceCount] = new Piece("ship", youPlayerId, 5, 1, 'Cruiser', pieceCount, true);
        pieceCount++;
        pieceArr[pieceCount] = new Piece("ship", youPlayerId, 3, 4, 'Submarine', pieceCount, true);
        pieceCount++;
		pieceArr[pieceCount] = new Piece("ship", youPlayerId, 3, 7, 'Destroyer', pieceCount, true);
        pieceCount++;
		
  		
    } else {
		placeTheShipsOnBoard();

		for(var i = 0; i < (opponentPlayerBoard.length)-5; i++) {
			
			var arr     = opponentPlayerBoard[i].split("_");
			
			var ship    = parseInt(arr[0]);
			var arr2    = arr[1].split("|");
		
			var guess	= parseInt(arr2[0]);
			var row     = parseInt(arr2[1]);
			var col     = parseInt(arr2[2]);

			
			if (guess == 0) {
				continue;
			}

			else if (guess == 1) {
				var hit;
				var cell = row + "_" + col;
				
				if (ship == 1) {
					hit = true;
				}
				else if (ship == 0) {
					hit = false;
				}

				var obj = {
					"cell" : cell,
					"hit" : hit
				};
				
				callBackdropBombs(obj);
				
			}
		}
	}
    
}

function placeTheShipsOnBoard() {
  	
    callGETPOST("GET", {method: 'shipsLoc', type: 'shipsPos', a:'game', u: youPlayerId}, callBackPlaceTheShipsOnBoard);
	
}

function callBackPlaceTheShipsOnBoard(data) {
	//alert(data);
	
    for(var key in data) {
		//alert(data[key].Ori);
        var arr = data[key].row_col.split("_");
        var row = parseInt(arr[0]);
        var col = parseInt(arr[1]);
        var vertical = (data[key].Ori === 'true') ? true : false;
		
		//alert(data[key].type);
	
			pieceArr[pieceCount] = new Piece("ship", youPlayerId, row, col, data[key].type, pieceCount, vertical);
			pieceCount++;
		
    }
   
    drawBoard(youPlayerBoard, false);
}

function drawBoard(data, withMessages) {
   
 
    for(var i = 0; i < (data.length)-5; i++) {
     
        var arr     = data[i].split("_");
        var ship    = parseInt(arr[0]);
        var arr2    = arr[1].split("|");
        var watercell   = parseInt(arr2[0]);
        var row     = parseInt(arr2[1]);
        var col     = parseInt(arr2[2]);

        if (watercell == 0 || boardArr[row][col].bomb != '') {
            
            continue;
        }
        else if (watercell == 1) {
            
            pieceArr[pieceCount] = new Piece("ship", youPlayerId, row, col, 'bomb', pieceCount);
            if(ship == 0) {
              
                pieceArr[pieceCount].piece.setAttributeNS(null, 'class', 'bomb_miss');
            }
            else if (ship == 1) {
          
				//alert("hit");
                pieceArr[pieceCount].piece.setAttributeNS(null, 'class', 'bomb_hit');
       
                var pieceId = boardArr[row][col].occupied;
				//alert(pieceId);
                var shipOnBoard = getPiece(pieceId);
				//alert(shipOnBoard);
				if(shipOnBoard.object == undefined){
					shipsLeft--;
				}
                shipOnBoard.object.life--;
               
                if (shipOnBoard.object.life == 0) {
				//alert(withMessages);
                    if(withMessages) {
                        
						var message = "You sunk " + youPlayer + " " + shipOnBoard.object.name + '|' + opponentPlayerId;
						
						callGETPOST("POST", {method: "sendMessage", type: "game", a: "game", data: message}, function(data){
							//alert(data);
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

function getPiece(type){

    var pieceNumber = type.search(/\|/)+1;
    pieceNumber = type.substr(pieceNumber, 1);
    pieceNumber = parseInt(pieceNumber);

    return pieceArr[pieceNumber];
}



function dropBombOnTheShip(row, col) {

	if(win==0){
		if(turn == youPlayerId){
			if(youPlayerShipsSet == true && opponentPlayerShipsSet == true) {
			
			
				var data = youPlayerId + "|" + row + "_" + col;
							
				if(availablebombs > 1) {
				
					callGETPOST("POST", {method: "dropBombs", type: "game", a: "game", data: data}, callBackdropBombs);
					availablebombs--;
				}
				else {
					
					callGETPOST("POST", {method: "dropBombs", type: "game", a: "game", data: data}, callBackdropBombs);
					changeWhoseTurnIsIt();
				}
				
				
			}
			else {
				alert("Either you or Opponent have not submitted their ships");
			}
		}
		else{
			alert("Its not your turn, "+youPlayerId);
		}
	}
	else
		alert("You lost. Please click END GAME to Exit");
}

function callBackdropBombs(data) {

    var arr = data.cell.split("_");
    var i = arr[0];
    var j = arr[1];
    piece2Arr[bombCount] = new Piece("guess", youPlayerId, i, j, 'bomb', bombCount);
   
    if(data.hit == true) {
        piece2Arr[bombCount].piece.setAttributeNS(null, 'class', 'bomb_hit');
    }
    else {
        piece2Arr[bombCount].piece.setAttributeNS(null, 'class', 'bomb_miss');
    }
    bombCount++;

    
}

function changeWhoseTurnIsIt(){
    
    callGETPOST("GET", {method:"changeWhoseTurnIsIt", type: "game", a:"game", data: gameId + "_" + opponentPlayerId}, function(data){
	
		document.getElementById('nyt').firstChild.data = "Turn: " + opponentPlayer;
		
		getWhoseTurnIsIt();
	});
}

function getWhoseTurnIsIt(){
	callGETPOST("GET", {method: "getTurn", type: "game", a:"game", data: gameId}, callBackWhoseTurnIsIt);
}

function callBackWhoseTurnIsIt(data) {
    turn = data.turn;
	//alert(turn+"_"+youPlayerId);
    if(data.turn == youPlayerId) {
		alert("Its your turn");
        
        document.getElementById('nyt').firstChild.data = "Turn: " + youPlayer;
       
        getYouPlayerBoard();
       
        availablebombs = opponentShipsLeft;
        
    }
    else {
      
        getTurnTimeout = setTimeout('getWhoseTurnIsIt()', 5000);
    }
	
}

function getYouPlayerBoard() {
	//alert("board");
    callGETPOST("GET", {method: "getTheBoard", type: "game", a:"game", data: gameId + "_" + youPlayerId}, callBackGetYouPlayerBoard);
}

function callBackGetYouPlayerBoard(data) {
    drawBoard(data, true);
	
	var i= data.length-1;
	
	var destroyerLife = parseInt(data[i--]);
	var cruiserLife = parseInt(data[i--]);
	var submarineLife = parseInt(data[i--]);
	var battleshipLife = parseInt(data[i--]);
    var acLife = parseInt(data[i--]);
	
   if (pieceArr[0].object.life != acLife || pieceArr[1].object.life != battleshipLife
        || pieceArr[2].object.life != cruiserLife
        || pieceArr[3].object.life != submarineLife
        || pieceArr[4].object.life != destroyerLife
        ) {
  var ajaxData = youPlayerId + "_" + pieceArr[0].object.life + "_" + pieceArr[1].object.life + "_" + pieceArr[2].object.life + "_"
            + pieceArr[3].object.life + "_"
            + pieceArr[4].object.life;
        callGETPOST("GET", {method: "updateLife", type: "game", a:"game", data: ajaxData}, callBackLifeUpdate);
    }

    if(shipsLeft==1)
	{
		alert("There is just : "+ shipsLeft + " ship left");
	}
	
    if (shipsLeft == 0) {
        var message = "You won this battle"+'|'+opponentPlayerId;
						
		callGETPOST("POST", {method: "sendMessage", type: "game", a: "game", data: message}, function(data){
		});
        alert(opponentPlayer + ' sunk all your ships!');
		
		win=1;
    }
}



function callBackLifeUpdate(data) {

}

function quit() {

	var r=confirm("Are you sure you want to quit?");
	
	if (r==true)
	{
		var data = youPlayerId;
		callGETPOST("POST", {method: "deleteFromAllGameTables", type: "game", a:"game", data: data}, function(data){});
		
		window.close();
	}else{
	
	}
}


