function setMove(which){		
    mover = which;
    //get the last position of the thing... (NOW through the transform=translate(x,y))
    var xy = getTransform(which);
    myX = xy[0];
    myY = xy[1];
    //getPiece(mover).putOnTop();
}
			
function releaseMove(evt){
    if(mover != '') {
        var hit = CheckEmptyCellAndDrop(evt.clientX, evt.clientY, mover);
        if(hit === false) {
            //snap back
            setTransform(mover, myX, myY);
        }
        mover = '';
    }
}
			
function go(evt){
    if(mover != ''){
        setTransform(mover, evt.clientX, evt.clientY);
    }
}

function CheckEmptyCellAndDrop(x, y, which){
    //change the x and y coords (mouse) to match the transform
    x = x - BOARDX;
    y = y - BOARDY;
    var currentPiece = getPiece(which);
    //detach this piece from the board
    currentPiece.drag();
    //go through ALL of the board
    for(var i = 0; i < BOARDWIDTH; i++){
        for(var j = 0; j < BOARDHEIGHT; j++){
            var drop = boardArr[i][j].myBBox;
            if((x > drop.x)
                && (x < (drop.x+drop.width))
                && (y > drop.y)
                && (y < (drop.y + drop.height))
                && (boardArr[i][j].occupied == '')
            ){
                if(currentPiece.isValidMove(i, j) === false) {
                    currentPiece.drop();
                    return false;
                }

                setTransform(which, boardArr[i][j].getTopLeftX(), boardArr[i][j].getTopLeftY());
				
                currentPiece.changeCell(i, j);
                return true;
            }
        }
    }
    currentPiece.drop();
    return false;
}

Piece.prototype.changeCell = function(row, col) {
    this.cellRow = row;
    this.cellCol = col;
    this.drop();
}

Piece.prototype.putOnTop = function() {
    document.getElementsByTagName('svg')[0].removeChild(this.piece);
    document.getElementsByTagName('svg')[0].appendChild(this.piece);
}

Piece.prototype.drag = function() {
    for(var i = 0; i < this.object.spaces; i++) {
        this.currentCell[i].notOccupied();
    }
    this.currentCell.length = 0;
}


Piece.prototype.drop = function() {
    for(var i = 0; i < this.object.spaces; i++) {
        if(this.object.vertical) {
            this.currentCell.push(boardArr[this.cellRow + i][this.cellCol]);
        }
        else {
            this.currentCell.push(boardArr[this.cellRow][this.cellCol + i]);
        }
        this.currentCell[i].isOccupied(this.id);
    }
}

Piece.prototype.isValidMove = function(row, col) {
    for(var k = 0; k < this.object.spaces; k++) {
        if(this.object.vertical == true) {
    
            if(boardArr[row+k] === undefined) {
                return false;
            }
      
            if(boardArr[row+k][col].occupied != '') {
                return false;
            }
        }
        else {
            
            if(boardArr[row][col+k] === undefined) {
                return false;
            }
          
            if(boardArr[row][col+k].occupied != '') {
                return false;
            }
        }
    }
}

////get Transform/////
//look at the id of the piece sent in and work on it's transform
//return an array of [0]=x, [1]=y
////////////////
function getTransform(which){
    var hold = document.getElementById(which).getAttributeNS(null, 'transform');
    var retVal = new Array();
    retVal[0] = hold.substring((hold.search(/\(/) + 1), hold.search(/,/));  //x value
    retVal[1] = hold.substring((hold.search(/,/) + 1), hold.search(/\)/));  //y value
    return retVal;
}
			
////set Transform/////
//look at the id, x, y of the piece sent in and set it's translate
////////////////
function setTransform(which,x,y){
    document.getElementById(which).setAttributeNS(null,'transform','translate('+x+','+y+')');
}


/////////// Execute code after the ships are set on the board ///////////////////////////////////

function submitShipsToStart() {
    var pieces = new Array();
    for(var i = 0; i < BOARDWIDTH; i++){
        for(var j = 0; j < BOARDHEIGHT; j++){
            if(boardArr[i][j].occupied) {
                pieces.push(i+"_"+j);
            }
        }
    }
    var piecesString = JSON.stringify(pieces);
    var data = youPlayerId+"|"+opponentPlayerId+"|"+piecesString;

	//alert(data);
	
    callGETPOST("GET", {method: "submitShipsToStart", type: "game", a: "game", data: data}, function(response){
		
		//alert(response);
		
		if(response == 1) {
			
			youPlayerShipsSet = true;
			alert("Your ships are set, Lets start the Battle !");
			//Remove listeners
			if(window.addEventListener) {
				document.getElementsByTagName('svg')[0].removeEventListener('mouseup', releaseMove, false);
				document.getElementsByTagName('svg')[0].removeEventListener('mousemove', go, false);
			 
			} else {
				//IE
				document.getElementsByTagName('svg')[0].detachEvent('onmouseup', releaseMove);
				document.getElementsByTagName('svg')[0].detachEvent('onmousemove', go);
				
			}

			if (turn != youPlayerId) {
				getTurn();
			}
		}    
	});
    
    var shipData = youPlayerId + "!";
		
    for (var k = 0; k < 5; k++) {
        var startRow = pieceArr[k].currentCell[0].row;
        var startCol = pieceArr[k].currentCell[0].col;
        var isVertical = pieceArr[k].object.vertical;
        shipData += startRow+"_"+startCol;
		
        if (k < 4)  { //not last one in the loop
            shipData += "!";
        }
    }
  
    callGETPOST("POST", {method: "shipLocations", type: "game", a: "game", data: shipData}, function(){});
	
    setShipsButton = $('#buttonShips').detach();
}

function checkShipsPresent() {
    var data = opponentPlayerId;
    callGETPOST("GET", {method: "checkShipsPresent", type: "game", a: "game", data: data}, callBackCheckShipsPresent);
}

function callBackCheckShipsPresent(data) {
    if(!data) {
        setTimeout('checkShipsPresent()', 7000);
    }
    else {
        opponentPlayerShipsSet = true;
		
        //Opponents ships are set
        alert("Your Opponent is ready !");
    }

}
