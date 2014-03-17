/**
 * @constructor
 * @param {string} board
 * @param {int} player
 * @param {int} cellRow
 * @param {int} cellCol
 * @param {string} type
 * @param {int} num
 * @param {boolean} isVertical
 * @this {Piece}
 */
function Piece(board, player, cellRow, cellCol, type, num, isVertical) {
    this.board      = board;        //ship/guess
    this.player     = player;       //type player (id)
    this.type       = type;         //piece needs to know what kind it is (checker)
    this.number     = num;          //0-11
    this.cellRow    = cellRow;
    this.cellCol    = cellCol;
    this.currentCell= new Array();
    this.isVertical = isVertical
	
    this.id = 'piece_' + this.board + '|' + this.number; //piece_ship|4 is the fifth ship.

    
    this.object  = eval('new ' + this.type + '(this);');
    this.piece = this.object.piece;
    this.piece.setAttributeNS(null, 'id', this.id);

	if(this.type != 'bomb') {
        this.piece.addEventListener('mousedown', function(){
            setMove(this.id);
        }, false);
    }
	
    document.getElementsByTagName('svg')[0].appendChild(this.piece);
}


function AircraftCarrier(parent) {
    this.parent = parent;
    this.name = "Aircraft Carrier";
    this.life = 5;
    this.spaces = 5;
    this.vertical = this.parent.isVertical;
    this.cssClass = "piece_AircraftCarrier";

    Ship(this);

    return this;
}

/**
 * Creates a new Battleship
 * @constructor
 * @param {Piece} parent
 * @this {Battleship}
 * @return {Battleship} Battleship
 */
function Battleship(parent) {
    this.parent = parent;
    this.name = "Battleship";
    this.life = 4;
    this.spaces = 4;
    this.vertical = this.parent.isVertical;
    this.cssClass = "piece_Battleship";

    Ship(this);

    return this;
}

/**
 * Creates a new Cruiser
 * @constructor
 * @param {Piece} parent
 * @this {Cruiser}
 * @return {Cruiser} Cruiser
 */
function Cruiser(parent) {
    this.parent = parent;
    this.name = "Cruiser";
    this.life = 3;
    this.spaces = 3;
    this.vertical = this.parent.isVertical;
    this.cssClass = "piece_Cruiser";

    Ship(this);

    return this;
}

/**
 * Creates a new Submarine
 * @constructor
 * @param {Piece} parent
 * @this {Submarine}
 * @return {Submarine} Submarine
 */
function Submarine(parent) {
    this.parent = parent;
    this.name = "Submarine";
    this.life = 3;
    this.spaces = 3;
    this.vertical = this.parent.isVertical;
    this.cssClass = "piece_Submarine";

    Ship(this);

    return this;
}

/**
 * Creates a new Destroyer
 * @constructor
 * @param {Piece} parent
 * @this {Destroyer}
 * @return {Destroyer} Destroyer
 */
function Destroyer(parent) {
    this.parent = parent;
    this.name = "Destroyer";
    this.life = 2;
    this.spaces = 2;
    this.vertical = this.parent.isVertical;
    this.cssClass = "piece_Destroyer";

    Ship(this);

    return this;
}

/**
 * Creates a g tag with a rect tag inside of it type is the ship. Populates the
 * currentCell array. Positions the ship on the board
 * @param {AircraftCarrier, Battleship, Cruiser, Submarine, Destroyer} type
 */
function Ship(type) {

    type.piece = document.createElementNS(svgns, 'g');
    type.piece.setAttributeNS(null, 'style', 'cursor:pointer');
    type.piece.setAttributeNS(null, 'class', type.cssClass);
    var rect = document.createElementNS(svgns, 'rect');

    //Make rectangle on the board depending on the ships spaces
    if(type.vertical) {
        rect.setAttributeNS(null, 'width', 35  + 'px');
        rect.setAttributeNS(null, 'height', 35 * type.spaces + 'px');
    }
    else {
        rect.setAttributeNS(null, 'width', 35 * type.spaces + 'px');
        rect.setAttributeNS(null, 'height', 35 + 'px');
    }

    //Add the new rectangle to the group
    type.piece.appendChild(rect);

    //Tell all the cells that they are occupied
	//alert(type.spaces);
	
    for(var i = 0; i < type.spaces; i++) {
        if(type.vertical) {
			//alert(type.parent.cellRow + i);
			//alert(type.parent.cellCol);
            type.parent.currentCell.push(boardArr[type.parent.cellRow + i][type.parent.cellCol]);
        }
        else {
            type.parent.currentCell.push(boardArr[type.parent.cellRow][type.parent.cellCol + i]);
        }

        type.parent.currentCell[i].isOccupied(type.parent.id);
    }

    type.parent.x = type.parent.currentCell[0].getTopLeftX();
    type.parent.y = type.parent.currentCell[0].getTopLeftY();

    type.piece.setAttributeNS(null, 'transform', 'translate(' + type.parent.x +', ' + type.parent.y + ')');
}

/**
 * Creates a new bomb. Assigns currentCell. Tells the cell that it has a bomb.
 * Creates the circles inside the g tag. Places it on the board.
 * @constructor
 * @param {Piece} parent
 * @this {bomb}
 * @return {bomb} bomb
 */
function bomb(parent) {
    this.parent = parent;
    this.piece = document.createElementNS(svgns, 'g');
    this.piece.setAttributeNS(null, 'class', 'piece_bomb');
    var circle = document.createElementNS(svgns, 'circle');
    var darkerCircle = document.createElementNS(svgns, 'circle');

    //Override currentCell to a single cell (not an array)
    if (this.parent.board == "ship") {
        this.parent.currentCell = boardArr[this.parent.cellRow][this.parent.cellCol];
    }
    else if (this.parent.board == "guess") {
        this.parent.currentCell = board2Arr[this.parent.cellRow][this.parent.cellCol];
        
    }

    //Tell the cell it has a bomb
    this.parent.currentCell.givebomb(this.parent.id);

    //Get the middle the cell
    this.parent.x = this.parent.currentCell.getCenterX(this.parent.board);
    this.parent.y = this.parent.currentCell.getCenterY(this.parent.board);

    //Set the circle's radius and append to g tag
    var darkerCircleRadius = (35-5)/2;
    var circleRadius = (35-15)/2;
    circle.setAttributeNS(null, 'r', circleRadius + 'px');
    darkerCircle.setAttributeNS(null, 'r', darkerCircleRadius + 'px');
    this.piece.appendChild(darkerCircle);
    this.piece.appendChild(circle);

    //Move it to the right spot
    this.piece.setAttributeNS(null, 'transform', 'translate(' + this.parent.x +', ' + this.parent.y + ')');

    return this;
}